<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FlexMlsApiService;
use Illuminate\Support\Facades\Http;

class SearchByLicense extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'flexmls:search-by-license
                            {license=294658 : State license number to search for}
                            {--max-per-mls=500 : Maximum listings per MLS system}';

    /**
     * The console command description.
     */
    protected $description = 'Search for Jeremiah Brown listings using his state license number';

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
        $license = $this->argument('license');
        $maxPerMls = $this->option('max-per-mls');
        
        $this->info('🔍 Searching by State License Number');
        $this->info('==================================');
        $this->info('🎯 Target License: ' . $license);
        $this->info('📊 Max listings per MLS: ' . $maxPerMls);
        $this->newLine();
        
        // All known MLS systems
        $mlsSystems = [
            '20250213134913285161000000' => 'ImagineMLS',
            '20210504182759685317000000' => 'Unknown MLS #3',
            '20240123174036063766000000' => 'Unknown MLS #2',
            '20130925153233009157000000' => 'Metro Search MLS',
            '20130228193502179028000000' => 'Knoxville Area Association MLS',
        ];
        
        $allFoundListings = [];
        $totalProcessed = 0;
        
        foreach ($mlsSystems as $mlsId => $mlsName) {
            $this->info("🏢 Searching {$mlsName}...");
            
            $mlsListings = $this->searchMlsByLicense($mlsId, $mlsName, $maxPerMls, $license);
            $totalProcessed += $mlsListings['total_processed'];
            
            if (!empty($mlsListings['license_listings'])) {
                $licenseCount = count($mlsListings['license_listings']);
                $this->info("   🎉 Found {$licenseCount} listings with license {$license}!");
                
                // Display found listings
                foreach ($mlsListings['license_listings'] as $listing) {
                    $standardFields = $listing['StandardFields'] ?? [];
                    $listingId = $standardFields['ListingId'] ?? 'N/A';
                    $address = $standardFields['UnparsedAddress'] ?? 'N/A';
                    $price = $standardFields['ListPrice'] ?? 0;
                    $status = $standardFields['MlsStatus'] ?? 'Unknown';
                    $agentName = $standardFields['ListAgentName'] ?? 'Unknown';
                    $agentLicense = $standardFields['ListAgentStateLicense'] ?? 'N/A';
                    $acres = $this->parseAcres($standardFields);
                    
                    $this->line("     • MLS #{$listingId}: {$address}");
                    $this->line("       Price: $" . number_format($price) . " ({$status}) - {$acres} acres");
                    $this->line("       Agent: {$agentName} (License: {$agentLicense})");
                }
                
                $allFoundListings = array_merge($allFoundListings, $mlsListings['license_listings']);
            } else {
                $this->line("   ⚪ No listings found with license {$license}");
            }
            
            $this->newLine();
            usleep(500000); // 0.5 second delay
        }
        
        // Final Results Summary
        $this->info("📊 SEARCH COMPLETE:");
        $this->line("  • Total listings processed: " . number_format($totalProcessed));
        $this->line("  • MLS systems searched: " . count($mlsSystems));
        $this->line("  • Listings found with license {$license}: " . count($allFoundListings));
        
        if (!empty($allFoundListings)) {
            $this->newLine();
            $this->info("🏆 SUMMARY OF ALL LISTINGS FOUND:");
            
            // Status breakdown
            $statusCounts = [];
            $totalValue = 0;
            $totalAcres = 0;
            
            foreach ($allFoundListings as $listing) {
                $standardFields = $listing['StandardFields'] ?? [];
                $status = $standardFields['MlsStatus'] ?? 'Unknown';
                $price = $standardFields['ListPrice'] ?? 0;
                $acres = $this->parseAcres($standardFields);
                
                $statusCounts[$status] = ($statusCounts[$status] ?? 0) + 1;
                $totalValue += $price;
                $totalAcres += $acres;
            }
            
            $this->line("📈 By Status:");
            foreach ($statusCounts as $status => $count) {
                $this->line("  • {$status}: {$count}");
            }
            
            $this->newLine();
            $this->line("💰 Total Portfolio Value: $" . number_format($totalValue));
            $this->line("🏞️  Total Acres: " . number_format($totalAcres, 2) . " acres");
            $this->line("📊 Average Price: $" . number_format($totalValue / count($allFoundListings)));
            $this->line("📏 Average Size: " . number_format($totalAcres / count($allFoundListings), 2) . " acres");
            
            if (count($allFoundListings) > 2) {
                $this->newLine();
                $this->info("🎉 SUCCESS: Found " . count($allFoundListings) . " listings (more than the 2 we found before)!");
                $this->warn("💡 Consider updating the import command to use license-based search for better results.");
            }
        }

        return Command::SUCCESS;
    }
    
    private function searchMlsByLicense(string $mlsId, string $mlsName, int $maxListings, string $license): array
    {
        $baseUrl = config('services.flexmls.base_url');
        $accessToken = config('services.flexmls.access_token');
        
        // Search this specific MLS system for the license number
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
                
                // Search through all listings for the license number
                $licenseListings = [];
                foreach ($allListings as $listing) {
                    $standardFields = $listing['StandardFields'] ?? [];
                    $agentLicense = $standardFields['ListAgentStateLicense'] ?? '';
                    
                    // Check if license matches (exact match)
                    if ($agentLicense === $license) {
                        $listing['_source_mls'] = $mlsName;
                        $listing['_source_mls_id'] = $mlsId;
                        $licenseListings[] = $listing;
                    }
                }
                
                return [
                    'total_processed' => count($allListings),
                    'license_listings' => $licenseListings
                ];
                
            } else {
                $this->line("   ❌ Failed: HTTP " . $response->status());
                return ['total_processed' => 0, 'license_listings' => []];
            }
            
        } catch (\Exception $e) {
            $this->line("   ❌ Error: " . $e->getMessage());
            return ['total_processed' => 0, 'license_listings' => []];
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
}
