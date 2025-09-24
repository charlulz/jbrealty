<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FlexMlsApiService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeepSearchJeremiahBrown extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'flexmls:deep-search-jeremiah
                            {--all-statuses : Include all MLS statuses (Active, Pending, Sold, Expired, etc.)}
                            {--large-batches : Use larger batch sizes for comprehensive search}';

    /**
     * The console command description.
     */
    protected $description = 'Deep search across ALL MLS data for Jeremiah Brown listings using different parameters and approaches';

    protected FlexMlsApiService $flexMlsService;

    public function __construct(FlexMlsApiService $flexMlsService)
    {
        parent::__construct();
        $this->flexMlsService = $flexMlsService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Deep Search for Jeremiah Brown Listings');
        $this->info('=========================================');
        
        $allStatuses = $this->option('all-statuses');
        $largeBatches = $this->option('large-batches');
        
        $this->info('🎯 Member ID: 20271 (Jeremiah Brown)');
        $this->info('🏢 Office: JB Land & Home Realty (from previous data)');
        
        if ($allStatuses) {
            $this->info('📊 Including all MLS statuses');
        }
        
        $this->newLine();
        
        $baseUrl = config('services.flexmls.base_url');
        $accessToken = config('services.flexmls.access_token');
        
        // Try different search strategies
        $searchStrategies = [
            'no_filters' => [
                'name' => 'No Filters (All Listings)',
                'params' => [
                    '_limit' => $largeBatches ? 1000 : 500,
                    '_expand' => 'PrimaryPhoto'
                ]
            ],
            'office_search' => [
                'name' => 'Search by Office (JB Land & Home)',
                'params' => [
                    '_limit' => $largeBatches ? 1000 : 500,
                    '_expand' => 'PrimaryPhoto',
                    '$filter' => "contains(ListOfficeName,'JB Land')"
                ]
            ],
            'agent_name_search' => [
                'name' => 'Search by Agent Name (Brown)',
                'params' => [
                    '_limit' => $largeBatches ? 1000 : 500,
                    '_expand' => 'PrimaryPhoto',
                    '$filter' => "contains(ListAgentName,'Brown')"
                ]
            ],
            'recent_listings' => [
                'name' => 'Recent Listings (Last 365 Days)',
                'params' => [
                    '_limit' => $largeBatches ? 1000 : 500,
                    '_expand' => 'PrimaryPhoto',
                    '$filter' => "OnMarketDate gt " . date('Y-m-d', strtotime('-365 days'))
                ]
            ]
        ];
        
        if ($allStatuses) {
            // Add different status searches
            $statuses = ['Active', 'Pending', 'Under Contract', 'Sold', 'Closed', 'Expired', 'Cancelled', 'Withdrawn'];
            foreach ($statuses as $status) {
                $searchStrategies["status_{$status}"] = [
                    'name' => "Status: {$status}",
                    'params' => [
                        '_limit' => 200,
                        '_expand' => 'PrimaryPhoto',
                        '$filter' => "MlsStatus eq '{$status}'"
                    ]
                ];
            }
        }
        
        $totalJeremiahListings = [];
        $totalProcessed = 0;
        
        foreach ($searchStrategies as $key => $strategy) {
            $this->info("🔍 Strategy: {$strategy['name']}");
            
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => 'application/json',
                ])->timeout(60)->get($baseUrl . '/v1/listings', $strategy['params']);
                
                if ($response->successful()) {
                    $data = $response->json();
                    $listings = $data['D']['Results'] ?? [];
                    $count = count($listings);
                    $totalProcessed += $count;
                    
                    $this->line("  ✅ Retrieved {$count} listings");
                    
                    // Search through these listings
                    $strategyFindings = [];
                    foreach ($listings as $listing) {
                        $standardFields = $listing['StandardFields'] ?? [];
                        
                        $agentName = $standardFields['ListAgentName'] ?? '';
                        $firstName = $standardFields['ListAgentFirstName'] ?? '';
                        $lastName = $standardFields['ListAgentLastName'] ?? '';
                        $mlsId = $standardFields['ListAgentMlsId'] ?? '';
                        $officeName = $standardFields['ListOfficeName'] ?? '';
                        $listingId = $standardFields['ListingId'] ?? '';
                        
                        // Check if this is Jeremiah Brown by any criteria
                        $isMatch = false;
                        $matchReason = '';
                        
                        if ($mlsId == '20271') {
                            $isMatch = true;
                            $matchReason = 'MLS ID 20271';
                        } elseif ($this->isJeremiahBrownMatch($agentName, $firstName, $lastName)) {
                            $isMatch = true;
                            $matchReason = 'Name match';
                        } elseif (stripos($officeName, 'JB Land') !== false || stripos($officeName, 'JB Land & Home') !== false) {
                            $isMatch = true;
                            $matchReason = 'Office match (JB Land)';
                        }
                        
                        if ($isMatch) {
                            $jeremiahListing = [
                                'listing_id' => $listingId,
                                'address' => $standardFields['UnparsedAddress'] ?? 'N/A',
                                'price' => $standardFields['ListPrice'] ?? 0,
                                'status' => $standardFields['MlsStatus'] ?? 'Unknown',
                                'property_type' => $this->mapPropertyType($standardFields['PropertyType'] ?? ''),
                                'agent_name' => $agentName,
                                'first_name' => $firstName,
                                'last_name' => $lastName,
                                'mls_id' => $mlsId,
                                'office_name' => $officeName,
                                'on_market_date' => $standardFields['OnMarketDate'] ?? 'N/A',
                                'match_reason' => $matchReason,
                                'search_strategy' => $strategy['name'],
                            ];
                            
                            $strategyFindings[] = $jeremiahListing;
                            
                            // Avoid duplicates in total list
                            $existsInTotal = false;
                            foreach ($totalJeremiahListings as $existing) {
                                if ($existing['listing_id'] === $listingId) {
                                    $existsInTotal = true;
                                    break;
                                }
                            }
                            
                            if (!$existsInTotal) {
                                $totalJeremiahListings[] = $jeremiahListing;
                            }
                        }
                    }
                    
                    if (!empty($strategyFindings)) {
                        $this->info("  🎉 Found " . count($strategyFindings) . " Jeremiah Brown listings!");
                        foreach ($strategyFindings as $listing) {
                            $this->line("    • MLS #{$listing['listing_id']}: {$listing['address']} - $" . number_format($listing['price']) . " ({$listing['status']}) - {$listing['match_reason']}");
                        }
                    } else {
                        $this->line("  ⚪ No matches found");
                    }
                    
                } else {
                    $this->line("  ❌ Failed: HTTP " . $response->status());
                    if ($response->status() == 400) {
                        $this->line("    Response: " . substr($response->body(), 0, 200));
                    }
                }
                
            } catch (\Exception $e) {
                $this->line("  ❌ Error: " . $e->getMessage());
            }
            
            $this->newLine();
            
            // Small delay between searches
            if (count($searchStrategies) > 1) {
                usleep(500000);
            }
        }
        
        // Final summary
        $this->info("📊 SEARCH SUMMARY:");
        $this->line("  • Total listings processed: " . number_format($totalProcessed));
        $this->line("  • Unique Jeremiah Brown listings found: " . count($totalJeremiahListings));
        
        if (!empty($totalJeremiahListings)) {
            $this->newLine();
            $this->info("🎉 ALL JEREMIAH BROWN LISTINGS FOUND:");
            $this->newLine();
            
            foreach ($totalJeremiahListings as $index => $listing) {
                $this->info("Listing " . ($index + 1) . ":");
                $this->line("  MLS #: {$listing['listing_id']}");
                $this->line("  Address: {$listing['address']}");
                $this->line("  Price: $" . number_format($listing['price']));
                $this->line("  Status: {$listing['status']}");
                $this->line("  Type: {$listing['property_type']}");
                $this->line("  Agent: {$listing['agent_name']}");
                $this->line("  MLS Agent ID: {$listing['mls_id']}");
                $this->line("  Office: {$listing['office_name']}");
                $this->line("  On Market: {$listing['on_market_date']}");
                $this->line("  Match Reason: {$listing['match_reason']}");
                $this->line("  Found By: {$listing['search_strategy']}");
                $this->newLine();
            }
            
            // Group by status
            $statusGroups = [];
            foreach ($totalJeremiahListings as $listing) {
                $status = $listing['status'];
                $statusGroups[$status] = ($statusGroups[$status] ?? 0) + 1;
            }
            
            $this->info("📈 Listings by Status:");
            foreach ($statusGroups as $status => $count) {
                $this->line("  • {$status}: {$count}");
            }
            
        } else {
            $this->newLine();
            $this->warn("⚠️  No Jeremiah Brown listings found across all search strategies");
            $this->info("💡 This could mean:");
            $this->line("  • Jeremiah Brown is not currently in this MLS system");
            $this->line("  • His listings might be in a different MLS that's not accessible");
            $this->line("  • His agent information might be formatted differently");
            $this->line("  • He might not have active listings at this time");
        }

        return Command::SUCCESS;
    }
    
    /**
     * Check if agent info matches Jeremiah Brown
     */
    private function isJeremiahBrownMatch(string $agentName, string $firstName, string $lastName): bool
    {
        $jeremiahVariations = ['jeremiah', 'jeremy', 'jer', 'j.', 'j ', 'j b', 'jb'];
        $brownVariations = ['brown'];
        
        // Check full name
        $fullNameLower = strtolower($agentName);
        foreach ($jeremiahVariations as $jeremiah) {
            foreach ($brownVariations as $brown) {
                if (strpos($fullNameLower, $jeremiah) !== false && strpos($fullNameLower, $brown) !== false) {
                    return true;
                }
            }
        }
        
        // Check first/last name
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
        
        return false;
    }
    
    /**
     * Map property type
     */
    private function mapPropertyType(string $type): string
    {
        $typeMap = [
            'R' => 'residential',
            'C' => 'commercial', 
            'L' => 'land',
            'F' => 'farm',
            'A' => 'acreage/land',
            'Single Family Residence' => 'residential',
            'Commercial' => 'commercial',
            'Land' => 'land',
            'Farm' => 'farm',
        ];

        return $typeMap[$type] ?? ($type ?: 'unknown');
    }
}
