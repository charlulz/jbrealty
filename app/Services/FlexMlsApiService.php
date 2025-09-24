<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class FlexMlsApiService
{
    private string $accessToken;
    private string $apiBaseUrl;
    private string $feedId;
    
    // Rate limiting settings
    private int $maxRequestsPerMinute = 60;
    private string $rateLimitCacheKey = 'flexmls_api_rate_limit';
    
    public function __construct()
    {
        $this->accessToken = config('services.flexmls.access_token', 'bbqc409db06nezg8fdsz0jaw7');
        $this->feedId = config('services.flexmls.feed_id', 'ddnzarj1vajdzzvcdp2es4tro');
        $this->apiBaseUrl = config('services.flexmls.base_url', 'https://api.sparkplatform.com');
    }

    /**
     * Get listings from the FlexMLS API
     */
    public function getListings(array $filters = []): array
    {
        $endpoint = '/v1/listings';
        $params = $this->buildListingParameters($filters);
        
        Log::info('Fetching listings from FlexMLS API', [
            'endpoint' => $endpoint,
            'params' => $params,
            'feed_id' => $this->feedId
        ]);
        
        $response = $this->makeApiRequest('GET', $endpoint, $params);
        
        Log::info('FlexMLS API response received', [
            'response_has_data' => !empty($response),
            'response_structure' => $response ? array_keys($response) : 'null',
            'has_results' => isset($response['D']['Results']),
            'results_count' => isset($response['D']['Results']) ? count($response['D']['Results']) : 0
        ]);
        
        if (!$response) {
            return [];
        }
        
        $listings = $this->processListingsResponse($response);
        
        // Apply client-side filtering since the replication API has limited filtering
        return $this->applyClientSideFilters($listings, $filters);
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
     * Get listing photos
     */
    public function getListingPhotos(string $mlsNumber): array
    {
        $endpoint = "/v1/listings/{$mlsNumber}/photos";
        
        Log::info('Fetching listing photos from FlexMLS API', [
            'mls_number' => $mlsNumber,
            'endpoint' => $endpoint
        ]);
        
        $response = $this->makeApiRequest('GET', $endpoint);
        
        if (!$response || !isset($response['D']['Results'])) {
            return [];
        }
        
        $photos = [];
        foreach ($response['D']['Results'] as $photo) {
            $photos[] = [
                'id' => $photo['Id'] ?? null,
                'name' => $photo['Name'] ?? '',
                'url' => $photo['Uri1024'] ?? $photo['Uri800'] ?? $photo['Uri640'] ?? $photo['Uri300'] ?? '',
                'caption' => $photo['Caption'] ?? '',
                'primary' => ($photo['Primary'] ?? 'false') === 'true',
                'order' => $photo['Order'] ?? 0,
            ];
        }
        
        // Sort by order
        usort($photos, function($a, $b) {
            return ($a['order'] ?? 0) <=> ($b['order'] ?? 0);
        });
        
        return $photos;
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
            '_limit' => $filters['limit'] ?? 500, // Use 500 as this found our listing
            '_expand' => 'PrimaryPhoto',
        ];
        
        // Add pagination support if needed - but note that pagination seems to miss some listings
        if (!empty($filters['offset'])) {
            $params['_offset'] = $filters['offset'];
        }
        
        // The replication API might not support complex filtering
        // Let's start with minimal filters and see what works
        
        // Don't add any status filters by default - get all listings regardless of status
        // Only add status filter if specifically requested
        if (!empty($filters['status']) && $filters['status'] !== 'All') {
            $params['$filter'] = "MlsStatus eq '{$filters['status']}'";
        }
        
        // Skip other complex filters for now until we understand the API better
        // We'll filter the results after fetching them, including agent filtering
        
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
            'description' => $this->cleanDescription($data['PublicRemarks'] ?? ''),
            'mls_number' => $data['ListingId'] ?? $data['MlsNumber'] ?? null,
            'status' => $this->mapMlsStatus($data['MlsStatus'] ?? 'Active'),
            'property_type' => $this->mapPropertyType($data['PropertyType'] ?? 'Residential'),
            'price' => $this->parsePrice($data['ListPrice'] ?? 0),
            'price_per_acre' => $this->calculatePricePerAcre(
                $data['ListPrice'] ?? 0,
                $data['LotSizeAcres'] ?? null
            ),
            'street_address' => $address,
            'city' => $data['City'] ?? '',
            'county' => $data['CountyOrParish'] ?? '',
            'state' => $data['StateOrProvince'] ?? 'KY',
            'zip_code' => $data['PostalCode'] ?? '',
            'latitude' => $data['Latitude'] ?? null,
            'longitude' => $data['Longitude'] ?? null,
            'total_acres' => $this->parseAcres($data['LotSizeAcres'] ?? null),
            'tillable_acres' => $this->parseAcres($data['CultivatedArea'] ?? null),
            'wooded_acres' => $this->parseAcres($data['WoodedArea'] ?? null),
            'pasture_acres' => $this->parseAcres($data['PastureArea'] ?? null),
            'wetland_acres' => $this->parseAcres($data['WetlandsAcreage'] ?? null),
            'water_access' => $this->parseBoolean($data['WaterfrontYN'] ?? false),
            'has_home' => !empty($data['BedsTotal']) || !empty($data['LivingArea']),
            'home_sq_ft' => $data['LivingArea'] ?? null,
            'home_bedrooms' => $data['BedsTotal'] ?? null,
            'home_bathrooms' => $data['BathroomsTotalDecimal'] ?? $data['BathsTotal'] ?? null,
            'home_year_built' => $data['YearBuilt'] ?? null,
            'listing_date' => $this->parseDate($data['OnMarketDate'] ?? $data['ListingContractDate']),
            'last_updated' => $this->parseDate($data['ModificationTimestamp']),
            'days_on_market' => $this->calculateDaysOnMarket($data['OnMarketDate'] ?? null),
            'public_remarks' => $this->cleanDescription($data['PublicRemarks'] ?? ''),
            'private_remarks' => $this->cleanDescription($data['PrivateRemarks'] ?? ''),
            'primary_image' => $this->extractPrimaryImageUrl($listing),
            'listing_agent_id' => 1, // Default agent, adjust as needed
            'published_at' => now(),
            'featured' => false,
            'api_source' => 'flexmls',
            'api_data' => json_encode($listing), // Store original data for reference
        ];
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
            'Cancelled' => 'inactive',
            'Expired' => 'inactive',
            'Withdrawn' => 'inactive',
        ];

        return $statusMap[$status] ?? 'active';
    }

    private function mapPropertyType(string $type): string
    {
        $typeMap = [
            'Single Family Residence' => 'residential',
            'Single Family' => 'residential',
            'Residential' => 'residential',
            'Farm' => 'farms',
            'Farm/Ranch' => 'farms',
            'Agriculture' => 'farms',
            'Land' => 'hunting',
            'Vacant Land' => 'hunting',
            'Unimproved Land' => 'hunting',
            'Commercial' => 'commercial',
            'Ranch' => 'ranches',
            'Waterfront' => 'waterfront',
            'Mobile Home' => 'residential',
            'Condo' => 'residential',
            'Townhouse' => 'residential',
        ];

        return $typeMap[$type] ?? 'residential';
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
        
        // Filter by status (if not already handled server-side)
        if (!empty($filters['status'])) {
            $filteredListings = array_filter($filteredListings, function($listing) use ($filters) {
                return strcasecmp($listing['status'], $filters['status']) === 0;
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
