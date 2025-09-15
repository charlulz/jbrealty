<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropertyScrapingService
{
    private Client $client;
    private array $results = [
        'processed' => 0,
        'imported' => 0,
        'failed' => 0,
        'errors' => []
    ];

    // Rate limiting and politeness settings
    private int $delayBetweenRequests = 2; // seconds
    private int $maxRetries = 3;
    private array $userAgents = [
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.1 Safari/605.1.15',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    ];

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 30,
            'headers' => [
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.5',
                'Accept-Encoding' => 'gzip, deflate',
                'DNT' => '1',
                'Connection' => 'keep-alive',
                'Upgrade-Insecure-Requests' => '1',
            ]
        ]);
    }

    /**
     * ⚠️ IMPORTANT: Legal and Ethical Web Scraping
     * 
     * Before using this service:
     * 1. Check the website's robots.txt file
     * 2. Review Terms of Service
     * 3. Respect rate limits and be polite
     * 4. Only scrape data you have permission to use
     * 5. Consider contacting the site for API access first
     * 6. Use this responsibly and ethically
     */
    public function scrapeListings(string $url, array $options = []): array
    {
        $this->resetResults();
        
        // Validate URL and check basic compliance
        if (!$this->isScrapingAllowed($url)) {
            $this->results['errors'][] = "Scraping may not be allowed for this domain. Please check robots.txt and ToS.";
            return $this->results;
        }

        try {
            // Get the site type from URL
            $siteType = $this->detectSiteType($url);
            
            Log::info("Starting property scraping", [
                'url' => $url,
                'site_type' => $siteType,
                'options' => $options
            ]);

            // Scrape based on site type
            $listings = match($siteType) {
                'imagineyourhome' => $this->scrapeImagineYourHome($url, $options),
                'zillow' => $this->scrapeZillow($url, $options),
                'generic' => $this->scrapeGeneric($url, $options),
                default => []
            };

            if (empty($listings)) {
                $this->results['errors'][] = "No listings found or unable to parse the page structure";
                return $this->results;
            }

            // Convert to CSV for import processing
            if ($options['save_to_csv'] ?? true) {
                $csvPath = $this->saveListingsToCSV($listings, $siteType);
                $this->results['csv_file'] = $csvPath;
            }

            $this->results['processed'] = count($listings);
            $this->results['listings'] = $listings;

            return $this->results;

        } catch (\Exception $e) {
            $this->results['errors'][] = "Scraping failed: " . $e->getMessage();
            Log::error('Property scraping error', [
                'url' => $url,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return $this->results;
        }
    }

    /**
     * Enhanced scraping that visits individual property pages for complete data
     */
    public function scrapeListingsWithDetails(string $url, array $options = []): array
    {
        $this->resetResults();
        
        Log::info("Starting enhanced deep scraping", [
            'url' => $url,
            'options' => $options
        ]);
        
        try {
            // First, get the basic listing from the office page
            $basicListings = $this->scrapeListings($url, array_merge($options, ['save_to_csv' => false]));
            
            if (empty($basicListings['listings'])) {
                throw new \Exception("No basic listings found to enhance");
            }
            
            $enhancedListings = [];
            $allListings = $basicListings['listings'];
            $limit = $options['limit'] ?? null;
            
            // Apply limit if specified
            if ($limit && $limit > 0) {
                $allListings = array_slice($allListings, 0, $limit);
                Log::info("Limited to first {$limit} properties for testing");
            }
            
            $total = count($allListings);
            
            Log::info("Found {$total} listings, now scraping individual pages for details");
            
            foreach ($allListings as $index => $basicListing) {
                $current = $index + 1;
                
                Log::info("Scraping detailed page {$current}/{$total}: {$basicListing['title']}");
                
                try {
                    $detailedListing = $this->scrapeIndividualPropertyPage($basicListing, $options);
                    
                    if ($detailedListing) {
                        $enhancedListings[] = $detailedListing;
                        $this->results['processed']++;
                        
                        // Add delay between requests
                        $this->politeSleep();
                    } else {
                        // If detailed scraping fails, keep the basic listing
                        $enhancedListings[] = $basicListing;
                        $this->results['errors'][] = "Failed to get detailed data for {$basicListing['title']}, using basic data";
                    }
                    
                } catch (\Exception $e) {
                    Log::error("Failed to scrape individual property page", [
                        'property' => $basicListing,
                        'error' => $e->getMessage()
                    ]);
                    
                    // Fall back to basic listing
                    $enhancedListings[] = $basicListing;
                    $this->results['errors'][] = "Failed to enhance {$basicListing['title']}: " . $e->getMessage();
                }
            }
            
            // Save enhanced listings to CSV
            if ($options['save_to_csv'] ?? true) {
                $csvPath = $this->saveListingsToCSV($enhancedListings, 'imagineyourhome_enhanced');
                $this->results['csv_file'] = $csvPath;
            }
            
            $this->results['listings'] = $enhancedListings;
            $this->results['processed'] = count($enhancedListings);
            
            Log::info("Enhanced scraping completed", [
                'total_listings' => count($enhancedListings),
                'errors' => count($this->results['errors'])
            ]);
            
        } catch (\Exception $e) {
            $this->results['errors'][] = "Enhanced scraping failed: " . $e->getMessage();
            Log::error('Enhanced scraping error', [
                'url' => $url,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
        
        return $this->results;
    }

    /**
     * Scrape an individual property page for detailed information
     */
    private function scrapeIndividualPropertyPage(array $basicListing, array $options): ?array
    {
        // Build the individual property page URL
        $propertyUrl = $this->buildIndividualPropertyUrl($basicListing);
        
        if (!$propertyUrl) {
            Log::warning("Could not build property URL", ['listing' => $basicListing]);
            return null;
        }
        
        try {
            Log::info("Fetching detailed property page: {$propertyUrl}");
            
            $html = $this->fetchPage($propertyUrl, ['delay' => $options['delay'] ?? 2]);
            
            // Success! We got the full property page content
            
            // Check if we got a 404 or error page (be more specific)
            if (str_contains($html, 'Oh dear!') && str_contains($html, 'couldn\'t find this page')) {
                Log::warning("Property page returned 404", ['url' => $propertyUrl]);
                return null;
            }
            
            // Parse the detailed property page
            if (preg_match('/<script id="__NEXT_DATA__" type="application\/json">(.*?)<\/script>/s', $html, $matches)) {
                $jsonData = json_decode($matches[1], true);
                
                // Extract detailed property data from JSON
                $detailedData = $this->extractDetailedPropertyData($jsonData, $html);
                
                if ($detailedData) {
                    // Merge basic listing with detailed data
                    $enhancedListing = array_merge($basicListing, $detailedData);
                    
                    Log::info("Successfully enhanced property data", [
                        'title' => $enhancedListing['title'],
                        'images_found' => substr_count($enhancedListing['image_urls'] ?? '', ',') + 1,
                        'description_length' => strlen($enhancedListing['description'] ?? '')
                    ]);
                    
                    return $enhancedListing;
                }
            }
            
            // Fallback to HTML parsing if JSON parsing fails
            return $this->parseDetailedPropertyHTML($html, $basicListing);
            
        } catch (\Exception $e) {
            Log::error("Failed to scrape individual property page", [
                'url' => $propertyUrl,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Build individual property page URL from basic listing data
     */
    private function buildIndividualPropertyUrl(array $listing): ?string
    {
        $mlsId = $listing['mls_number'] ?? $listing['external_id'] ?? null;
        $address = $listing['address'] ?? $listing['title'] ?? null;
        
        if (!$mlsId || !$address) {
            return null;
        }
        
        // Clean and format address for URL
        $addressSlug = $this->createAddressSlug($address);
        $propertyUrl = "https://www.imagineyourhome.com/address/{$addressSlug}/{$mlsId}";
        
        Log::info("Building property URL", [
            'original_address' => $address,
            'mls_id' => $mlsId,
            'address_slug' => $addressSlug,
            'final_url' => $propertyUrl
        ]);
        
        return $propertyUrl;
    }

    /**
     * Create URL-friendly address slug
     */
    private function createAddressSlug(string $address): string
    {
        // Based on working URL: "18A Hall Road, Grayson, KY 41143" -> "18A-Hall-Road-Grayson"
        
        // Extract parts before state/zip
        if (preg_match('/^(.*?),\s*([^,]+),\s*(KY|Kentucky)\s*\d{5}.*$/i', $address, $matches)) {
            $streetAddress = trim($matches[1]); // "18A Hall Road"
            $city = trim($matches[2]); // "Grayson"
            
            // Combine street and city
            $fullAddress = $streetAddress . ', ' . $city;
        } else {
            // Fallback: remove state and ZIP
            $fullAddress = preg_replace('/,\s*(KY|Kentucky)\s*\d{5}.*$/i', '', $address);
        }
        
        // Clean and format
        $slug = preg_replace('/[^\w\s-]/', '', $fullAddress); // Remove special chars except spaces/hyphens
        $slug = preg_replace('/\s+/', '-', trim($slug)); // Replace spaces with hyphens
        
        return $slug;
    }

    /**
     * Extract detailed property data from individual page JSON
     */
    private function extractDetailedPropertyData($jsonData, string $html): ?array
    {
        $detailedData = [];
        
        try {
            // FIRST PRIORITY: Extract from Next.js __NEXT_DATA__ (most reliable for property details)
            if ($jsonData && isset($jsonData['props']['pageProps']['properties'][0])) {
                $propertyData = $jsonData['props']['pageProps']['properties'][0];
                
                // Get the property description from PublicRemarks (this is the main description)
                if (isset($propertyData['PublicRemarks']) && !empty($propertyData['PublicRemarks'])) {
                    $description = trim($propertyData['PublicRemarks']);
                    // Clean up line breaks and normalize whitespace
                    $description = preg_replace('/\s*\n\s*/', ' ', $description);
                    $description = preg_replace('/\s+/', ' ', $description);
                    
                    if (strlen($description) > 50) {  // Basic length check
                        $detailedData['description'] = $description;
                        Log::info("Successfully extracted description from Next.js PublicRemarks", [
                            'length' => strlen($description),
                            'preview' => substr($description, 0, 100) . '...'
                        ]);
                    }
                }
                
                // Extract images from Media array
                if (isset($propertyData['Media']) && is_array($propertyData['Media'])) {
                    $imageUrls = [];
                    foreach ($propertyData['Media'] as $media) {
                        if (isset($media['MediaURL']) && str_contains($media['MediaURL'], 'sparkplatform.com') && 
                            str_contains($media['MediaURL'], '.jpg')) {
                            $imageUrls[] = $media['MediaURL'];
                        }
                    }
                    if (!empty($imageUrls)) {
                        $detailedData['image_urls'] = implode(',', $imageUrls);
                        Log::info("Extracted {count} images from Next.js Media array", ['count' => count($imageUrls)]);
                    }
                }
                
                // Extract additional data if available
                if (isset($propertyData['LotFeatures']) && is_array($propertyData['LotFeatures'])) {
                    $detailedData['lot_features'] = implode(', ', $propertyData['LotFeatures']);
                }
            }
            
            // FALLBACK: Try JSON-LD structured data only if Next.js didn't provide description
            if (empty($detailedData['description']) && preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $matches)) {
                Log::info("Falling back to JSON-LD extraction as Next.js didn't provide description");
                
                foreach ($matches[1] as $jsonString) {
                    $ldJsonData = json_decode($jsonString, true);
                    if (!$ldJsonData) continue;
                    
                    // Look for description in various JSON-LD structures
                    $possibleDescriptions = [
                        $ldJsonData['description'] ?? null,
                        $ldJsonData['accommodationFloorPlan']['description'] ?? null,
                        $ldJsonData['seller']['description'] ?? null,
                        $ldJsonData['offers']['seller']['description'] ?? null,
                        $ldJsonData['@graph'][0]['description'] ?? null,
                        $ldJsonData['@graph'][1]['description'] ?? null,
                    ];
                    
                    foreach ($possibleDescriptions as $desc) {
                        if ($desc && is_string($desc) && strlen($desc) > 200) {
                            $cleanDesc = trim(strip_tags($desc));
                            $cleanDesc = preg_replace('/\s+/', ' ', $cleanDesc);
                            
                            if (!str_contains($cleanDesc, 'Imagine Your Home has') && 
                                !str_contains($cleanDesc, 'MLS #') && 
                                strlen($cleanDesc) > 300 &&
                                (str_contains($cleanDesc, 'Acres') || str_contains($cleanDesc, 'Discover') || 
                                 str_contains($cleanDesc, 'Located') || str_contains($cleanDesc, 'property'))) {
                                
                                $detailedData['description'] = $cleanDesc;
                                break 2;
                            }
                        }
                    }
                }
                
                // Extract images from structured data (fallback)
                if (empty($detailedData['image_urls']) && isset($ldJsonData['image']) && is_array($ldJsonData['image'])) {
                    $imageUrls = [];
                    foreach ($ldJsonData['image'] as $image) {
                        if (isset($image['url']) && strpos($image['url'], 'sparkplatform') !== false) {
                            $imageUrls[] = $image['url'];
                        }
                    }
                    if (!empty($imageUrls)) {
                        $detailedData['image_urls'] = implode(',', $imageUrls);
                    }
                }
            }
            
            // Look for property data in the Next.js page props as fallback
            if (empty($detailedData['description']) && isset($jsonData['props']['pageProps']['property'])) {
                $propertyData = $jsonData['props']['pageProps']['property'];
                
                // Extract enhanced data
                if (isset($propertyData['description']) && strlen($propertyData['description']) > 50) {
                    $detailedData['description'] = $propertyData['description'];
                }
                
                if (isset($propertyData['images']) && is_array($propertyData['images'])) {
                    $imageUrls = [];
                    foreach ($propertyData['images'] as $image) {
                        if (is_string($image)) {
                            $imageUrls[] = $image;
                        } elseif (isset($image['url'])) {
                            $imageUrls[] = $image['url'];
                        }
                    }
                    $detailedData['image_urls'] = implode(',', $imageUrls);
                }
                
                if (isset($propertyData['lotFeatures'])) {
                    $detailedData['lot_features'] = is_array($propertyData['lotFeatures']) 
                        ? implode(', ', $propertyData['lotFeatures'])
                        : $propertyData['lotFeatures'];
                }
            }
            
            // Fallback to HTML parsing for description and images
            if (empty($detailedData['description'])) {
                $detailedData['description'] = $this->extractDescriptionFromHTML($html);
            }
            
            if (empty($detailedData['image_urls'])) {
                $detailedData['image_urls'] = $this->extractAllImagesFromHTML($html);
            }
            
            if (empty($detailedData['lot_features'])) {
                $detailedData['lot_features'] = $this->extractLotFeaturesFromHTML($html);
            }
            
            return !empty($detailedData) ? $detailedData : null;
            
        } catch (\Exception $e) {
            Log::warning("Failed to extract detailed property data", ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Extract description from HTML content
     */
    private function extractDescriptionFromHTML(string $html): string
    {
        // Try to find the main property description in various HTML patterns
        $patterns = [
            // Look for descriptions that start with common property description phrases
            '/(?:Discover your own|Located|This property|Welcome to|Nestled|Situated|Offering)[^<]*(?:<[^>]*>[^<]*)*?(?=(?:<\/p>|<div|<section|Details:|Lot features:|Property type:))/si',
            
            // Look for longer text blocks that might be descriptions
            '/<p[^>]*>([^<]{200,})<\/p>/si',
            
            // Look for description in structured content
            '/(?:class=["\'][^"\']*description[^"\']*["\'][^>]*>|id=["\'][^"\']*description[^"\']*["\'][^>]*>)([^<]+)/si',
            
            // Look for property overview content
            '/(?:overview|description)[^>]*>[\s]*([^<]{100,})/si',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $html, $matches)) {
                $description = isset($matches[1]) ? trim(strip_tags($matches[1])) : trim(strip_tags($matches[0]));
                
                // Only return if it's substantial and not just meta description
                if (strlen($description) > 100 && !str_contains($description, 'Imagine Your Home has')) {
                    return $description;
                }
            }
        }
        
        // Last resort: try to extract any substantial text content (but avoid meta description)
        if (preg_match('/>([^<]{150,})</s', $html, $matches)) {
            $description = trim(strip_tags($matches[1]));
            if (!str_contains($description, 'Imagine Your Home has') && !str_contains($description, 'MLS #')) {
                return $description;
            }
        }
        
        return '';
    }

    /**
     * Extract all images from HTML content
     */
    private function extractAllImagesFromHTML(string $html): string
    {
        $images = [];
        
        // Look for high-resolution images
        preg_match_all('/https:\/\/cdn\.photos\.sparkplatform\.com[^"\s]+/i', $html, $matches);
        
        foreach ($matches[0] as $imageUrl) {
            if (!in_array($imageUrl, $images)) {
                $images[] = $imageUrl;
            }
        }
        
        // Also check for other image patterns
        preg_match_all('/<img[^>]+src=["\']([^"\']*)["\'][^>]*>/i', $html, $imgMatches);
        
        foreach ($imgMatches[1] as $src) {
            if (filter_var($src, FILTER_VALIDATE_URL) && 
                (str_contains($src, 'sparkplatform') || str_contains($src, 'imagineyourhome'))) {
                if (!in_array($src, $images)) {
                    $images[] = $src;
                }
            }
        }
        
        return implode(',', $images);
    }

    /**
     * Extract lot features from HTML
     */
    private function extractLotFeaturesFromHTML(string $html): string
    {
        // Look for lot features section
        if (preg_match('/Lot features[^<]*<[^>]*>([^<]+)/i', $html, $matches)) {
            return trim($matches[1]);
        }
        
        return '';
    }

    /**
     * Parse detailed property HTML as fallback
     */
    private function parseDetailedPropertyHTML(string $html, array $basicListing): ?array
    {
        $crawler = new Crawler($html);
        
        try {
            $enhancedListing = $basicListing;
            
            // Try to extract description
            $description = $this->extractDescriptionFromHTML($html);
            if ($description) {
                $enhancedListing['description'] = $description;
            }
            
            // Try to extract all images
            $images = $this->extractAllImagesFromHTML($html);
            if ($images) {
                $enhancedListing['image_urls'] = $images;
            }
            
            // Try to extract lot features
            $lotFeatures = $this->extractLotFeaturesFromHTML($html);
            if ($lotFeatures) {
                $enhancedListing['lot_features'] = $lotFeatures;
            }
            
            return $enhancedListing;
            
        } catch (\Exception $e) {
            Log::warning("HTML parsing fallback failed", ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Scrape ImagineYourHome.com listings
     */
    private function scrapeImagineYourHome(string $url, array $options): array
    {
        $listings = [];
        
        try {
            $html = $this->fetchPage($url);
            
            // ImagineYourHome uses Next.js with server-side data in __NEXT_DATA__ script
            if (preg_match('/<script id="__NEXT_DATA__" type="application\/json">(.*?)<\/script>/s', $html, $matches)) {
                $jsonData = json_decode($matches[1], true);
                
                if (isset($jsonData['props']['pageProps']['offices'][0]['Listings'])) {
                    $propertyListings = $jsonData['props']['pageProps']['offices'][0]['Listings'];
                    
                    Log::info("Found " . count($propertyListings) . " listings in ImagineYourHome JSON data");
                    
                    foreach ($propertyListings as $property) {
                        try {
                            $listing = $this->transformImagineYourHomeProperty($property);
                            if ($listing) {
                                $listings[] = $listing;
                            }
                        } catch (\Exception $e) {
                            Log::warning('Failed to transform ImagineYourHome property', [
                                'property' => $property,
                                'error' => $e->getMessage()
                            ]);
                            continue;
                        }
                    }
                } else {
                    Log::warning('No listings found in ImagineYourHome JSON structure', [
                        'available_keys' => array_keys($jsonData['props']['pageProps'] ?? [])
                    ]);
                }
            } else {
                // Fallback to traditional HTML scraping if JSON not found
                Log::info("No __NEXT_DATA__ found, falling back to HTML scraping");
                return $this->scrapeImagineYourHomeHtml($html, $url);
            }

        } catch (\Exception $e) {
            throw new \Exception("Failed to scrape ImagineYourHome: " . $e->getMessage());
        }

        Log::info("Successfully scraped " . count($listings) . " listings from ImagineYourHome");
        return $listings;
    }

    /**
     * Transform ImagineYourHome property JSON to our format
     */
    private function transformImagineYourHomeProperty(array $property): ?array
    {
        // Skip if no basic info
        if (empty($property['Address']) && empty($property['MlsId'])) {
            return null;
        }

        $listing = [
            'title' => $property['Address'] ?? 'Property Listing',
            'price' => $property['ListPrice'] ?? null,
            'address' => $property['Address'] ?? '',
            'city' => $property['City'] ?? '',
            'county' => $property['CountyOrParish'] ?? '',
            'state' => 'KY', // All listings appear to be in Kentucky
            'latitude' => $property['Latitude'] ?? null,
            'longitude' => $property['Longitude'] ?? null,
            'bedrooms' => $property['BedroomsTotal'] ?? null,
            'bathrooms' => $property['BathroomsFull'] ?? null,
            'sqft' => $property['LivingArea'] ?? null,
            'acres' => $property['LotSizeAcres'] ?? null,
            'property_type' => $this->mapImagineYourHomePropertyType($property['HomeType'] ?? 'Residential'),
            'mls_number' => $property['MlsId'] ?? null,
            'external_id' => $property['MlsId'] ?? null,
            'external_url' => $this->buildPropertyUrl($property),
            'image_urls' => $property['FirstMedia'] ?? '',
            'status' => $this->mapImagineYourHomeStatus($property['MlsStatus'] ?? 'Active'),
            'listing_date' => $property['OnMarketDate'] ?? null,
            'original_price' => $property['OriginalListPrice'] ?? null,
            'school_district' => $this->extractSchoolDistrict($property),
            'source' => 'imagineyourhome',
            'scraped_at' => now()->toISOString(),
            'raw_data' => json_encode($property) // Store original data for reference
        ];

        return array_filter($listing, function($value) {
            return $value !== null && $value !== '';
        });
    }

    /**
     * Map ImagineYourHome property types to our types
     */
    private function mapImagineYourHomePropertyType(string $homeType): string
    {
        $typeMap = [
            'Single Family Residence' => 'residential',
            'Residential' => 'residential',
            'Farm' => 'farms',
            'Agriculture' => 'farms',
            'Unimproved Land' => 'hunting',
            'Land' => 'hunting',
            'Commercial' => 'commercial',
            'Mobile Home' => 'residential',
            'Condo' => 'residential',
            'Townhouse' => 'residential',
        ];

        return $typeMap[$homeType] ?? 'residential';
    }

    /**
     * Map ImagineYourHome status to our status
     */
    private function mapImagineYourHomeStatus(string $mlsStatus): string
    {
        $statusMap = [
            'Active' => 'active',
            'Pending' => 'pending',
            'Contingent' => 'pending',
            'Closed' => 'sold',
            'Cancelled' => 'inactive',
            'Expired' => 'inactive',
        ];

        return $statusMap[$mlsStatus] ?? 'active';
    }

    /**
     * Build property URL for ImagineYourHome
     */
    private function buildPropertyUrl(array $property): string
    {
        $mlsId = $property['MlsId'] ?? '';
        $address = $property['Address'] ?? '';
        
        if ($mlsId && $address) {
            // Clean address for URL
            $cleanAddress = preg_replace('/[^a-zA-Z0-9\s]/', '', $address);
            $cleanAddress = str_replace(' ', '-', trim($cleanAddress));
            
            return "https://www.imagineyourhome.com/property/{$cleanAddress}/{$mlsId}";
        }
        
        return '';
    }

    /**
     * Extract school district info
     */
    private function extractSchoolDistrict(array $property): string
    {
        $schools = [];
        
        if (!empty($property['ElementarySchool'])) {
            $schools[] = $property['ElementarySchool'];
        }
        
        if (!empty($property['HighSchool'])) {
            $schools[] = $property['HighSchool'];
        }
        
        return implode(', ', array_unique($schools));
    }

    /**
     * Fallback HTML scraping for ImagineYourHome (if JSON parsing fails)
     */
    private function scrapeImagineYourHomeHtml(string $html, string $url): array
    {
        $listings = [];
        $crawler = new Crawler($html);
        
        // Look for property listings in HTML (fallback)
        $propertyElements = $crawler->filter('.property-card, .listing-item, .property-listing, [data-property]');
        
        if ($propertyElements->count() === 0) {
            $propertyElements = $crawler->filter('.card, .item, .result, article');
        }

        foreach ($propertyElements as $element) {
            $property = new Crawler($element);
            
            try {
                $listing = [
                    'title' => $this->extractText($property, 'h3, h4, .title, .property-title, .listing-title'),
                    'price' => $this->extractPrice($property, '.price, .cost, .amount'),
                    'address' => $this->extractText($property, '.address, .location, .property-address'),
                    'description' => $this->extractText($property, '.description, .summary, p'),
                    'bedrooms' => $this->extractNumber($property, '.bed, .bedroom, .br'),
                    'bathrooms' => $this->extractNumber($property, '.bath, .bathroom, .ba'),
                    'sqft' => $this->extractNumber($property, '.sqft, .square-feet, .area'),
                    'acres' => $this->extractAcres($property, '.acres, .lot-size, .land'),
                    'property_type' => $this->extractPropertyType($property),
                    'image_urls' => $this->extractImages($property),
                    'listing_url' => $this->extractLink($property, $url),
                    'external_id' => $this->extractExternalId($property, $url),
                    'source' => 'imagineyourhome',
                    'scraped_at' => now()->toISOString()
                ];

                if (!empty($listing['title']) || !empty($listing['address'])) {
                    $listings[] = array_filter($listing);
                    $this->politeSleep();
                }

            } catch (\Exception $e) {
                Log::warning('Failed to parse HTML property element', ['error' => $e->getMessage()]);
                continue;
            }
        }

        return $listings;
    }

    /**
     * Scrape Zillow listings (basic implementation)
     */
    private function scrapeZillow(string $url, array $options): array
    {
        // Note: Zillow has sophisticated anti-bot protection
        // This is a basic implementation - consider using their API instead
        
        $listings = [];
        
        try {
            $html = $this->fetchPage($url, ['delay' => 3]); // Longer delay for Zillow
            $crawler = new Crawler($html);
            
            // Zillow uses dynamic loading, so this might not capture all listings
            $propertyElements = $crawler->filter('[data-test="property-card"], .list-card');
            
            foreach ($propertyElements as $element) {
                $property = new Crawler($element);
                
                $listing = [
                    'title' => $this->extractText($property, '.list-card-addr, [data-test="property-card-addr"]'),
                    'price' => $this->extractPrice($property, '.list-card-price, [data-test="property-card-price"]'),
                    'address' => $this->extractText($property, '.list-card-addr, [data-test="property-card-addr"]'),
                    'bedrooms' => $this->extractNumber($property, '.list-card-details li:first-child'),
                    'bathrooms' => $this->extractNumber($property, '.list-card-details li:nth-child(2)'),
                    'sqft' => $this->extractNumber($property, '.list-card-details li:last-child'),
                    'image_urls' => $this->extractImages($property),
                    'listing_url' => $this->extractLink($property, $url),
                    'source' => 'zillow',
                    'scraped_at' => now()->toISOString()
                ];

                if (!empty($listing['title']) || !empty($listing['address'])) {
                    $listings[] = array_filter($listing);
                    $this->politeSleep();
                }
            }

        } catch (\Exception $e) {
            throw new \Exception("Failed to scrape Zillow: " . $e->getMessage());
        }

        return $listings;
    }

    /**
     * Generic scraping for unknown sites
     */
    private function scrapeGeneric(string $url, array $options): array
    {
        $listings = [];
        
        try {
            $html = $this->fetchPage($url);
            $crawler = new Crawler($html);
            
            // Try common property listing patterns
            $selectors = [
                '.property, .listing, .card, .item',
                '[class*="property"], [class*="listing"]',
                'article, .result',
            ];

            foreach ($selectors as $selector) {
                $elements = $crawler->filter($selector);
                if ($elements->count() > 0) {
                    
                    foreach ($elements as $element) {
                        $property = new Crawler($element);
                        
                        $listing = [
                            'title' => $this->extractText($property, 'h1, h2, h3, h4, .title'),
                            'price' => $this->extractPrice($property, '*'),
                            'address' => $this->extractText($property, '.address, .location'),
                            'description' => $this->extractText($property, '.description, p'),
                            'image_urls' => $this->extractImages($property),
                            'listing_url' => $this->extractLink($property, $url),
                            'source' => 'generic',
                            'scraped_at' => now()->toISOString()
                        ];

                        if (!empty($listing['title'])) {
                            $listings[] = array_filter($listing);
                            $this->politeSleep();
                        }
                    }
                    
                    if (!empty($listings)) {
                        break; // Found listings with this selector
                    }
                }
            }

        } catch (\Exception $e) {
            throw new \Exception("Failed to scrape generic site: " . $e->getMessage());
        }

        return $listings;
    }

    /**
     * Helper methods for data extraction
     */
    private function extractText(Crawler $crawler, string $selector): string
    {
        try {
            $element = $crawler->filter($selector)->first();
            return $element->count() > 0 ? trim($element->text()) : '';
        } catch (\Exception $e) {
            return '';
        }
    }

    private function extractPrice(Crawler $crawler, string $selector): string
    {
        $text = $this->extractText($crawler, $selector);
        if (empty($text)) {
            // Search for price patterns in any text
            $allText = $crawler->text();
            preg_match('/\$[\d,]+(?:\.\d{2})?/', $allText, $matches);
            return $matches[0] ?? '';
        }
        return $text;
    }

    private function extractNumber(Crawler $crawler, string $selector): ?int
    {
        $text = $this->extractText($crawler, $selector);
        preg_match('/\d+/', $text, $matches);
        return isset($matches[0]) ? (int) $matches[0] : null;
    }

    private function extractAcres(Crawler $crawler, string $selector): ?float
    {
        $text = $this->extractText($crawler, $selector);
        preg_match('/[\d.]+\s*(?:acres?|ac\.?)/i', $text, $matches);
        if (isset($matches[0])) {
            preg_match('/[\d.]+/', $matches[0], $numberMatch);
            return isset($numberMatch[0]) ? (float) $numberMatch[0] : null;
        }
        return null;
    }

    private function extractPropertyType(Crawler $crawler): string
    {
        $text = strtolower($crawler->text());
        
        if (str_contains($text, 'farm')) return 'farms';
        if (str_contains($text, 'hunt') || str_contains($text, 'land') || str_contains($text, 'acre')) return 'hunting';
        if (str_contains($text, 'ranch')) return 'ranches';
        if (str_contains($text, 'water') || str_contains($text, 'lake') || str_contains($text, 'river')) return 'waterfront';
        if (str_contains($text, 'commercial')) return 'commercial';
        
        return 'residential';
    }

    private function extractImages(Crawler $crawler): string
    {
        $images = [];
        
        $crawler->filter('img')->each(function (Crawler $img) use (&$images) {
            $src = $img->attr('src') ?: $img->attr('data-src');
            if ($src && filter_var($src, FILTER_VALIDATE_URL)) {
                $images[] = $src;
            }
        });
        
        return implode(',', array_unique($images));
    }

    private function extractLink(Crawler $crawler, string $baseUrl): string
    {
        $link = $crawler->filter('a')->first();
        if ($link->count() > 0) {
            $href = $link->attr('href');
            if ($href) {
                return $this->resolveUrl($href, $baseUrl);
            }
        }
        return '';
    }

    private function extractExternalId(Crawler $crawler, string $baseUrl): string
    {
        // Try to extract ID from data attributes or URL
        $id = $crawler->attr('data-id') ?: $crawler->attr('data-property-id');
        if ($id) return $id;
        
        $link = $this->extractLink($crawler, $baseUrl);
        if ($link) {
            preg_match('/(?:id=|\/|-)(\d+)(?:\/|$)/', $link, $matches);
            return $matches[1] ?? '';
        }
        
        return '';
    }

    /**
     * Utility methods
     */
    private function fetchPage(string $url, array $options = []): string
    {
        $delay = $options['delay'] ?? $this->delayBetweenRequests;
        
        // Be polite - add delay between requests
        if ($delay > 0) {
            sleep($delay);
        }
        
        $userAgent = $this->userAgents[array_rand($this->userAgents)];
        
        try {
            $response = $this->client->get($url, [
                'headers' => [
                    'User-Agent' => $userAgent,
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
                    'Accept-Language' => 'en-US,en;q=0.9',
                    'Accept-Encoding' => 'gzip, deflate, br',
                    'Connection' => 'keep-alive',
                    'Upgrade-Insecure-Requests' => '1',
                    'Sec-Fetch-Dest' => 'document',
                    'Sec-Fetch-Mode' => 'navigate',
                    'Sec-Fetch-Site' => 'none',
                    'Cache-Control' => 'max-age=0',
                    'DNT' => '1',
                    'Referer' => parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST),
                ],
                'timeout' => 30,
                'verify' => false, // For SSL issues in development
            ]);
            
            return $response->getBody()->getContents();
            
        } catch (RequestException $e) {
            throw new \Exception("Failed to fetch page: " . $e->getMessage());
        }
    }

    private function detectSiteType(string $url): string
    {
        $host = parse_url($url, PHP_URL_HOST);
        
        return match(true) {
            str_contains($host, 'imagineyourhome.com') => 'imagineyourhome',
            str_contains($host, 'zillow.com') => 'zillow',
            str_contains($host, 'realtor.com') => 'realtor',
            default => 'generic'
        };
    }

    private function isScrapingAllowed(string $url): bool
    {
        try {
            $robotsUrl = parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST) . '/robots.txt';
            $response = $this->client->get($robotsUrl, ['timeout' => 5]);
            $robotsTxt = $response->getBody()->getContents();
            
            // Get the path from the URL we want to scrape
            $urlPath = parse_url($url, PHP_URL_PATH) ?: '/';
            
            // Parse robots.txt more carefully
            $lines = array_map('trim', explode("\n", strtolower($robotsTxt)));
            $isUserAgentAll = false;
            
            foreach ($lines as $line) {
                // Skip comments and empty lines
                if (empty($line) || str_starts_with($line, '#')) {
                    continue;
                }
                
                // Check for User-agent: *
                if (str_starts_with($line, 'user-agent:')) {
                    $userAgent = trim(substr($line, 11));
                    $isUserAgentAll = ($userAgent === '*');
                    continue;
                }
                
                // Only process disallow rules for User-agent: *
                if ($isUserAgentAll && str_starts_with($line, 'disallow:')) {
                    $disallowedPath = trim(substr($line, 9));
                    
                    // Check if this disallow rule applies to our URL path
                    if ($this->pathMatches($urlPath, $disallowedPath)) {
                        Log::info("Scraping blocked by robots.txt", [
                            'url' => $url,
                            'path' => $urlPath,
                            'disallowed_pattern' => $disallowedPath
                        ]);
                        return false;
                    }
                }
            }
            
        } catch (\Exception $e) {
            // If we can't fetch robots.txt, proceed with caution but allow
            Log::info("Could not fetch robots.txt for {$url}", ['error' => $e->getMessage()]);
        }
        
        return true; // Default to allowing if no specific blocks found
    }

    /**
     * Check if a URL path matches a robots.txt disallow pattern
     */
    private function pathMatches(string $urlPath, string $disallowPattern): bool
    {
        // Empty disallow means allow everything
        if (empty($disallowPattern)) {
            return false;
        }
        
        // Exact root disallow
        if ($disallowPattern === '/') {
            return true; // This would block everything
        }
        
        // Pattern matching
        if (str_ends_with($disallowPattern, '*')) {
            $prefix = rtrim($disallowPattern, '*');
            return str_starts_with($urlPath, $prefix);
        }
        
        // Exact path match
        return $urlPath === $disallowPattern;
    }

    private function resolveUrl(string $url, string $baseUrl): string
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }
        
        $base = parse_url($baseUrl);
        $baseHost = $base['scheme'] . '://' . $base['host'];
        
        if (str_starts_with($url, '/')) {
            return $baseHost . $url;
        }
        
        return $baseUrl . '/' . ltrim($url, '/');
    }

    private function politeSleep(): void
    {
        // Add random delay to appear more human-like
        $delay = rand($this->delayBetweenRequests * 1000, ($this->delayBetweenRequests + 1) * 1000) / 1000;
        usleep($delay * 1000000);
    }

    private function saveListingsToCSV(array $listings, string $source): string
    {
        $filename = "scraped_listings_{$source}_" . date('Y-m-d_H-i-s') . '.csv';
        $path = "imports/{$filename}";
        
        if (empty($listings)) {
            return '';
        }
        
        // Get all possible headers from all listings
        $headers = [];
        foreach ($listings as $listing) {
            $headers = array_unique(array_merge($headers, array_keys($listing)));
        }
        
        $csv = implode(',', $headers) . "\n";
        
        foreach ($listings as $listing) {
            $row = [];
            foreach ($headers as $header) {
                $value = $listing[$header] ?? '';
                $row[] = '"' . str_replace('"', '""', $value) . '"';
            }
            $csv .= implode(',', $row) . "\n";
        }
        
        Storage::disk('local')->put($path, $csv);
        
        return Storage::disk('local')->path($path);
    }

    private function resetResults(): void
    {
        $this->results = [
            'processed' => 0,
            'imported' => 0,
            'failed' => 0,
            'errors' => []
        ];
    }
}
