<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FlexMlsApiService;
use Illuminate\Support\Facades\Http;

class DeepAgentSearch extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'flexmls:deep-agent-search
                            {--license=294658 : State license number}
                            {--agent-ids=20271,429520271 : Comma-separated agent MLS IDs}
                            {--max-total=10000 : Maximum total listings to search across all systems}';

    /**
     * The console command description.
     */
    protected $description = 'Comprehensive search for ALL Jeremiah Brown listings using multiple identifiers';

    protected FlexMlsApiService $flexMlsService;

    public function __construct(FlexMlsApiService $flexMlsService)
    {
        parent::__construct();
        $this->flexMlsService = $flexMlsService;
    }

    public function handle()
    {
        $license = $this->option('license');
        $agentIds = explode(',', $this->option('agent-ids'));
        $maxTotal = $this->option('max-total');
        
        $this->info('🔍 COMPREHENSIVE Jeremiah Brown Search');
        $this->info('=====================================');
        $this->info('🎯 License: ' . $license);
        $this->info('🆔 Agent IDs: ' . implode(', ', $agentIds));
        $this->info('📊 Max total listings: ' . number_format($maxTotal));
        $this->newLine();
        
        // Get raw data without MLS filtering to see total available listings
        $this->info('1️⃣ Getting overview of total available data...');
        $totalAvailable = $this->getRawDataOverview();
        $this->newLine();
        
        // Search across all systems with high limits
        $this->info('2️⃣ Comprehensive search across all MLS systems...');
        $allJeremiahListings = $this->comprehensiveSearch($license, $agentIds, $maxTotal);
        
        $this->displayResults($allJeremiahListings, $totalAvailable);
        
        return Command::SUCCESS;
    }
    
    private function getRawDataOverview(): int
    {
        $baseUrl = config('services.flexmls.base_url');
        $accessToken = config('services.flexmls.access_token');
        
        try {
            // Get total count without any filters
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
            ])->timeout(30)->get($baseUrl . '/v1/listings', [
                '_limit' => 1 // Just get count info
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Try to get pagination info or total count
                if (isset($data['D']['Pagination']['Total'])) {
                    $total = $data['D']['Pagination']['Total'];
                } else {
                    // If no pagination info, estimate based on available data
                    $total = 'Unknown (no pagination info)';
                }
                
                $this->line("   📊 Total listings available in API: {$total}");
                
                // Check if there are any unusual MLS systems in the first batch
                if (isset($data['D']['Results']) && !empty($data['D']['Results'])) {
                    $sampleListing = $data['D']['Results'][0] ?? [];
                    if (isset($sampleListing['StandardFields']['MlsId'])) {
                        $sampleMlsId = $sampleListing['StandardFields']['MlsId'];
                        $this->line("   🏢 Sample MLS ID found: {$sampleMlsId}");
                    }
                }
                
                return is_numeric($total) ? (int)$total : 0;
            }
        } catch (\Exception $e) {
            $this->error("   ❌ Error getting overview: " . $e->getMessage());
        }
        
        return 0;
    }
    
    private function comprehensiveSearch(string $license, array $agentIds, int $maxTotal): array
    {
        $allListings = [];
        $processed = 0;
        $batchSize = 1000; // Large batches
        $currentOffset = 0;
        
        $this->info("   Searching in large batches of {$batchSize}...");
        
        while ($processed < $maxTotal) {
            $this->line("   Batch: " . number_format($currentOffset) . " - " . number_format($currentOffset + $batchSize));
            
            $batchListings = $this->searchBatch($currentOffset, $batchSize);
            
            if (empty($batchListings)) {
                $this->line("   No more data available.");
                break;
            }
            
            // Filter for Jeremiah Brown in this batch
            foreach ($batchListings as $listing) {
                if ($this->isJeremiahBrownListing($listing, $license, $agentIds)) {
                    $allListings[] = $listing;
                    
                    $standardFields = $listing['StandardFields'] ?? [];
                    $listingId = $standardFields['ListingId'] ?? 'N/A';
                    $address = $standardFields['UnparsedAddress'] ?? 'N/A';
                    $agentName = $standardFields['ListAgentName'] ?? 'N/A';
                    
                    $this->info("     🎉 FOUND: MLS #{$listingId} - {$address} - {$agentName}");
                }
            }
            
            $processed += count($batchListings);
            $currentOffset += $batchSize;
            
            // Small delay to avoid rate limiting
            usleep(500000); // 0.5 seconds
            
            // If we got less than batch size, we've reached the end
            if (count($batchListings) < $batchSize) {
                break;
            }
        }
        
        $this->line("   ✅ Processed " . number_format($processed) . " total listings");
        
        return $allListings;
    }
    
    private function searchBatch(int $offset, int $limit): array
    {
        $baseUrl = config('services.flexmls.base_url');
        $accessToken = config('services.flexmls.access_token');
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
            ])->timeout(60)->get($baseUrl . '/v1/listings', [
                '_limit' => $limit,
                '_offset' => $offset,
                '_expand' => 'PrimaryPhoto'
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                return $data['D']['Results'] ?? [];
            }
            
        } catch (\Exception $e) {
            $this->error("     ❌ Batch error at offset {$offset}: " . $e->getMessage());
        }
        
        return [];
    }
    
    private function isJeremiahBrownListing(array $listing, string $license, array $agentIds): bool
    {
        $standardFields = $listing['StandardFields'] ?? [];
        
        $agentLicense = $standardFields['ListAgentStateLicense'] ?? '';
        $agentMlsId = $standardFields['ListAgentMlsId'] ?? '';
        $agentName = $standardFields['ListAgentName'] ?? '';
        $firstName = $standardFields['ListAgentFirstName'] ?? '';
        $lastName = $standardFields['ListAgentLastName'] ?? '';
        $officeName = $standardFields['ListOfficeName'] ?? '';
        
        // 1. License match
        if ($agentLicense === $license) {
            return true;
        }
        
        // 2. Agent MLS ID match
        if (in_array($agentMlsId, $agentIds)) {
            return true;
        }
        
        // 3. Name variations
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
        
        // 4. First/Last name match
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
        
        // 5. Office name match
        $officeNameLower = strtolower($officeName);
        if (strpos($officeNameLower, 'jb land') !== false || 
            strpos($officeNameLower, 'jb land & home') !== false) {
            return true;
        }
        
        return false;
    }
    
    private function displayResults(array $listings, int $totalAvailable): void
    {
        $this->newLine();
        $this->info('🏆 COMPREHENSIVE SEARCH RESULTS');
        $this->info('==============================');
        
        if (empty($listings)) {
            $this->warn('No additional listings found with comprehensive search.');
            $this->info('This suggests the 2 listings we found earlier are likely all the currently active listings.');
            return;
        }
        
        $this->line('📊 Found ' . count($listings) . ' total Jeremiah Brown listings');
        $this->newLine();
        
        $statusCounts = [];
        $totalValue = 0;
        $totalAcres = 0;
        
        foreach ($listings as $index => $listing) {
            $standardFields = $listing['StandardFields'] ?? [];
            
            $listingId = $standardFields['ListingId'] ?? 'N/A';
            $address = $standardFields['UnparsedAddress'] ?? 'N/A';
            $price = $standardFields['ListPrice'] ?? 0;
            $status = $standardFields['MlsStatus'] ?? 'Unknown';
            $agentName = $standardFields['ListAgentName'] ?? 'Unknown';
            $agentMlsId = $standardFields['ListAgentMlsId'] ?? 'N/A';
            $agentLicense = $standardFields['ListAgentStateLicense'] ?? 'N/A';
            $acres = $this->parseAcres($standardFields);
            
            $this->info("Listing " . ($index + 1) . ":");
            $this->line("  MLS #: {$listingId}");
            $this->line("  Address: {$address}");
            $this->line("  Price: $" . number_format($price) . " ({$status})");
            $this->line("  Size: {$acres} acres");
            $this->line("  Agent: {$agentName} (ID: {$agentMlsId}, License: {$agentLicense})");
            $this->newLine();
            
            $statusCounts[$status] = ($statusCounts[$status] ?? 0) + 1;
            $totalValue += $price;
            $totalAcres += $acres;
        }
        
        $this->info('📈 Summary:');
        foreach ($statusCounts as $status => $count) {
            $this->line("  • {$status}: {$count}");
        }
        $this->line("  • Total Value: $" . number_format($totalValue));
        $this->line("  • Total Acres: " . number_format($totalAcres, 2));
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
}
