<?php

namespace App\Services;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use League\Csv\Reader;
use Intervention\Image\Laravel\Facades\Image;

class PropertyImportService
{
    private array $results = [
        'processed' => 0,
        'imported' => 0,
        'updated' => 0,
        'skipped' => 0,
        'failed' => 0,
        'images_downloaded' => 0,
        'errors' => []
    ];

    /**
     * Import properties from a generic CSV file
     */
    public function importFromCsv(string $filePath, array $options = []): array
    {
        $this->resetResults();
        
        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0);
        
        $records = iterator_to_array($csv);
        $total = count($records);
        $current = 0;
        
        foreach (array_chunk($records, $options['chunk_size'] ?? 100) as $chunk) {
            foreach ($chunk as $record) {
                $current++;
                
                if (isset($options['progress_callback'])) {
                    $options['progress_callback']($current, $total, $record);
                }
                
                $this->processRecord($record, $options, 'csv');
            }
        }
        
        return $this->results;
    }

    /**
     * Import properties from a Zillow-formatted CSV export
     */
    public function importFromZillowCsv(string $filePath, array $options = []): array
    {
        $this->resetResults();
        
        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0);
        
        $records = iterator_to_array($csv);
        $total = count($records);
        $current = 0;
        
        foreach (array_chunk($records, $options['chunk_size'] ?? 100) as $chunk) {
            foreach ($chunk as $record) {
                $current++;
                
                if (isset($options['progress_callback'])) {
                    $options['progress_callback']($current, $total, $record);
                }
                
                // Transform Zillow format to our format
                $transformedRecord = $this->transformZillowRecord($record);
                $this->processRecord($transformedRecord, $options, 'zillow');
            }
        }
        
        return $this->results;
    }

    /**
     * Import from MLS feeds (placeholder - requires MLS access)
     */
    public function importFromMls(array $options = []): array
    {
        $this->resetResults();
        
        // This would integrate with MLS RETS feeds
        // For now, return a placeholder result
        $this->results['errors'][] = 'MLS integration requires RETS credentials and setup';
        
        return $this->results;
    }

    /**
     * Import from API sources
     */
    public function importFromApi(array $options = []): array
    {
        $this->resetResults();
        
        // This would integrate with various real estate APIs
        // For now, return a placeholder result
        $this->results['errors'][] = 'API integration requires specific API credentials and endpoints';
        
        return $this->results;
    }

    /**
     * Process a single record
     */
    private function processRecord(array $record, array $options, string $source): void
    {
        $this->results['processed']++;
        
        try {
            // Validate required fields
            if (empty($record['title']) && empty($record['address'])) {
                $this->results['failed']++;
                $this->results['errors'][] = "Missing required fields: title or address";
                return;
            }
            
            // Check for duplicates
            $existingProperty = $this->findExistingProperty($record, $source);
            
            if ($existingProperty && !$options['update_existing']) {
                $this->results['skipped']++;
                return;
            }
            
            // Transform record to our property format
            $propertyData = $this->transformToPropertyData($record, $source);
            
            if ($options['dry_run']) {
                $this->results['imported']++;
                return;
            }
            
            // Create or update property
            if ($existingProperty) {
                $existingProperty->update($propertyData);
                $property = $existingProperty;
                $this->results['updated']++;
            } else {
                $property = Property::create($propertyData);
                $this->results['imported']++;
            }
            
            // Handle images
            if ($options['download_images'] && !empty($record['image_urls'])) {
                $this->downloadPropertyImages($property, $record['image_urls']);
            }
            
        } catch (\Exception $e) {
            $this->results['failed']++;
            $this->results['errors'][] = "Failed to process record: " . $e->getMessage();
            Log::error('Property import error', [
                'record' => $record,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Transform Zillow record format to our standard format
     */
    private function transformZillowRecord(array $record): array
    {
        return [
            'title' => $record['Address'] ?? ($record['street_address'] ?? 'Imported Property'),
            'description' => $record['Description'] ?? $record['description'] ?? '',
            'price' => $this->parsePrice($record['Price'] ?? $record['price'] ?? 0),
            'total_acres' => $this->parseAcres($record['Lot Size'] ?? $record['lot_size'] ?? $record['acres'] ?? 0),
            'street_address' => $record['Address'] ?? $record['street_address'] ?? '',
            'city' => $record['City'] ?? $record['city'] ?? '',
            'state' => $record['State'] ?? $record['state'] ?? '',
            'zip_code' => $record['Zip'] ?? $record['zip_code'] ?? '',
            'property_type' => $this->mapPropertyType($record['Property Type'] ?? $record['type'] ?? 'residential'),
            'bedrooms' => $record['Bedrooms'] ?? $record['bedrooms'] ?? null,
            'bathrooms' => $record['Bathrooms'] ?? $record['bathrooms'] ?? null,
            'sqft' => $record['Square Feet'] ?? $record['sqft'] ?? null,
            'year_built' => $record['Year Built'] ?? $record['year_built'] ?? null,
            'external_id' => $record['Zillow ID'] ?? $record['id'] ?? null,
            'external_url' => $record['URL'] ?? $record['listing_url'] ?? null,
            'image_urls' => $this->parseImageUrls($record['Photo URLs'] ?? $record['images'] ?? ''),
            'listing_date' => $record['List Date'] ?? $record['listing_date'] ?? now(),
        ];
    }

    /**
     * Transform generic record to property data
     */
    private function transformToPropertyData(array $record, string $source): array
    {
        $data = [
            'title' => $record['title'] ?? 'Imported Property',
            'description' => $record['description'] ?? '',
            'price' => $this->parsePrice($record['price'] ?? 0),
            'total_acres' => $this->parseAcres($record['total_acres'] ?? $record['acres'] ?? 1),
            'street_address' => $record['street_address'] ?? '',
            'city' => $record['city'] ?? '',
            'county' => $record['county'] ?? $this->guessCounty($record['city'] ?? ''),
            'state' => $record['state'] ?? 'KY', // Default to Kentucky
            'zip_code' => $record['zip_code'] ?? '',
            'latitude' => $record['latitude'] ?? null,
            'longitude' => $record['longitude'] ?? null,
            'property_type' => $this->mapPropertyType($record['property_type'] ?? 'residential'),
            'status' => 'active',
            'listing_agent_id' => 1, // Default to first user, adjust as needed
            'listing_date' => now(),
            'published_at' => now(),
            'featured' => false,
        ];

        // Handle optional fields
        if (isset($record['bedrooms'])) $data['home_bedrooms'] = (int) $record['bedrooms'];
        if (isset($record['bathrooms'])) $data['home_bathrooms'] = (int) $record['bathrooms'];
        if (isset($record['sqft'])) $data['home_sq_ft'] = (int) $record['sqft'];
        if (isset($record['year_built'])) $data['home_year_built'] = (int) $record['year_built'];
        
        // Boolean fields
        $data['has_home'] = !empty($record['bedrooms']) || !empty($record['sqft']);
        $data['water_access'] = $this->parseBoolean($record['water_access'] ?? false);
        $data['hunting_rights'] = $this->parseBoolean($record['hunting_rights'] ?? false);
        
        // External reference
        if (isset($record['external_id'])) {
            $data['mls_number'] = $record['external_id'];
        }

        return $data;
    }

    /**
     * Find existing property by external ID or address
     */
    private function findExistingProperty(array $record, string $source): ?Property
    {
        // Try to find by external ID first
        if (!empty($record['external_id'])) {
            $property = Property::where('mls_number', $record['external_id'])->first();
            if ($property) return $property;
        }

        // Try to find by address
        if (!empty($record['street_address']) && !empty($record['city'])) {
            return Property::where('street_address', 'like', '%' . $record['street_address'] . '%')
                          ->where('city', 'like', '%' . $record['city'] . '%')
                          ->first();
        }

        return null;
    }

    /**
     * Download and process property images
     */
    private function downloadPropertyImages(Property $property, string $imageUrls): void
    {
        $urls = $this->parseImageUrls($imageUrls);
        
        foreach ($urls as $index => $url) {
            try {
                $response = Http::timeout(30)->get($url);
                
                if ($response->successful()) {
                    $filename = 'imported_' . time() . '_' . ($index + 1) . '.jpg';
                    $path = "properties/{$property->id}/images/exterior/{$filename}";
                    
                    Storage::disk('public')->put($path, $response->body());
                    
                    // Create image record
                    PropertyImage::create([
                        'property_id' => $property->id,
                        'filename' => $filename,
                        'path' => $path,
                        'title' => "Imported Image " . ($index + 1),
                        'alt_text' => "Property image for {$property->title}",
                        'file_size' => strlen($response->body()),
                        'mime_type' => 'image/jpeg',
                        'sort_order' => $index + 1,
                        'is_primary' => $index === 0,
                        'category' => 'exterior',
                    ]);
                    
                    $this->results['images_downloaded']++;
                }
            } catch (\Exception $e) {
                $this->results['errors'][] = "Failed to download image {$url}: " . $e->getMessage();
            }
        }
    }

    /**
     * Helper methods for data transformation
     */
    private function parsePrice($price): float
    {
        return (float) preg_replace('/[^0-9.]/', '', $price);
    }

    private function parseAcres($acres): float
    {
        $parsed = (float) preg_replace('/[^0-9.]/', '', $acres);
        return max($parsed, 0.1); // Minimum 0.1 acres
    }

    private function parseBoolean($value): bool
    {
        if (is_bool($value)) return $value;
        if (is_string($value)) {
            return in_array(strtolower($value), ['yes', 'true', '1', 'on']);
        }
        return (bool) $value;
    }

    private function parseImageUrls(string $urls): array
    {
        if (empty($urls)) return [];
        
        // Split by common delimiters
        $urlArray = preg_split('/[,;\n\r]+/', $urls);
        
        return array_filter(array_map('trim', $urlArray), function($url) {
            return filter_var($url, FILTER_VALIDATE_URL) !== false;
        });
    }

    private function mapPropertyType(string $type): string
    {
        $typeMap = [
            'single family' => 'residential',
            'house' => 'residential',
            'condo' => 'residential',
            'townhouse' => 'residential',
            'land' => 'hunting',
            'farm' => 'farms',
            'ranch' => 'ranches',
            'commercial' => 'commercial',
            'vacant land' => 'hunting',
            'acreage' => 'hunting',
            'waterfront' => 'waterfront',
        ];

        $normalized = strtolower(trim($type));
        return $typeMap[$normalized] ?? 'residential';
    }

    private function guessCounty(string $city): string
    {
        // Basic county mapping for Kentucky - extend as needed
        $countyMap = [
            'louisville' => 'Jefferson',
            'lexington' => 'Fayette',
            'bowling green' => 'Warren',
            'owensboro' => 'Daviess',
            'covington' => 'Kenton',
            'hopkinsville' => 'Christian',
            'richmond' => 'Madison',
            'florence' => 'Boone',
            'elizabethtown' => 'Hardin',
            'henderson' => 'Henderson',
        ];

        $normalized = strtolower(trim($city));
        return $countyMap[$normalized] ?? 'Carter'; // Default to Carter County
    }

    private function resetResults(): void
    {
        $this->results = [
            'processed' => 0,
            'imported' => 0,
            'updated' => 0,
            'skipped' => 0,
            'failed' => 0,
            'images_downloaded' => 0,
            'errors' => []
        ];
    }
}
