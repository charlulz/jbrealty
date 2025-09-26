<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ImportJeremiahBrownGeographic extends Command
{
    protected $signature = 'properties:import-jeremiah-geographic 
                            {--dry-run : Show what would be imported without saving}
                            {--clear-existing : Clear existing Jeremiah Brown properties first}';

    protected $description = 'Import Jeremiah Brown listings using proven geographic search method';

    public function handle()
    {
        $this->info('🌍 Geographic Import for Jeremiah Brown');
        $this->info('=====================================');

        $dryRun = $this->option('dry-run');
        $clearExisting = $this->option('clear-existing');

        if ($dryRun) {
            $this->warn('🔍 DRY RUN MODE - No data will be saved');
        }

        if ($clearExisting) {
            $this->warn('🗑️ Clearing existing Jeremiah Brown properties...');
            if (!$dryRun) {
                Property::where('api_source', 'flexmls')
                        ->where(function ($query) {
                            $query->whereJsonContains('api_data->StandardFields->ListAgentMlsId', '20271')
                                  ->orWhereJsonContains('api_data->StandardFields->ListAgentMlsId', '429520271')
                                  ->orWhereJsonContains('api_data->StandardFields->ListAgentName', 'Jeremiah Brown')
                                  ->orWhereJsonContains('api_data->StandardFields->ListOfficeName', 'JB Land & Home Realty');
                        })
                        ->delete();
                $this->info('✅ Existing properties cleared');
            }
        }
        $this->newLine();

        // Search using the successful geographic approach
        $allListings = $this->searchGeographicAreas();

        $this->newLine();
        $this->info('📊 SEARCH RESULTS:');
        $this->line('  • Total Jeremiah Brown listings found: ' . count($allListings));

        if (empty($allListings)) {
            $this->warn('No listings found with geographic search.');
            return Command::FAILURE;
        }

        // Display found listings
        $this->newLine();
        $this->info('📋 Found Listings:');
        foreach ($allListings as $index => $listing) {
            $standardFields = $listing['StandardFields'] ?? [];
            $listingId = $standardFields['ListingId'] ?? 'N/A';
            $address = $standardFields['UnparsedAddress'] ?? 'N/A';
            $price = $standardFields['ListPrice'] ?? 0;
            $status = $standardFields['MlsStatus'] ?? 'Unknown';
            $agentName = $standardFields['ListAgentName'] ?? 'Unknown';

            $this->line(sprintf(
                "  %d. MLS #%s: %s - $%s (%s) - %s",
                $index + 1,
                $listingId,
                $address,
                number_format($price),
                $status,
                $agentName
            ));
        }

        // Import process
        if (!$dryRun) {
            $this->newLine();
            $this->info('🔄 Starting import...');
            $results = $this->importListings($allListings);

            $this->info('✅ Import completed!');
            $this->line('  • Created: ' . $results['created']);
            $this->line('  • Updated: ' . $results['updated']);
            $this->line('  • Errors: ' . $results['errors']);
        } else {
            $this->newLine();
            $this->info('🔍 DRY RUN - No data was imported');
        }

        return Command::SUCCESS;
    }

    /**
     * Import photos for all Jeremiah Brown properties
     */
    private function importPhotosForProperties(bool $updateExisting): void
    {
        $properties = Property::where('api_source', 'flexmls')->get();
        $totalPhotos = 0;
        
        $this->info("Found {$properties->count()} properties for photo import");
        
        $progressBar = $this->output->createProgressBar($properties->count());
        $progressBar->setFormat('verbose');
        $progressBar->start();
        
        foreach ($properties as $property) {
            try {
                $photosImported = $this->flexMlsService->importPropertyPhotos($property, $updateExisting);
                $totalPhotos += $photosImported;
                
                if ($photosImported > 0) {
                    $this->line("\n   ✅ {$property->title}: {$photosImported} photos imported");
                }
                
                $progressBar->advance();
                
                // Small delay to be respectful to the API
                usleep(500000); // 0.5 second delay
                
            } catch (\Exception $e) {
                $this->error("\n   ❌ Failed to import photos for {$property->title}: " . $e->getMessage());
                $progressBar->advance();
            }
        }
        
        $progressBar->finish();
        $this->info("\n\n📸 Photo import completed: {$totalPhotos} total photos imported");
    }

    private function searchGeographicAreas(): array
    {
        $baseUrl = config('services.flexmls.base_url');
        $accessToken = config('services.flexmls.access_token');

        $allJeremiahListings = [];

        // Jeremiah's FULL market areas (discovered via expanded search)
        $searchAreas = [
            // Primary cities
            'Morehead' => ['type' => 'city', 'filter' => "City Eq 'Morehead'"],
            'Ewing' => ['type' => 'city', 'filter' => "City Eq 'Ewing'"],
            'Grayson' => ['type' => 'city', 'filter' => "City Eq 'Grayson'"],
            'Olive Hill' => ['type' => 'city', 'filter' => "City Eq 'Olive Hill'"],
            'Vanceburg' => ['type' => 'city', 'filter' => "City Eq 'Vanceburg'"],
            'Maysville' => ['type' => 'city', 'filter' => "City Eq 'Maysville'"],
            
            // NEWLY DISCOVERED MAJOR MARKETS
            'Carlisle' => ['type' => 'city', 'filter' => "City Eq 'Carlisle'"], // 34 listings found!
            'Jackson' => ['type' => 'city', 'filter' => "City Eq 'Jackson'"], // 1 listing found
            
            // ADDITIONAL CITIES FROM USER INPUT
            'Bowling Green' => ['type' => 'city', 'filter' => "City Eq 'Bowling Green'"], // 3 listings found
            'Hitchins' => ['type' => 'city', 'filter' => "City Eq 'Hitchins'"], // 10 listings found - MAJOR MARKET!
            'Williamsburg' => ['type' => 'city', 'filter' => "City Eq 'Williamsburg'"], // 2 listings found  
            'Corinth' => ['type' => 'city', 'filter' => "City Eq 'Corinth'"], // 1 listing found
            'Wallingford' => ['type' => 'city', 'filter' => "City Eq 'Wallingford'"], // 1 listing found
            'Louisa' => ['type' => 'city', 'filter' => "City Eq 'Louisa'"], // NEW MARKET
            'Mt Sterling' => ['type' => 'city', 'filter' => "City Eq 'Mt Sterling'"], // NEW MARKET
            
            // Counties (using simplified filter that works)
            'Fleming County' => ['type' => 'county', 'filter' => "CountyOrParish Eq 'Fleming County'"],
            'Lewis County' => ['type' => 'county', 'filter' => "CountyOrParish Eq 'Lewis County'"],
        ];

        foreach ($searchAreas as $areaName => $areaData) {
            $this->info("🏙️ Searching {$areaName} ({$areaData['type']})...");

            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => 'application/json',
                ])->timeout(30)->get($baseUrl . '/v1/listings', [
                    '_filter' => $areaData['filter'],
                    '_limit' => 500,
                    '_expand' => 'PrimaryPhoto'
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $listings = $data['D']['Results'] ?? [];

                    $this->line("   📊 Retrieved " . count($listings) . " listings from {$areaName}");

                    // Filter for Jeremiah Brown
                    $jeremiahCount = 0;
                    foreach ($listings as $listing) {
                        if ($this->isJeremiahBrownListing($listing)) {
                            $allJeremiahListings[] = $listing;
                            $jeremiahCount++;

                            $standardFields = $listing['StandardFields'] ?? [];
                            $listingId = $standardFields['ListingId'] ?? 'N/A';
                            $address = $standardFields['UnparsedAddress'] ?? 'N/A';
                            $this->line("   🎉 Found: MLS #{$listingId} - {$address}");
                        }
                    }

                    if ($jeremiahCount === 0) {
                        $this->line("   ⚪ No Jeremiah Brown listings in {$areaName}");
                    }

                } else {
                    $this->error("   ❌ Failed to search {$areaName}: HTTP " . $response->status());
                    
                    // Try simplified filter for counties if the complex one fails
                    if ($areaData['type'] === 'county' && strpos($areaData['filter'], 'County') !== false) {
                        $this->line("   🔄 Trying alternative county filter...");
                        
                        $altFilter = str_replace('CountyOrParish Eq', 'CountyOrParish Ct', $areaData['filter']);
                        $altFilter = str_replace(' County', '', $altFilter);
                        
                        $response2 = Http::withHeaders([
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Accept' => 'application/json',
                        ])->timeout(30)->get($baseUrl . '/v1/listings', [
                            '_filter' => $altFilter,
                            '_limit' => 500,
                            '_expand' => 'PrimaryPhoto'
                        ]);
                        
                        if ($response2->successful()) {
                            $data = $response2->json();
                            $listings = $data['D']['Results'] ?? [];
                            $this->line("   ✅ Alternative filter worked: " . count($listings) . " listings");
                            
                            foreach ($listings as $listing) {
                                if ($this->isJeremiahBrownListing($listing)) {
                                    $allJeremiahListings[] = $listing;
                                    $jeremiahCount++;

                                    $standardFields = $listing['StandardFields'] ?? [];
                                    $listingId = $standardFields['ListingId'] ?? 'N/A';
                                    $address = $standardFields['UnparsedAddress'] ?? 'N/A';
                                    $this->line("   🎉 Found: MLS #{$listingId} - {$address}");
                                }
                            }
                        }
                    }
                }

            } catch (\Exception $e) {
                $this->error("   ❌ Error searching {$displayName}: " . $e->getMessage());
            }

            usleep(300000); // 0.3 second delay
        }

        // Remove duplicates
        return $this->removeDuplicates($allJeremiahListings);
    }

    private function isJeremiahBrownListing(array $listing): bool
    {
        $standardFields = $listing['StandardFields'] ?? [];

        $agentMlsId = $standardFields['ListAgentMlsId'] ?? '';
        $agentLicense = $standardFields['ListAgentStateLicense'] ?? '';
        $agentName = $standardFields['ListAgentName'] ?? '';
        $firstName = $standardFields['ListAgentFirstName'] ?? '';
        $lastName = $standardFields['ListAgentLastName'] ?? '';
        $officeName = $standardFields['ListOfficeName'] ?? '';

        // Known identifiers
        $knownIds = ['20271', '429520271'];
        $knownLicense = '294658';

        return in_array($agentMlsId, $knownIds) ||
               $agentLicense === $knownLicense ||
               (stripos($agentName, 'jeremiah') !== false && stripos($agentName, 'brown') !== false) ||
               (stripos($firstName, 'jeremiah') !== false && stripos($lastName, 'brown') !== false) ||
               stripos($officeName, 'jb land') !== false;
    }

    private function removeDuplicates(array $listings): array
    {
        $unique = [];
        $seenIds = [];

        foreach ($listings as $listing) {
            $listingId = $listing['StandardFields']['ListingId'] ?? 'unknown';

            if (!in_array($listingId, $seenIds)) {
                $unique[] = $listing;
                $seenIds[] = $listingId;
            }
        }

        return $unique;
    }

    private function importListings(array $listings): array
    {
        $results = ['created' => 0, 'updated' => 0, 'errors' => 0];

        foreach ($listings as $listing) {
            try {
                $standardFields = $listing['StandardFields'] ?? [];
                $listingId = $standardFields['ListingId'] ?? 'unknown';
                $mlsNumber = $standardFields['MlsNumber'] ?? $listingId;

                // Check if property already exists
                $existingProperty = Property::where('mls_number', $mlsNumber)->first();

                // Transform listing data
                $propertyData = [
                    'mls_number' => $mlsNumber,
                    'title' => $this->generateTitle($standardFields),
                    'description' => $standardFields['PublicRemarks'] ?? '',
                    'price' => $standardFields['ListPrice'] ?? 0,
                    'acres' => $this->parseAcres($standardFields),
                    'total_acres' => $this->parseAcres($standardFields),
                    'address' => $standardFields['UnparsedAddress'] ?? '',
                    'city' => $standardFields['City'] ?? '',
                    'county' => $standardFields['CountyOrParish'] ?? '',
                    'state' => $standardFields['StateOrProvince'] ?? '',
                    'zip' => $standardFields['PostalCode'] ?? '',
                    'latitude' => $standardFields['Latitude'] ?? null,
                    'longitude' => $standardFields['Longitude'] ?? null,
                    'property_type' => $this->mapPropertyType($standardFields['PropertyType'] ?? ''),
                    'status' => $this->mapMlsStatus($standardFields['MlsStatus'] ?? ''),
                    'bedrooms' => $standardFields['BedsTotal'] ?? null,
                    'bathrooms' => $standardFields['BathroomsTotalInteger'] ?? null,
                    'square_feet' => $standardFields['LivingArea'] ?? null,
                    'year_built' => $standardFields['YearBuilt'] ?? null,
                    'listing_date' => $this->parseListingDate($standardFields),
                    'api_source' => 'flexmls',
                    'api_data' => json_encode($listing),
                    'published_at' => now(),
                ];

                if ($existingProperty) {
                    // Don't modify created_at for existing properties
                    $existingProperty->update($propertyData);
                    $results['updated']++;
                    $this->line("     ✅ Updated: {$mlsNumber}");
                } else {
                    // Set created_at to the original listing date for new properties
                    $originalListingDate = $this->parseOriginalListingDate($standardFields);
                    $propertyData['created_at'] = $originalListingDate;
                    
                    Property::create($propertyData);
                    $results['created']++;
                    $this->line("     ✅ Created: {$mlsNumber} (listed: {$originalListingDate->format('Y-m-d')})");
                }

            } catch (\Exception $e) {
                $results['errors']++;
                $this->error("     ❌ Error importing {$mlsNumber}: " . $e->getMessage());
                Log::error("Error importing listing {$mlsNumber}", [
                    'error' => $e->getMessage(),
                    'listing' => $listing
                ]);
            }
        }

        return $results;
    }

    private function generateTitle(array $standardFields): string
    {
        $address = $standardFields['UnparsedAddress'] ?? '';
        $acres = $this->parseAcres($standardFields);
        $type = $standardFields['PropertyType'] ?? '';

        if ($acres > 0) {
            return "{$acres} Acres - {$address}";
        } elseif ($type) {
            return "{$type} - {$address}";
        } else {
            return $address ?: 'Property Listing';
        }
    }

    private function parseAcres(array $standardFields): float
    {
        $acreFields = ['LotSizeAcres', 'TotalAcres', 'Acres', 'LotSizeArea'];

        foreach ($acreFields as $field) {
            if (isset($standardFields[$field]) && is_numeric($standardFields[$field])) {
                return (float) $standardFields[$field];
            }
        }

        return 0.0;
    }

    private function mapPropertyType(string $type): string
    {
        $typeMap = [
            'R' => 'residential',
            'C' => 'commercial',
            'L' => 'farms', // Land -> farms
            'F' => 'farms',
            'A' => 'farms', // Acreage -> farms
            'Single Family Residence' => 'residential',
            'Commercial' => 'commercial',
            'Land' => 'farms',
            'Farm' => 'farms',
        ];

        $mapped = $typeMap[$type] ?? strtolower($type);

        // Ensure valid enum value
        $validTypes = ['hunting', 'farms', 'ranches', 'residential', 'commercial', 'waterfront', 'timber', 'development', 'investment'];

        return in_array($mapped, $validTypes) ? $mapped : 'farms';
    }

    private function mapMlsStatus(string $status): string
    {
        $statusMap = [
            'Active' => 'active',
            'Pending' => 'pending',
            'Sold' => 'sold',
            'Contingent' => 'pending',
            'Under Contract' => 'pending',
            'Withdrawn' => 'off_market',
            'Expired' => 'off_market',
            'Closed' => 'sold', // Map Closed to sold
        ];

        $mapped = $statusMap[$status] ?? strtolower($status);

        // Ensure valid enum value
        $validStatuses = ['active', 'pending', 'sold', 'off_market', 'draft'];

        return in_array($mapped, $validStatuses) ? $mapped : 'active';
    }

    private function parseListingDate(array $standardFields): ?string
    {
        $dateFields = [
            'OnMarketDate',
            'ListingContractDate',
            'OriginalOnMarketTimestamp',
            'OnMarketTimestamp'
        ];

        foreach ($dateFields as $field) {
            if (!empty($standardFields[$field])) {
                $date = $standardFields[$field];

                if (is_string($date) && strtotime($date) !== false) {
                    return date('Y-m-d', strtotime($date));
                }
            }
        }

        return date('Y-m-d');
    }

    private function parseOriginalListingDate(array $standardFields): Carbon
    {
        // Priority order for original listing date
        $dateFields = [
            'OriginalOnMarketTimestamp',
            'OnMarketDate',
            'OnMarketTimestamp',
            'ListingContractDate',
            'OnMarketContractDate',
        ];

        foreach ($dateFields as $field) {
            if (!empty($standardFields[$field])) {
                $date = $standardFields[$field];

                if (is_string($date) && strtotime($date) !== false) {
                    return Carbon::parse($date);
                }
            }
        }

        // Fallback to current date if no listing date found
        return Carbon::now();
    }
}
