<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\PropertyImage;
use App\Models\User;

class FlexMlsApiService
{
    private string $accessToken;
    private string $apiBaseUrl;
    private string $resoBaseUrl;
    private string $feedId;
    private string $listingsEndpoint;
    private ?int $listingAgentId = null;
    private bool $listingAgentResolved = false;
    
    // Rate limiting settings (Spark allows bursty replication pulls)
    private int $maxRequestsPerMinute = 120;
    private string $rateLimitCacheKey = 'flexmls_api_rate_limit';
    
    public function __construct()
    {
        $this->accessToken = (string) config('services.flexmls.access_token', '');
        $this->feedId = (string) config('services.flexmls.feed_id', '');
        $this->apiBaseUrl = rtrim((string) config('services.flexmls.base_url', 'https://replication.sparkapi.com'), '/');
        $this->resoBaseUrl = rtrim((string) config('services.flexmls.reso_url', 'https://replication.sparkapi.com/Version/3/Reso/OData'), '/');
        // Own-data ImagineMLS plans: /v1/my/listings. IDX: /v1/listings
        $this->listingsEndpoint = (string) config('services.flexmls.listings_endpoint', '/v1/my/listings');
    }

    /**
     * Get listings from the FlexMLS / Spark API (paginated).
     *
     * With the ImagineMLS own-data feed, prefer /v1/my/listings so we get
     * Jeremiah Brown's full inventory without geographic workarounds.
     */
    public function getListings(array $filters = []): array
    {
        if ($this->accessToken === '') {
            Log::error('FlexMLS access token is not configured');
            return [];
        }

        $endpoint = $this->listingsEndpoint;
        $pageSize = min((int) ($filters['limit'] ?? 200), 200);
        $maxPages = 50;
        $allRaw = [];

        Log::info('Fetching listings from FlexMLS API', [
            'endpoint' => $endpoint,
            'page_size' => $pageSize,
            'feed_id' => $this->feedId,
        ]);

        for ($page = 1; $page <= $maxPages; $page++) {
            $params = $this->buildListingParameters(array_merge($filters, [
                'limit' => $pageSize,
                'page' => $page,
            ]));

            $response = $this->makeApiRequest('GET', $endpoint, $params);

            if (!$response || !isset($response['D']['Results']) || !is_array($response['D']['Results'])) {
                break;
            }

            $batch = $response['D']['Results'];
            if ($batch === []) {
                break;
            }

            $allRaw = array_merge($allRaw, $batch);

            $pagination = $response['D']['Pagination'] ?? null;
            if (is_array($pagination) && isset($pagination['TotalPages'])) {
                if ($page >= (int) $pagination['TotalPages']) {
                    break;
                }
            } elseif (count($batch) < $pageSize) {
                break;
            }
        }

        Log::info('FlexMLS API listings fetched', [
            'raw_count' => count($allRaw),
            'endpoint' => $endpoint,
        ]);

        $listings = $this->processListingsResponse(['D' => ['Results' => $allRaw]]);

        // Apply client-side filtering (agent safety net + optional filters)
        return $this->applyClientSideFilters($listings, $filters);
    }

    /**
     * Fetch Property records from RESO Web API v3 (OData).
     *
     * Useful for replication / filtered queries. Photo URLs still come from
     * the Spark photos endpoint via importPropertyPhotos().
     */
    public function getResoProperties(array $options = []): array
    {
        if ($this->accessToken === '') {
            Log::error('FlexMLS access token is not configured');
            return [];
        }

        $query = [
            '$top' => $options['top'] ?? 100,
            '$count' => 'true',
        ];

        if (!empty($options['filter'])) {
            $query['$filter'] = $options['filter'];
        }

        if (!empty($options['select'])) {
            $query['$select'] = $options['select'];
        }

        if (!empty($options['expand'])) {
            $query['$expand'] = $options['expand'];
        }

        if (!empty($options['skiptoken'])) {
            $query['$skiptoken'] = $options['skiptoken'];
        }

        $url = $this->resoBaseUrl . '/Property?' . http_build_query($query);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Accept' => 'application/json',
                'User-Agent' => 'Jeremiah Brown Real Estate/1.0',
            ])->timeout(60)->get($url);

            if (!$response->successful()) {
                Log::error('RESO Property request failed', [
                    'status' => $response->status(),
                    'body' => substr($response->body(), 0, 500),
                ]);
                return [];
            }

            return $response->json() ?? [];
        } catch (\Exception $e) {
            Log::error('RESO Property request exception', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Get a specific listing by MLS number
     */
    public function getListing(string $mlsNumber): ?array
    {
        $endpoint = "/v1/listings/{$mlsNumber}";
        
        Log::info('Fetching listing from FlexMLS API', [
            'mls_number' => $mlsNumber,
            'endpoint' => $endpoint
        ]);
        
        $response = $this->makeApiRequest('GET', $endpoint);
        
        if (!$response) {
            return null;
        }
        
        $listings = $this->processListingsResponse($response);
        return !empty($listings) ? $listings[0] : null;
    }

    /**
     * Get listing photos (returns full Spark API photo data).
     *
     * Prefer `_expand=Photos` on the listing (one request). The dedicated
     * `/photos` sub-resource often ignores `_limit`/`_page` and returns the
     * full set with no Pagination object — so we must not page blindly.
     */
    public function getListingPhotos(string $listingKey): array
    {
        Log::info('Fetching listing photos from FlexMLS API', [
            'listing_key' => $listingKey,
        ]);

        // Fast path: expand Photos on the listing itself
        $expanded = $this->makeApiRequest('GET', "/v1/listings/{$listingKey}", [
            '_expand' => 'Photos',
        ]);

        if ($expanded && !empty($expanded['D']['Results'][0])) {
            $listing = $expanded['D']['Results'][0];
            $photos = $listing['StandardFields']['Photos']
                ?? $listing['Photos']
                ?? [];

            if (is_array($photos) && $photos !== []) {
                return $this->uniquePhotosById($photos);
            }
        }

        // Fallback: dedicated photos endpoint (single request — do not page
        // unless Pagination metadata is present)
        $endpoint = "/v1/listings/{$listingKey}/photos";
        $pageSize = 25;
        $maxPages = 50;
        $allPhotos = [];

        for ($page = 1; $page <= $maxPages; $page++) {
            $response = $this->makeApiRequest('GET', $endpoint, [
                '_limit' => $pageSize,
                '_pagination' => 1,
                '_page' => $page,
            ]);

            if (!$response || !isset($response['D']['Results']) || !is_array($response['D']['Results'])) {
                break;
            }

            $batch = $response['D']['Results'];
            if ($batch === []) {
                break;
            }

            $allPhotos = array_merge($allPhotos, $batch);

            $pagination = $response['D']['Pagination'] ?? null;
            if (is_array($pagination) && isset($pagination['TotalPages'])) {
                if ($page >= (int) $pagination['TotalPages']) {
                    break;
                }
            } else {
                // No pagination metadata: this MLS returns the full set in one
                // response (often ignoring _limit). Stop after the first page.
                break;
            }
        }

        return $this->uniquePhotosById($allPhotos);
    }

    /**
     * Deduplicate Spark photo records by Id.
     */
    private function uniquePhotosById(array $photos): array
    {
        $unique = [];
        foreach ($photos as $photo) {
            if (!is_array($photo)) {
                continue;
            }
            $id = $photo['Id'] ?? null;
            if ($id) {
                $unique[$id] = $photo;
            } else {
                $unique[] = $photo;
            }
        }

        return array_values($unique);
    }

    /**
     * Import photos for a property using the dedicated photos API endpoint
     */
    public function importPropertyPhotos($property, bool $updateExisting = false): int
    {
        if (!$property->api_data) {
            Log::warning('No API data found for property', ['property_id' => $property->id]);
            return 0;
        }

        $apiData = is_string($property->api_data) ? json_decode($property->api_data, true) : $property->api_data;
        
        // Use the ListingKey/Id (long format) for the photos API - this is the correct format
        $listingKey = $apiData['Id'] ?? $apiData['StandardFields']['ListingKey'] ?? null;
        
        if (!$listingKey) {
            Log::warning('No listing key found in API data', ['property_id' => $property->id]);
            return 0;
        }

        // PhotosCount is refreshed on every listing import, so when we already hold that
        // many photos locally there is nothing new to fetch. This keeps the daily sync
        // cheap instead of re-pulling every photo of every listing on each run.
        $expectedCount = (int) ($apiData['StandardFields']['PhotosCount'] ?? 0);
        $storedCount = $property->images()->where('api_source', 'flexmls')->count();

        if (!$updateExisting && $expectedCount > 0 && $storedCount >= $expectedCount) {
            return 0;
        }

        // Get ALL photos using the dedicated photos API endpoint (paginated)
        $photos = $this->getListingPhotos($listingKey);
        $usedPhotosEndpoint = $photos !== [];

        if (empty($photos)) {
            // Fall back to extracting from existing API data if photos API fails
            $photos = $this->extractPhotosFromApiData($apiData);

            if (empty($photos)) {
                Log::info('No photos found for property', [
                    'property_id' => $property->id,
                    'listing_key' => $listingKey,
                    'photos_count_field' => $apiData['StandardFields']['PhotosCount'] ?? 0,
                ]);
                return 0;
            }
        }

        $importedCount = 0;
        $sortOrder = $property->images()->max('sort_order') ?? 0;

        // One lookup for the whole listing instead of a query per photo
        $storedPhotoIds = PropertyImage::where('property_id', $property->id)
            ->whereNotNull('api_photo_id')
            ->pluck('id', 'api_photo_id');

        foreach ($photos as $index => $photo) {
            $sortOrder++;

            try {
                $photoId = $photo['Id'] ?? null;
                $storedId = $photoId ? ($storedPhotoIds[$photoId] ?? null) : null;

                if ($storedId !== null && !$updateExisting) {
                    continue;
                }

                $photoData = $this->transformPhotoData($photo, $property, $sortOrder);

                if ($storedId !== null) {
                    PropertyImage::find($storedId)?->update($photoData);
                } else {
                    PropertyImage::create($photoData);
                }

                $importedCount++;

            } catch (\Exception $e) {
                Log::error('Failed to import photo', [
                    'property_id' => $property->id,
                    'photo_id' => $photo['Id'] ?? 'unknown',
                    'error' => $e->getMessage(),
                    'photo_structure' => is_array($photo) ? array_keys($photo) : 'not_array'
                ]);
            }
        }

        Log::info('Photo import completed', [
            'property_id' => $property->id,
            'imported_count' => $importedCount,
            'total_photos' => count($photos),
            'used_photos_endpoint' => $usedPhotosEndpoint,
        ]);

        return $importedCount;
    }

    /**
     * Extract photos from existing API data
     */
    private function extractPhotosFromApiData(array $apiData): array
    {
        // Check StandardFields first (most common location)
        if (isset($apiData['StandardFields']['Photos']) && is_array($apiData['StandardFields']['Photos'])) {
            return $apiData['StandardFields']['Photos'];
        }
        
        // Check if Photos array is at the top level
        if (isset($apiData['Photos']) && is_array($apiData['Photos'])) {
            return $apiData['Photos'];
        }
        
        // Check if there's a PrimaryPhoto that we can use
        if (isset($apiData['PrimaryPhoto']) && is_array($apiData['PrimaryPhoto'])) {
            return [$apiData['PrimaryPhoto']];
        }
        
        return [];
    }

    /**
     * Transform Spark API photo data to our PropertyImage format
     */
    private function transformPhotoData(array $photo, $property, int $sortOrder): array
    {
        // Build photo URLs array from all available sizes
        $photoUrls = [];
        $urlFields = ['UriThumb', 'Uri300', 'Uri640', 'Uri800', 'Uri1024', 'Uri1280', 'Uri1600', 'Uri2048', 'UriLarge'];
        
        foreach ($urlFields as $field) {
            if (!empty($photo[$field])) {
                $photoUrls[strtolower(str_replace('uri', '', $field))] = $photo[$field];
            }
        }

        // Use the largest available image as the primary URL
        $primaryUrl = $photo['UriLarge'] ?? $photo['Uri2048'] ?? $photo['Uri1600'] ?? $photo['Uri1280'] ?? $photo['Uri1024'] ?? $photo['Uri800'] ?? $photo['Uri640'] ?? $photo['Uri300'] ?? $photo['UriThumb'] ?? null;

        return [
            'property_id' => $property->id,
            'api_photo_id' => $photo['Id'],
            'api_source' => 'flexmls',
            'filename' => $photo['Name'] ?? 'listing-photo.jpg',
            'path' => '', // Not storing locally, using API URLs
            'url' => $primaryUrl,
            'photo_urls' => $photoUrls,
            'title' => $photo['Name'] ?? 'Listing Photo',
            'caption' => $photo['Caption'] ?? '',
            'alt_text' => $this->generatePhotoAltText($property, $photo),
            'tags' => $photo['Tags'] ?? [],
            'sort_order' => $sortOrder,
            'is_primary' => $photo['Primary'] ?? false,
            'category' => $this->determinePhotoCategory($photo),
            'file_size' => null, // Not available from API
            'mime_type' => 'image/jpeg', // Assuming JPEG
            'width' => null, // Not available from API
            'height' => null, // Not available from API
        ];
    }

    /**
     * Generate alt text for photo accessibility
     */
    private function generatePhotoAltText($property, array $photo): string
    {
        $name = $photo['Name'] ?? '';
        $caption = $photo['Caption'] ?? '';
        
        if ($caption) {
            return $caption;
        }
        
        if ($name && $name !== 'Listing Photo') {
            return "{$name} - {$property->title}";
        }
        
        $tags = $photo['Tags'] ?? [];
        if (!empty($tags['Room'])) {
            $roomType = is_array($tags['Room']) ? implode(', ', $tags['Room']) : $tags['Room'];
            return "{$roomType} - {$property->title}";
        }
        
        return "Property photo - {$property->title}";
    }

    /**
     * Determine photo category based on API data
     */
    private function determinePhotoCategory(array $photo): string
    {
        $tags = $photo['Tags'] ?? [];
        
        // Check for room tags to determine category
        if (!empty($tags['Room'])) {
            $rooms = is_array($tags['Room']) ? $tags['Room'] : [$tags['Room']];
            
            foreach ($rooms as $room) {
                $room = strtolower($room);
                
                if (in_array($room, ['living', 'kitchen', 'bedroom', 'bathroom', 'dining', 'family', 'office', 'basement'])) {
                    return 'interior';
                }
                
                if (in_array($room, ['exterior', 'porch', 'deck', 'patio', 'yard'])) {
                    return 'exterior';
                }
            }
        }
        
        // Check photo name/caption for clues
        $name = strtolower($photo['Name'] ?? '');
        $caption = strtolower($photo['Caption'] ?? '');
        $text = $name . ' ' . $caption;
        
        if (strpos($text, 'aerial') !== false || strpos($text, 'drone') !== false || strpos($text, 'overhead') !== false) {
            return 'aerial';
        }
        
        if (strpos($text, 'interior') !== false || strpos($text, 'inside') !== false) {
            return 'interior';
        }
        
        if (strpos($text, 'land') !== false || strpos($text, 'acreage') !== false || strpos($text, 'field') !== false || strpos($text, 'pasture') !== false) {
            return 'land';
        }
        
        // Default to exterior for most property photos
        return 'exterior';
    }

    /**
     * Get available property types from the system info
     */
    public function getPropertyTypes(): array
    {
        $endpoint = '/v1/system';
        $response = $this->makeApiRequest('GET', $endpoint);
        
        if (!$response || !isset($response['D']['Results'])) {
            return $this->getDefaultPropertyTypes();
        }
        
        // Extract property types from system info if available
        // This might vary depending on MLS configuration
        return $this->getDefaultPropertyTypes();
    }

    /**
     * Build listing search parameters
     */
    private function buildListingParameters(array $filters): array
    {
        $params = [
            '_limit' => $filters['limit'] ?? 200,
            '_expand' => 'PrimaryPhoto',
            '_pagination' => 1,
        ];

        if (!empty($filters['page'])) {
            $params['_page'] = (int) $filters['page'];
        }

        if (!empty($filters['offset'])) {
            $params['_offset'] = $filters['offset'];
        }

        // Spark filter syntax for own-data / replication keys
        if (!empty($filters['status']) && !in_array($filters['status'], ['All', 'all'], true)) {
            $params['_filter'] = "MlsStatus Eq '{$filters['status']}'";
        }

        return $params;
    }

    /**
     * Make API request with rate limiting and error handling (public for debugging tools)
     */
    public function makeRawApiRequest(string $method, string $endpoint, array $params = []): ?array
    {
        return $this->makeApiRequest($method, $endpoint, $params);
    }

    /**
     * Make API request with rate limiting and error handling
     */
    private function makeApiRequest(string $method, string $endpoint, array $params = []): ?array
    {
        // Check rate limiting
        if (!$this->checkRateLimit()) {
            Log::warning('FlexMLS API rate limit exceeded');
            return null;
        }
        
        $url = $this->apiBaseUrl . $endpoint;
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'User-Agent' => 'Jeremiah Brown Real Estate/1.0',
                'Accept' => 'application/json',
            ])
            ->timeout(60) // Increase timeout for large requests
            ->retry(3, 1000) // Retry 3 times with 1 second delay
            ->when($method === 'GET', function ($http) use ($url, $params) {
                return $http->get($url, $params);
            }, function ($http) use ($method, $url, $params) {
                return $http->send($method, $url, [
                    'json' => $params
                ]);
            });
            
            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('FlexMLS API request successful', [
                    'endpoint' => $endpoint,
                    'status' => $response->status(),
                    'results_count' => is_array($data['D']['Results'] ?? null) ? count($data['D']['Results']) : 0
                ]);
                
                // Update rate limit tracking
                $this->updateRateLimit();
                
                return $data;
            } else {
                Log::error('FlexMLS API request failed', [
                    'endpoint' => $endpoint,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                return null;
            }
        } catch (\Exception $e) {
            Log::error('FlexMLS API request exception', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return null;
        }
    }

    /**
     * Process listings API response
     */
    private function processListingsResponse(array $response): array
    {
        if (!isset($response['D']['Results'])) {
            return [];
        }
        
        $listings = [];
        
        foreach ($response['D']['Results'] as $listing) {
            $processed = $this->transformApiListingToProperty($listing);
            if ($processed) {
                $listings[] = $processed;
            }
        }
        
        return $listings;
    }

    /**
     * Transform API listing data to our property format
     */
    private function transformApiListingToProperty(array $listing): array
    {
        // The replication API has data nested under StandardFields
        $data = $listing['StandardFields'] ?? $listing;
        
        // Build address string - use UnparsedAddress if available, otherwise build from components
        $address = '';
        if (!empty($data['UnparsedAddress'])) {
            // Remove city, state, zip from unparsed address to get street address
            $unparsed = $data['UnparsedAddress'];
            $parts = explode(',', $unparsed);
            $address = trim($parts[0] ?? '');
        } else {
            $address = trim(
                ($data['StreetNumber'] ?? '') . ' ' . 
                ($data['StreetName'] ?? '') . ' ' . 
                ($data['StreetSuffix'] ?? '')
            );
        }
        
        $fullAddress = $data['UnparsedAddress'] ?? $address;
        if (!$fullAddress && !empty($data['City'])) {
            $fullAddress = $address;
            if (!empty($data['City'])) {
                $fullAddress .= ', ' . $data['City'];
            }
            if (!empty($data['StateOrProvince'])) {
                $fullAddress .= ', ' . $data['StateOrProvince'];
            }
            if (!empty($data['PostalCode'])) {
                $fullAddress .= ' ' . $data['PostalCode'];
            }
        }
        
        return [
            'title' => $fullAddress ?: ($data['ListingId'] ?? 'Property Listing'),
            'description' => $this->cleanDescription($this->unmask($data['PublicRemarks'] ?? '')),
            'mls_number' => $this->unmask($data['ListingId'] ?? $data['MlsNumber'] ?? null),
            'status' => $this->mapMlsStatus((string) ($this->unmask($data['MlsStatus'] ?? 'Active') ?? 'Active')),
            'property_type' => $this->mapPropertyType((string) ($this->unmask($data['PropertySubType'] ?? $data['PropertyType'] ?? 'Single Family Residence') ?? 'Single Family Residence')),
            'price' => $this->parsePrice($this->unmask($data['ListPrice'] ?? 0)),
            'price_per_acre' => $this->calculatePricePerAcre(
                $this->unmask($data['ListPrice'] ?? 0),
                $this->unmask($data['LotSizeAcres'] ?? null)
            ),
            'street_address' => $address,
            'city' => (string) ($this->unmask($data['City'] ?? '') ?? ''),
            'county' => (string) ($this->unmask($data['CountyOrParish'] ?? '') ?? ''),
            'state' => (string) ($this->unmask($data['StateOrProvince'] ?? 'KY') ?? 'KY'),
            'zip_code' => (string) ($this->unmask($data['PostalCode'] ?? '') ?? ''),
            'latitude' => $this->parseNullableFloat($this->unmask($data['Latitude'] ?? null)),
            'longitude' => $this->parseNullableFloat($this->unmask($data['Longitude'] ?? null)),
            'total_acres' => $this->parseAcres($this->unmask($data['LotSizeAcres'] ?? null)),
            'tillable_acres' => $this->parseAcres($this->unmask($data['CultivatedArea'] ?? null)),
            'wooded_acres' => $this->parseAcres($this->unmask($data['WoodedArea'] ?? null)),
            'pasture_acres' => $this->parseAcres($this->unmask($data['PastureArea'] ?? null)),
            'wetland_acres' => $this->parseAcres($this->unmask($data['WetlandsAcreage'] ?? null)),
            'water_access' => $this->parseBoolean($this->unmask($data['WaterfrontYN'] ?? false)),
            'has_home' => $this->parseNullableInt($this->unmask($data['BedsTotal'] ?? null)) !== null
                || $this->parseNullableInt($this->unmask($data['LivingArea'] ?? null)) !== null,
            'home_sq_ft' => $this->parseNullableInt($this->unmask($data['LivingArea'] ?? null)),
            'home_bedrooms' => $this->parseNullableInt($this->unmask($data['BedsTotal'] ?? null)),
            'home_bathrooms' => $this->parseNullableFloat(
                $this->unmask($data['BathroomsTotalDecimal'] ?? $data['BathsTotal'] ?? null)
            ),
            'home_year_built' => $this->parseYear($this->unmask($data['YearBuilt'] ?? null)),
            'listing_date' => $this->parseDate($this->unmask($data['OnMarketDate'] ?? $data['ListingContractDate'] ?? null)),
            'last_updated' => $this->parseDate($this->unmask($data['ModificationTimestamp'] ?? null)),
            'days_on_market' => $this->calculateDaysOnMarket($this->unmask($data['OnMarketDate'] ?? null)),
            'public_remarks' => $this->cleanDescription($this->unmask($data['PublicRemarks'] ?? '')),
            'private_remarks' => $this->cleanDescription($this->unmask($data['PrivateRemarks'] ?? '')),
            'primary_image' => $this->extractPrimaryImageUrl($listing),
            'listing_agent_id' => $this->resolveListingAgentId(),
            'published_at' => now(),
            'featured' => false,
            'api_source' => 'flexmls',
            'api_data' => json_encode($listing), // Store original data for reference
        ];
    }

    /**
     * properties.listing_agent_id is a foreign key to users.id, so it must point at a
     * user that actually exists or every insert fails. On a freshly migrated database
     * there may be no users yet — the column is nullable, so leave it empty rather than
     * assuming an ID.
     */
    private function resolveListingAgentId(): ?int
    {
        if ($this->listingAgentResolved) {
            return $this->listingAgentId;
        }

        $configured = config('services.flexmls.listing_agent_id');

        $this->listingAgentId = $configured && User::whereKey($configured)->exists()
            ? (int) $configured
            : User::orderBy('id')->value('id');

        $this->listingAgentResolved = true;

        if ($this->listingAgentId === null) {
            Log::warning('No users exist; importing listings without a listing agent. Run db:seed to create the admin user.');
        }

        return $this->listingAgentId;
    }

    /**
     * Spark masks restricted fields as ******** — treat those as null.
     */
    private function unmask(mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            $trimmed = trim($value);
            if ($trimmed === '' || $trimmed === '********' || str_starts_with($trimmed, '****')) {
                return null;
            }
        }

        return $value;
    }

    private function parseNullableInt(mixed $value): ?int
    {
        if ($value === null || $value === '' || !is_numeric($value)) {
            return null;
        }

        return (int) $value;
    }

    private function parseNullableFloat(mixed $value): ?float
    {
        if ($value === null || $value === '' || !is_numeric($value)) {
            return null;
        }

        return (float) $value;
    }

    private function parseYear(mixed $value): ?int
    {
        $year = $this->parseNullableInt($value);
        if ($year === null || $year < 1800 || $year > ((int) date('Y') + 2)) {
            return null;
        }

        return $year;
    }

    /**
     * Helper methods for data transformation
     */
    private function cleanDescription(?string $description): string
    {
        if (!$description) return '';
        
        // Remove excessive whitespace and normalize line breaks
        $clean = preg_replace('/\s+/', ' ', $description);
        $clean = preg_replace('/\n+/', "\n", $clean);
        
        return trim($clean);
    }

    private function mapMlsStatus(string $status): string
    {
        $statusMap = [
            'Active' => 'active',
            'Pending' => 'pending',
            'Under Contract' => 'pending',
            'Contingent' => 'pending',
            'Sold' => 'sold',
            'Closed' => 'sold',
            // DB enum: active, pending, sold, off_market, draft
            'Cancelled' => 'off_market',
            'Canceled' => 'off_market',
            'Expired' => 'off_market',
            'Withdrawn' => 'off_market',
            'Delete' => 'off_market',
            'Temporary Off Market' => 'off_market',
        ];

        return $statusMap[$status] ?? 'active';
    }

    private function mapPropertyType(string $type): string
    {
        // Use MLS PropertySubType directly - no complex mapping needed
        $normalized = trim($type);
        
        // Return the MLS property type as-is, with fallback for unknown types
        return $normalized ?: 'Single Family Residence';
    }

    private function parsePrice($price): float
    {
        return (float) ($price ?? 0);
    }

    private function parseAcres($acres): ?float
    {
        if ($acres === null || $acres === '' || $acres === 0) {
            return null;
        }
        
        return (float) $acres;
    }

    private function calculatePricePerAcre($price, $acres): ?float
    {
        if (!$price || !$acres || $acres <= 0) {
            return null;
        }
        
        return round($price / $acres, 2);
    }

    private function parseBoolean($value): bool
    {
        if (is_bool($value)) return $value;
        if (is_string($value)) {
            return in_array(strtolower($value), ['yes', 'true', '1', 'y']);
        }
        return (bool) $value;
    }

    private function parseDate($date): ?\Carbon\Carbon
    {
        if (!$date) return null;
        
        try {
            return \Carbon\Carbon::parse($date);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function extractPrimaryImageUrl(array $listing): ?string
    {
        // The replication API may have photos in a different structure
        $data = $listing['StandardFields'] ?? $listing;
        
        // Try to get primary photo URL from the expanded data
        if (!empty($listing['PrimaryPhoto']['Uri800'])) {
            return $listing['PrimaryPhoto']['Uri800'];
        }
        
        if (!empty($listing['PrimaryPhoto']['Uri1024'])) {
            return $listing['PrimaryPhoto']['Uri1024'];
        }
        
        if (!empty($listing['PrimaryPhoto']['Uri640'])) {
            return $listing['PrimaryPhoto']['Uri640'];
        }
        
        if (!empty($listing['PrimaryPhoto']['Uri300'])) {
            return $listing['PrimaryPhoto']['Uri300'];
        }
        
        // Check if there's a Photos array in StandardFields
        if (!empty($data['Photos']) && is_array($data['Photos'])) {
            $firstPhoto = $data['Photos'][0] ?? null;
            if ($firstPhoto && isset($firstPhoto['Uri800'])) {
                return $firstPhoto['Uri800'];
            }
        }
        
        return null;
    }
    
    private function calculateDaysOnMarket(?string $onMarketDate): ?int
    {
        if (!$onMarketDate) return null;
        
        try {
            $marketDate = \Carbon\Carbon::parse($onMarketDate);
            return $marketDate->diffInDays(now());
        } catch (\Exception $e) {
            return null;
        }
    }

    private function checkRateLimit(): bool
    {
        $key = $this->rateLimitCacheKey;
        $requests = Cache::get($key, []);
        $now = time();
        
        // Remove requests older than 1 minute
        $requests = array_filter($requests, function($timestamp) use ($now) {
            return ($now - $timestamp) < 60;
        });
        
        return count($requests) < $this->maxRequestsPerMinute;
    }

    private function updateRateLimit(): void
    {
        $key = $this->rateLimitCacheKey;
        $requests = Cache::get($key, []);
        $requests[] = time();
        
        Cache::put($key, $requests, 120); // Store for 2 minutes
    }

    private function applyClientSideFilters(array $listings, array $filters): array
    {
        if (empty($listings)) {
            return $listings;
        }
        
        $filteredListings = $listings;
        
        // ALWAYS filter by Jeremiah Brown only - this is the most important filter
        $filteredListings = array_filter($filteredListings, function($listing) {
            return $this->isJeremiahBrownListing($listing);
        });
        
        // Filter by property type
        if (!empty($filters['property_type'])) {
            $filteredListings = array_filter($filteredListings, function($listing) use ($filters) {
                return strcasecmp($listing['property_type'], $filters['property_type']) === 0;
            });
        }
        
        // Filter by price range
        if (!empty($filters['min_price'])) {
            $filteredListings = array_filter($filteredListings, function($listing) use ($filters) {
                return ($listing['price'] ?? 0) >= $filters['min_price'];
            });
        }
        
        if (!empty($filters['max_price'])) {
            $filteredListings = array_filter($filteredListings, function($listing) use ($filters) {
                return ($listing['price'] ?? 0) <= $filters['max_price'];
            });
        }
        
        // Filter by acreage
        if (!empty($filters['min_acres'])) {
            $filteredListings = array_filter($filteredListings, function($listing) use ($filters) {
                return ($listing['total_acres'] ?? 0) >= $filters['min_acres'];
            });
        }
        
        // Filter by status (mapped DB statuses: active/pending/sold/inactive)
        if (!empty($filters['status']) && !in_array($filters['status'], ['All', 'all'], true)) {
            $wanted = $this->mapMlsStatus($filters['status']);
            $filteredListings = array_filter($filteredListings, function ($listing) use ($wanted) {
                return strcasecmp($listing['status'] ?? '', $wanted) === 0;
            });
        }
        
        // Re-index the array and limit results
        $filteredListings = array_values($filteredListings);
        
        // Apply limit
        if (!empty($filters['limit']) && count($filteredListings) > $filters['limit']) {
            $filteredListings = array_slice($filteredListings, 0, $filters['limit']);
        }
        
        return $filteredListings;
    }
    
    /**
     * Check if a listing belongs to Jeremiah Brown
     */
    private function isJeremiahBrownListing(array $listing): bool
    {
        // Get the original API data to check agent information
        $apiData = json_decode($listing['api_data'] ?? '{}', true);
        $standardFields = $apiData['StandardFields'] ?? [];
        
        // UPDATED: Check agent MLS ID first (most reliable method)
        $agentMlsId = $standardFields['ListAgentMlsId'] ?? '';
        $knownJeremiahIds = ['20271', '429520271']; // Both variations found in comprehensive search
        
        if (in_array($agentMlsId, $knownJeremiahIds)) {
            Log::info('Found Jeremiah Brown listing by Agent MLS ID', [
                'agent_mls_id' => $agentMlsId,
                'agent_name' => $standardFields['ListAgentName'] ?? 'unknown',
                'listing_id' => $listing['mls_number'] ?? 'unknown'
            ]);
            return true;
        }
        
        // Define possible variations of Jeremiah Brown's name
        $jeremiahBrownVariations = [
            'Jeremiah Brown',
            'JEREMIAH BROWN',
            'jeremiah brown',
            'Jeremiah',
            'Brown',
            'J Brown',
            'J. Brown',
            'JB',
        ];
        
        // Check ListAgentName
        $agentName = $standardFields['ListAgentName'] ?? '';
        if ($agentName) {
            foreach ($jeremiahBrownVariations as $variation) {
                if (stripos($agentName, $variation) !== false) {
                    Log::info('Found Jeremiah Brown listing by ListAgentName', [
                        'agent_name' => $agentName,
                        'listing_id' => $listing['mls_number'] ?? 'unknown'
                    ]);
                    return true;
                }
            }
        }
        
        // Check first name and last name separately
        $firstName = $standardFields['ListAgentFirstName'] ?? '';
        $lastName = $standardFields['ListAgentLastName'] ?? '';
        
        if ((stripos($firstName, 'Jeremiah') !== false && stripos($lastName, 'Brown') !== false)) {
            Log::info('Found Jeremiah Brown listing by first/last name', [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'listing_id' => $listing['mls_number'] ?? 'unknown'
            ]);
            return true;
        }
        
        // Also check by office name (JB Land & Home Realty)
        $officeName = $standardFields['ListOfficeName'] ?? '';
        if (stripos($officeName, 'JB Land') !== false || stripos($officeName, 'JB Land & Home') !== false) {
            Log::info('Found potential Jeremiah Brown listing by office', [
                'office_name' => $officeName,
                'agent_name' => $agentName,
                'listing_id' => $listing['mls_number'] ?? 'unknown'
            ]);
            return true;
        }
        
        // Log rejected listings for debugging
        Log::debug('Rejected listing - not Jeremiah Brown', [
            'agent_name' => $agentName,
            'agent_mls_id' => $agentMlsId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'listing_id' => $listing['mls_number'] ?? 'unknown',
            'office_name' => $standardFields['ListOfficeName'] ?? 'unknown'
        ]);
        
        return false;
    }

    private function getDefaultPropertyTypes(): array
    {
        return [
            'Residential',
            'Single Family Residence',
            'Farm',
            'Land',
            'Commercial',
            'Condo',
            'Townhouse',
            'Mobile Home',
            'Vacant Land',
            'Waterfront'
        ];
    }
}
