<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FlexMlsApiService;
use Illuminate\Support\Facades\Http;

class HistoricalJeremiahSearch extends Command
{
    protected $signature = 'flexmls:historical-jeremiah
                            {--years=3 : How many years back to search}
                            {--include-sold : Include sold listings}
                            {--include-expired : Include expired/withdrawn listings}
                            {--all-statuses : Include all possible statuses}';

    protected $description = 'Search for Jeremiah Brown listings including historical (sold/expired) data';

    protected FlexMlsApiService $flexMlsService;

    public function __construct(FlexMlsApiService $flexMlsService)
    {
        parent::__construct();
        $this->flexMlsService = $flexMlsService;
    }

    public function handle()
    {
        $years = $this->option('years');
        $includeSold = $this->option('include-sold');
        $includeExpired = $this->option('include-expired'); 
        $allStatuses = $this->option('all-statuses');
        
        $this->info('📅 Historical Jeremiah Brown Search');
        $this->info('==================================');
        $this->info('🗓️  Searching back: ' . $years . ' years');
        $this->info('💰 Include sold: ' . ($includeSold ? 'Yes' : 'No'));
        $this->info('❌ Include expired: ' . ($includeExpired ? 'Yes' : 'No'));
        $this->info('📊 All statuses: ' . ($allStatuses ? 'Yes' : 'No'));
        $this->newLine();
        
        // Define search parameters for historical data
        $searchStrategies = [];
        
        if ($allStatuses) {
            $searchStrategies['All Statuses'] = [];
        } else {
            if ($includeSold) {
                $searchStrategies['Sold Listings'] = ['status_filter' => 'Sold'];
            }
            if ($includeExpired) {
                $searchStrategies['Expired/Withdrawn'] = ['status_filter' => 'Expired'];
                $searchStrategies['Withdrawn'] = ['status_filter' => 'Withdrawn']; 
            }
            $searchStrategies['Active/Pending'] = ['status_filter' => 'Active,Pending,Contingent'];
        }
        
        $allFoundListings = [];
        
        foreach ($searchStrategies as $strategyName => $params) {
            $this->info("🔍 Strategy: {$strategyName}");
            $strategyListings = $this->searchWithStrategy($strategyName, $params, $years);
            
            if (!empty($strategyListings)) {
                $this->info("   🎉 Found " . count($strategyListings) . " listings");
                $allFoundListings = array_merge($allFoundListings, $strategyListings);
            } else {
                $this->line("   ⚪ No listings found");
            }
            $this->newLine();
        }
        
        // Remove duplicates
        $uniqueListings = $this->removeDuplicates($allFoundListings);
        
        $this->displayHistoricalResults($uniqueListings);
        
        return Command::SUCCESS;
    }
    
    private function searchWithStrategy(string $strategyName, array $params, int $years): array
    {
        $foundListings = [];
        
        // MLS systems to search
        $mlsSystems = [
            '20250213134913285161000000' => 'ImagineMLS',
            '20210504182759685317000000' => 'Unknown MLS #3',
            '20240123174036063766000000' => 'Unknown MLS #2', 
            '20130925153233009157000000' => 'Metro Search MLS',
            '20130228193502179028000000' => 'Knoxville MLS',
        ];
        
        foreach ($mlsSystems as $mlsId => $mlsName) {
            $mlsListings = $this->searchMlsHistorical($mlsId, $mlsName, $params, $years);
            if (!empty($mlsListings)) {
                $foundListings = array_merge($foundListings, $mlsListings);
                $this->line("   - {$mlsName}: " . count($mlsListings) . " listings");
            }
        }
        
        return $foundListings;
    }
    
    private function searchMlsHistorical(string $mlsId, string $mlsName, array $params, int $years): array
    {
        $baseUrl = config('services.flexmls.base_url');
        $accessToken = config('services.flexmls.access_token');
        
        // Build search parameters
        $searchParams = [
            '_limit' => 500,
            '_expand' => 'PrimaryPhoto'
        ];
        
        // Add MLS filter
        $filters = ["MlsId Eq '{$mlsId}'"];
        
        // Add date range (search last X years)
        $cutoffDate = date('Y-m-d', strtotime("-{$years} years"));
        $filters[] = "ModificationTimestamp Gt {$cutoffDate}";
        
        // Add status filter if specified
        if (isset($params['status_filter'])) {
            $statuses = explode(',', $params['status_filter']);
            $statusFilters = [];
            foreach ($statuses as $status) {
                $statusFilters[] = "MlsStatus Eq '" . trim($status) . "'";
            }
            if (!empty($statusFilters)) {
                $filters[] = '(' . implode(' Or ', $statusFilters) . ')';
            }
        }
        
        $searchParams['_filter'] = implode(' And ', $filters);
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
            ])->timeout(45)->get($baseUrl . '/v1/listings', $searchParams);
            
            if ($response->successful()) {
                $data = $response->json();
                $allListings = $data['D']['Results'] ?? [];
                
                // Filter for Jeremiah Brown
                $jeremiahListings = [];
                foreach ($allListings as $listing) {
                    if ($this->isJeremiahBrownListing($listing)) {
                        $listing['_source_mls'] = $mlsName;
                        $listing['_source_mls_id'] = $mlsId;
                        $jeremiahListings[] = $listing;
                    }
                }
                
                return $jeremiahListings;
                
            } else {
                // Try without status filter if it failed
                if (isset($searchParams['_filter']) && strpos($searchParams['_filter'], 'MlsStatus') !== false) {
                    $this->line("   ⚠️  {$mlsName}: Status filter failed, trying without it...");
                    $searchParams['_filter'] = "MlsId Eq '{$mlsId}' And ModificationTimestamp Gt {$cutoffDate}";
                    
                    $response2 = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $accessToken,
                        'Accept' => 'application/json',
                    ])->timeout(45)->get($baseUrl . '/v1/listings', $searchParams);
                    
                    if ($response2->successful()) {
                        $data = $response2->json();
                        $allListings = $data['D']['Results'] ?? [];
                        
                        $jeremiahListings = [];
                        foreach ($allListings as $listing) {
                            if ($this->isJeremiahBrownListing($listing)) {
                                $listing['_source_mls'] = $mlsName;
                                $listing['_source_mls_id'] = $mlsId;
                                $jeremiahListings[] = $listing;
                            }
                        }
                        
                        return $jeremiahListings;
                    }
                }
            }
            
        } catch (\Exception $e) {
            // Silent fail for individual MLS systems
        }
        
        return [];
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
        
        // 1. MLS ID match
        if (in_array($agentMlsId, $knownIds)) {
            return true;
        }
        
        // 2. License match
        if ($agentLicense === $knownLicense) {
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
    
    private function displayHistoricalResults(array $listings): void
    {
        $this->info('🏆 HISTORICAL SEARCH RESULTS');
        $this->info('============================');
        
        if (empty($listings)) {
            $this->warn('No additional historical listings found.');
            $this->info('💡 This suggests that:');
            $this->line('   • The 2 current listings may be all that are currently available');
            $this->line('   • Historical data may have different agent IDs/names');  
            $this->line('   • The ~30 listings may be from a longer time period or different systems');
            return;
        }
        
        $this->info('📊 Found ' . count($listings) . ' total listings (including historical)');
        $this->newLine();
        
        // Organize by status and date
        $byStatus = [];
        $byYear = [];
        
        foreach ($listings as $listing) {
            $standardFields = $listing['StandardFields'] ?? [];
            $status = $standardFields['MlsStatus'] ?? 'Unknown';
            $modTime = $standardFields['ModificationTimestamp'] ?? '';
            
            $byStatus[$status] = ($byStatus[$status] ?? 0) + 1;
            
            if ($modTime) {
                $year = date('Y', strtotime($modTime));
                $byYear[$year] = ($byYear[$year] ?? 0) + 1;
            }
        }
        
        $this->info('📈 By Status:');
        foreach ($byStatus as $status => $count) {
            $this->line("  • {$status}: {$count}");
        }
        
        if (!empty($byYear)) {
            $this->newLine();
            $this->info('📅 By Year:');
            ksort($byYear);
            foreach ($byYear as $year => $count) {
                $this->line("  • {$year}: {$count}");
            }
        }
        
        // Show detailed listing info
        $this->newLine();
        $this->info('📋 Detailed Listings:');
        
        foreach ($listings as $index => $listing) {
            $standardFields = $listing['StandardFields'] ?? [];
            
            $listingId = $standardFields['ListingId'] ?? 'N/A';
            $address = $standardFields['UnparsedAddress'] ?? 'N/A';
            $price = $standardFields['ListPrice'] ?? 0;
            $status = $standardFields['MlsStatus'] ?? 'Unknown';
            $agentName = $standardFields['ListAgentName'] ?? 'Unknown';
            $modTime = $standardFields['ModificationTimestamp'] ?? 'N/A';
            $source = $listing['_source_mls'] ?? 'Unknown MLS';
            
            $this->line(sprintf(
                "%d. MLS #%s | %s | $%s | %s | %s | %s",
                $index + 1,
                $listingId,
                substr($address, 0, 30) . (strlen($address) > 30 ? '...' : ''),
                number_format($price),
                $status,
                $agentName,
                $source
            ));
        }
    }
}
