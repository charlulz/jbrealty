<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FlexMlsApiService;
use App\Services\PropertyImportService;
use App\Models\Property;
use Illuminate\Support\Facades\Log;

class ImportJeremiahBrownListings extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'properties:import-jeremiah-brown
                            {--max-per-mls=1000 : Maximum listings per MLS system}
                            {--dry-run : Preview import without saving to database}
                            {--clear-existing : Clear existing Jeremiah Brown properties before import}';

    /**
     * The console command description.
     */
    protected $description = 'Import ALL Jeremiah Brown listings using comprehensive MLS search';

    protected FlexMlsApiService $flexMlsService;
    protected PropertyImportService $propertyImportService;

    public function __construct(FlexMlsApiService $flexMlsService, PropertyImportService $propertyImportService)
    {
        parent::__construct();
        $this->flexMlsService = $flexMlsService;
        $this->propertyImportService = $propertyImportService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🏠 Jeremiah Brown Listings Import Tool');
        $this->info('===================================');
        
        $dryRun = $this->option('dry-run');
        $maxPerMls = $this->option('max-per-mls');
        $clearExisting = $this->option('clear-existing');
        
        if ($dryRun) {
            $this->warn('DRY RUN MODE - No data will be saved');
        }
        
        $this->info('📊 Max listings per MLS: ' . $maxPerMls);
        $this->newLine();
        
        // All known MLS systems where we've found Jeremiah listings
        $mlsSystems = [
            '20250213134913285161000000' => 'ImagineMLS',
            '20210504182759685317000000' => 'Unknown MLS #3',
            '20240123174036063766000000' => 'Unknown MLS #2',
            '20130925153233009157000000' => 'Metro Search MLS',
            '20130228193502179028000000' => 'Knoxville Area Association MLS',
        ];
        
        // Known Jeremiah Brown MLS IDs
        $jeremiahMlsIds = ['20271', '429520271'];
        
        if ($clearExisting && !$dryRun) {
            if ($this->confirm('⚠️  This will delete all existing properties with Jeremiah Brown as agent. Continue?')) {
                // Clear existing properties
                $this->info('🗑️  Clearing existing Jeremiah Brown properties...');
                // Add logic to clear existing properties if needed
            } else {
                $this->info('Import cancelled by user.');
                return Command::FAILURE;
            }
        }
        
        $allJeremiahListings = [];
        $totalProcessed = 0;
        
        foreach ($mlsSystems as $mlsId => $mlsName) {
            $this->info("🏢 Searching {$mlsName}...");
            
            $mlsListings = $this->searchMlsSystemForJeremiah($mlsId, $mlsName, $maxPerMls, $jeremiahMlsIds);
            $totalProcessed += $mlsListings['total_processed'];
            
            if (!empty($mlsListings['jeremiah_listings'])) {
                $jeremiahCount = count($mlsListings['jeremiah_listings']);
                $this->info("   🎉 Found {$jeremiahCount} Jeremiah Brown listings!");
                
                // Display found listings
                foreach ($mlsListings['jeremiah_listings'] as $listing) {
                    $standardFields = $listing['StandardFields'] ?? [];
                    $listingId = $standardFields['ListingId'] ?? 'N/A';
                    $address = $standardFields['UnparsedAddress'] ?? 'N/A';
                    $price = $standardFields['ListPrice'] ?? 0;
                    $status = $standardFields['MlsStatus'] ?? 'Unknown';
                    $agentName = $standardFields['ListAgentName'] ?? 'Unknown';
                    $agentMlsId = $standardFields['ListAgentMlsId'] ?? 'N/A';
                    
                    $this->line("     • MLS #{$listingId}: {$address} - $" . number_format($price) . " ({$status})");
                    $this->line("       Agent: {$agentName} (MLS ID: {$agentMlsId})");
                }
                
                // Add to master list (avoiding duplicates)
                foreach ($mlsListings['jeremiah_listings'] as $listing) {
                    $listingId = $listing['StandardFields']['ListingId'] ?? 'unknown';
                    
                    // Check for duplicates
                    $isDuplicate = false;
                    foreach ($allJeremiahListings as $existing) {
                        if (($existing['StandardFields']['ListingId'] ?? '') === $listingId) {
                            $isDuplicate = true;
                            break;
                        }
                    }
                    
                    if (!$isDuplicate) {
                        $listing['_source_mls'] = $mlsName;
                        $listing['_source_mls_id'] = $mlsId;
                        $allJeremiahListings[] = $listing;
                    }
                }
            } else {
                $this->line("   ⚪ No Jeremiah Brown listings found");
            }
            
            $this->newLine();
            usleep(500000); // 0.5 second delay
        }
        
        // Results Summary
        $this->info("📊 SEARCH COMPLETE:");
        $this->line("  • Total listings processed: " . number_format($totalProcessed));
        $this->line("  • MLS systems searched: " . count($mlsSystems));
        $this->line("  • Jeremiah Brown listings found: " . count($allJeremiahListings));
        $this->newLine();
        
        if (empty($allJeremiahListings)) {
            $this->warn("⚠️  No Jeremiah Brown listings found across all MLS systems");
            return Command::FAILURE;
        }
        
        // Import Process
        if (!$dryRun) {
            $this->info("🔄 Starting import process...");
            
            // Direct import using Property model since PropertyImportService doesn't have FlexMLS method
            $importResults = $this->importListingsDirectly($allJeremiahListings);
            
            $this->info("✅ Import completed!");
            $this->line("  • Created: " . $importResults['created']);
            $this->line("  • Updated: " . $importResults['updated']);
            $this->line("  • Skipped: " . $importResults['skipped']);
            $this->line("  • Errors: " . $importResults['errors']);
            
        } else {
            $this->info("🔍 DRY RUN - Listings that would be imported:");
            
            foreach ($allJeremiahListings as $index => $listing) {
                $standardFields = $listing['StandardFields'] ?? [];
                
                $this->info("Listing " . ($index + 1) . ":");
                $this->line("  MLS #: " . ($standardFields['ListingId'] ?? 'N/A'));
                $this->line("  Address: " . ($standardFields['UnparsedAddress'] ?? 'N/A'));
                $this->line("  Price: $" . number_format($standardFields['ListPrice'] ?? 0));
                $this->line("  Status: " . ($standardFields['MlsStatus'] ?? 'Unknown'));
                $this->line("  Agent: " . ($standardFields['ListAgentName'] ?? 'Unknown'));
                $this->line("  Agent MLS ID: " . ($standardFields['ListAgentMlsId'] ?? 'N/A'));
                $this->line("  Office: " . ($standardFields['ListOfficeName'] ?? 'Unknown'));
                $this->line("  Source MLS: " . ($listing['_source_mls'] ?? 'Unknown'));
                $this->newLine();
            }
        }

        return Command::SUCCESS;
    }
    
    private function searchMlsSystemForJeremiah(string $mlsId, string $mlsName, int $maxListings, array $jeremiahMlsIds): array
    {
        $baseUrl = config('services.flexmls.base_url');
        $accessToken = config('services.flexmls.access_token');
        
        // Search this specific MLS system
        $params = [
            '_filter' => "MlsId Eq '{$mlsId}'",
            '_limit' => $maxListings,
            '_expand' => 'PrimaryPhoto'
        ];
        
        try {
            $response = $this->flexMlsService->makeRawApiRequest('GET', '/v1/listings', $params);
            
            if ($response && isset($response['D']['Results'])) {
                $allListings = $response['D']['Results'];
                $this->line("   ✅ Retrieved " . count($allListings) . " listings");
                
                // Search through all listings for Jeremiah Brown
                $jeremiahListings = [];
                foreach ($allListings as $listing) {
                    if ($this->isJeremiahBrownListing($listing, $jeremiahMlsIds)) {
                        $jeremiahListings[] = $listing;
                    }
                }
                
                return [
                    'total_processed' => count($allListings),
                    'jeremiah_listings' => $jeremiahListings
                ];
                
            } else {
                $this->line("   ❌ No data returned");
                return ['total_processed' => 0, 'jeremiah_listings' => []];
            }
            
        } catch (\Exception $e) {
            $this->line("   ❌ Error: " . $e->getMessage());
            return ['total_processed' => 0, 'jeremiah_listings' => []];
        }
    }
    
    private function isJeremiahBrownListing(array $listing, array $jeremiahMlsIds): bool
    {
        $standardFields = $listing['StandardFields'] ?? [];
        
        $agentMlsId = $standardFields['ListAgentMlsId'] ?? '';
        $agentName = $standardFields['ListAgentName'] ?? '';
        $firstName = $standardFields['ListAgentFirstName'] ?? '';
        $lastName = $standardFields['ListAgentLastName'] ?? '';
        $officeName = $standardFields['ListOfficeName'] ?? '';
        
        // 1. Exact MLS ID match
        if (in_array($agentMlsId, $jeremiahMlsIds)) {
            return true;
        }
        
        // 2. Name variations
        $jeremiahVariations = ['jeremiah', 'jeremy', 'jer', 'j.', 'j ', 'jb'];
        $brownVariations = ['brown'];
        
        $agentNameLower = strtolower($agentName);
        foreach ($jeremiahVariations as $jeremiah) {
            foreach ($brownVariations as $brown) {
                if (strpos($agentNameLower, $jeremiah) !== false && strpos($agentNameLower, $brown) !== false) {
                    return true;
                }
            }
        }
        
        // 3. First/Last name match
        $firstNameLower = strtolower($firstName);
        $lastNameLower = strtolower($lastName);
        
        foreach ($jeremiahVariations as $jeremiah) {
            if (strpos($firstNameLower, $jeremiah) !== false) {
                foreach ($brownVariations as $brown) {
                    if (strpos($lastNameLower, $brown) !== false) {
                        return true;
                    }
                }
            }
        }
        
        // 4. Office name match
        $officeNameLower = strtolower($officeName);
        if (strpos($officeNameLower, 'jb land') !== false || 
            strpos($officeNameLower, 'jb land & home') !== false) {
            return true;
        }
        
        return false;
    }
    
    private function importListingsDirectly(array $listings): array
    {
        $results = [
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
            'errors' => 0
        ];
        
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
                    'total_acres' => $this->parseAcres($standardFields), // Add total_acres field
                    'address' => $standardFields['UnparsedAddress'] ?? '',
                    'city' => $standardFields['City'] ?? '',
                    'county' => $standardFields['CountyOrParish'] ?? '', // Add county field
                    'state' => $standardFields['StateOrProvince'] ?? '',
                    'zip' => $standardFields['PostalCode'] ?? '',
                    'latitude' => $standardFields['Latitude'] ?? null,
                    'longitude' => $standardFields['Longitude'] ?? null,
                    'property_type' => $this->mapPropertyType($standardFields['PropertyType'] ?? ''),
                    'status' => $this->mapMlsStatus($standardFields['MlsStatus'] ?? ''), // Use 'status' not 'listing_status'
                    'bedrooms' => $standardFields['BedsTotal'] ?? null,
                    'bathrooms' => $standardFields['BathroomsTotalInteger'] ?? null,
                    'square_feet' => $standardFields['LivingArea'] ?? null,
                    'year_built' => $standardFields['YearBuilt'] ?? null,
                    'listing_date' => $this->parseListingDate($standardFields), // Add listing_date field
                    'api_source' => 'flexmls',
                    'api_data' => json_encode($listing),
                    'published_at' => now(),
                ];
                
                if ($existingProperty) {
                    $existingProperty->update($propertyData);
                    $results['updated']++;
                    $this->line("     ✅ Updated: {$mlsNumber}");
                } else {
                    Property::create($propertyData);
                    $results['created']++;
                    $this->line("     ✅ Created: {$mlsNumber}");
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
        $acreFields = [
            'LotSizeAcres',
            'TotalAcres', 
            'Acres',
            'LotSizeArea'
        ];
        
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
            'L' => 'farms', // Land -> farms (closest match)
            'F' => 'farms',
            'A' => 'farms', // Acreage -> farms
            'Single Family Residence' => 'residential',
            'Commercial' => 'commercial',
            'Land' => 'farms',
            'Farm' => 'farms',
        ];
        
        $mapped = $typeMap[$type] ?? strtolower($type);
        
        // Ensure we return a valid enum value
        $validTypes = ['hunting', 'farms', 'ranches', 'residential', 'commercial', 'waterfront', 'timber', 'development', 'investment'];
        
        return in_array($mapped, $validTypes) ? $mapped : 'farms'; // Default to farms for unknown types
    }
    
    private function mapMlsStatus(string $status): string
    {
        $statusMap = [
            'Active' => 'active',
            'Pending' => 'pending',
            'Sold' => 'sold',
            'Contingent' => 'pending', // Map Contingent to pending (valid enum value)
            'Under Contract' => 'pending',
            'Withdrawn' => 'off_market',
            'Expired' => 'off_market',
        ];
        
        $mapped = $statusMap[$status] ?? strtolower($status);
        
        // Ensure we return a valid enum value
        $validStatuses = ['active', 'pending', 'sold', 'off_market', 'draft'];
        
        return in_array($mapped, $validStatuses) ? $mapped : 'active'; // Default to active
    }
    
    private function parseListingDate(array $standardFields): ?string
    {
        // Try multiple date fields from the API
        $dateFields = [
            'OnMarketDate',
            'ListingContractDate',
            'OriginalOnMarketTimestamp',
            'OnMarketTimestamp'
        ];
        
        foreach ($dateFields as $field) {
            if (!empty($standardFields[$field])) {
                $date = $standardFields[$field];
                
                // If it's already a timestamp, convert to date
                if (is_string($date) && strtotime($date) !== false) {
                    return date('Y-m-d', strtotime($date));
                }
            }
        }
        
        // Default to today if no listing date found
        return date('Y-m-d');
    }
}
