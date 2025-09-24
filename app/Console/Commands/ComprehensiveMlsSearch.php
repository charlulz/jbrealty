<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ComprehensiveMlsSearch extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'flexmls:comprehensive-search
                            {--member-id=20271 : Member ID to search for}
                            {--max-per-mls=500 : Maximum listings per MLS system}';

    /**
     * The console command description.
     */
    protected $description = 'Comprehensive search across ALL MLS systems for Jeremiah Brown listings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Comprehensive MLS Search for Jeremiah Brown');
        $this->info('============================================');
        
        $memberId = $this->option('member-id');
        $maxPerMls = $this->option('max-per-mls');
        
        $this->info('🎯 Target Member ID: ' . $memberId);
        $this->info('📊 Max listings per MLS: ' . $maxPerMls);
        $this->newLine();
        
        // All known MLS systems from previous tests
        $mlsSystems = [
            '20250213134913285161000000' => 'ImagineMLS',
            '20240123174036063766000000' => 'Unknown MLS #2', 
            '20210504182759685317000000' => 'Unknown MLS #3',
            '20130925153233009157000000' => 'Metro Search MLS',
            '20130228193502179028000000' => 'Knoxville Area Association MLS',
        ];
        
        $allJeremiahListings = [];
        $totalProcessed = 0;
        
        foreach ($mlsSystems as $mlsId => $mlsName) {
            $this->info("🏢 Searching {$mlsName}...");
            $this->line("   MLS ID: {$mlsId}");
            
            $mlsListings = $this->searchMlsSystem($mlsId, $mlsName, $maxPerMls, $memberId);
            $totalProcessed += count($mlsListings['all_listings']);
            
            if (!empty($mlsListings['jeremiah_listings'])) {
                $jeremiahCount = count($mlsListings['jeremiah_listings']);
                $this->info("   🎉 Found {$jeremiahCount} Jeremiah Brown listings in {$mlsName}!");
                
                // Add to master list (avoiding duplicates)
                foreach ($mlsListings['jeremiah_listings'] as $listing) {
                    $listingId = $listing['StandardFields']['ListingId'] ?? 'unknown';
                    
                    // Check for duplicate
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
                $this->line("   ⚪ No Jeremiah Brown listings found in {$mlsName}");
            }
            
            $this->newLine();
            usleep(800000); // 0.8 second delay between MLS searches
        }
        
        // Final Results
        $this->info("📊 SEARCH COMPLETE:");
        $this->line("  • Total listings processed: " . number_format($totalProcessed));
        $this->line("  • MLS systems searched: " . count($mlsSystems));
        $this->line("  • Jeremiah Brown listings found: " . count($allJeremiahListings));
        $this->newLine();
        
        if (!empty($allJeremiahListings)) {
            $this->info("🎉 ALL JEREMIAH BROWN LISTINGS FOUND:");
            $this->newLine();
            
            foreach ($allJeremiahListings as $index => $listing) {
                $standardFields = $listing['StandardFields'] ?? [];
                
                $this->info("Listing " . ($index + 1) . ":");
                $this->line("  MLS #: " . ($standardFields['ListingId'] ?? 'N/A'));
                $this->line("  Address: " . ($standardFields['UnparsedAddress'] ?? 'N/A'));
                $this->line("  Price: $" . number_format($standardFields['ListPrice'] ?? 0));
                $this->line("  Status: " . ($standardFields['MlsStatus'] ?? 'Unknown'));
                $this->line("  Property Type: " . $this->mapPropertyType($standardFields['PropertyType'] ?? ''));
                $this->line("  Agent: " . ($standardFields['ListAgentName'] ?? 'Unknown'));
                $this->line("  Agent MLS ID: " . ($standardFields['ListAgentMlsId'] ?? 'N/A'));
                $this->line("  Office: " . ($standardFields['ListOfficeName'] ?? 'Unknown'));
                $this->line("  On Market: " . ($standardFields['OnMarketDate'] ?? 'N/A'));
                $this->line("  Source MLS: " . ($listing['_source_mls'] ?? 'Unknown'));
                $this->newLine();
            }
            
            // Status breakdown
            $statusCounts = [];
            foreach ($allJeremiahListings as $listing) {
                $status = $listing['StandardFields']['MlsStatus'] ?? 'Unknown';
                $statusCounts[$status] = ($statusCounts[$status] ?? 0) + 1;
            }
            
            $this->info("📈 Listings by Status:");
            foreach ($statusCounts as $status => $count) {
                $this->line("  • {$status}: {$count}");
            }
            
            // MLS breakdown
            $mlsCounts = [];
            foreach ($allJeremiahListings as $listing) {
                $mls = $listing['_source_mls'] ?? 'Unknown';
                $mlsCounts[$mls] = ($mlsCounts[$mls] ?? 0) + 1;
            }
            
            $this->newLine();
            $this->info("🏢 Listings by MLS System:");
            foreach ($mlsCounts as $mls => $count) {
                $this->line("  • {$mls}: {$count}");
            }
            
        } else {
            $this->warn("⚠️  No Jeremiah Brown listings found across all MLS systems");
            $this->info("💡 This could indicate:");
            $this->line("  • Agent name/ID variations we haven't tried");
            $this->line("  • Listings under a different office");
            $this->line("  • All listings might be historical/expired");
            $this->line("  • Different MLS systems we haven't discovered");
        }

        return Command::SUCCESS;
    }
    
    private function searchMlsSystem(string $mlsId, string $mlsName, int $maxListings, string $targetMemberId): array
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
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
            ])->timeout(60)->get($baseUrl . '/v1/listings', $params);
            
            if ($response->successful()) {
                $data = $response->json();
                $allListings = $data['D']['Results'] ?? [];
                $this->line("   ✅ Retrieved " . count($allListings) . " listings");
                
                // Search through all listings for Jeremiah Brown
                $jeremiahListings = [];
                foreach ($allListings as $listing) {
                    if ($this->isJeremiahBrownListing($listing, $targetMemberId)) {
                        $jeremiahListings[] = $listing;
                    }
                }
                
                return [
                    'all_listings' => $allListings,
                    'jeremiah_listings' => $jeremiahListings
                ];
                
            } else {
                $this->line("   ❌ Failed: HTTP " . $response->status());
                return ['all_listings' => [], 'jeremiah_listings' => []];
            }
            
        } catch (\Exception $e) {
            $this->line("   ❌ Error: " . $e->getMessage());
            return ['all_listings' => [], 'jeremiah_listings' => []];
        }
    }
    
    private function isJeremiahBrownListing(array $listing, string $targetMemberId): bool
    {
        $standardFields = $listing['StandardFields'] ?? [];
        
        $agentMlsId = $standardFields['ListAgentMlsId'] ?? '';
        $agentName = $standardFields['ListAgentName'] ?? '';
        $firstName = $standardFields['ListAgentFirstName'] ?? '';
        $lastName = $standardFields['ListAgentLastName'] ?? '';
        $officeName = $standardFields['ListOfficeName'] ?? '';
        
        // 1. Exact MLS ID match
        if ($agentMlsId === $targetMemberId) {
            return true;
        }
        
        // 2. Full name variations
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
        
        // 3. First name + Last name match
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
        
        // 4. Office name match (JB Land & Home Realty)
        $officeNameLower = strtolower($officeName);
        if (strpos($officeNameLower, 'jb land') !== false || 
            strpos($officeNameLower, 'jb land & home') !== false) {
            return true;
        }
        
        return false;
    }
    
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
