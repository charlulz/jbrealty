<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FocusedJeremiahSearch extends Command
{
    protected $signature = 'flexmls:focused-search
                            {--strategy=all : Search strategy (all, recent, property-type, geographic)}';

    protected $description = 'Focused search for more Jeremiah Brown listings using targeted strategies';

    public function handle()
    {
        $strategy = $this->option('strategy');
        
        $this->info('🎯 Focused Jeremiah Brown Search');
        $this->info('==============================');
        $this->info('📋 Strategy: ' . $strategy);
        $this->newLine();
        
        $baseUrl = config('services.flexmls.base_url');
        $accessToken = config('services.flexmls.access_token');
        
        $allListings = [];
        
        switch ($strategy) {
            case 'recent':
                $allListings = $this->searchRecentListings($baseUrl, $accessToken);
                break;
            case 'property-type':
                $allListings = $this->searchByPropertyType($baseUrl, $accessToken);
                break;
            case 'geographic':
                $allListings = $this->searchByLocation($baseUrl, $accessToken);
                break;
            case 'all':
            default:
                // Do all strategies but with smaller limits
                $this->info('🔄 Running all strategies with conservative limits...');
                $recent = $this->searchRecentListings($baseUrl, $accessToken, 1000);
                $propertyType = $this->searchByPropertyType($baseUrl, $accessToken, 800);
                $geographic = $this->searchByLocation($baseUrl, $accessToken, 600);
                
                $allListings = array_merge($recent, $propertyType, $geographic);
                break;
        }
        
        $uniqueListings = $this->removeDuplicates($allListings);
        $this->displayResults($uniqueListings);
        
        return Command::SUCCESS;
    }
    
    private function searchRecentListings(string $baseUrl, string $accessToken, int $limit = 2000): array
    {
        $this->info('📅 Strategy: Recent Listings (Last 3 Years)');
        
        $foundListings = [];
        $cutoffDate = date('Y-m-d', strtotime('-3 years'));
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
            ])->timeout(30)->get($baseUrl . '/v1/listings', [
                '_filter' => "ModificationTimestamp Ge {$cutoffDate}",
                '_limit' => $limit,
                '_expand' => 'PrimaryPhoto'
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                $allListings = $data['D']['Results'] ?? [];
                
                $this->line('   📊 Retrieved ' . count($allListings) . ' recent listings');
                
                foreach ($allListings as $listing) {
                    if ($this->isJeremiahBrownListing($listing)) {
                        $foundListings[] = $listing;
                    }
                }
                
                $this->line('   🎉 Found ' . count($foundListings) . ' Jeremiah listings');
            }
            
        } catch (\Exception $e) {
            $this->error('   ❌ Error: ' . $e->getMessage());
        }
        
        return $foundListings;
    }
    
    private function searchByPropertyType(string $baseUrl, string $accessToken, int $limit = 1500): array
    {
        $this->info('🏞️  Strategy: Land/Farm/Acreage Property Types');
        
        $foundListings = [];
        
        // Focus on land types that Jeremiah specializes in
        $propertyFilters = [
            "PropertyType Eq 'A'", // Acreage
            "PropertyType Eq 'L'", // Land
            "PropertyType Eq 'F'", // Farm
            "PropertyTypeLabel Ct 'Land'",
            "PropertyTypeLabel Ct 'Farm'",
            "PropertyTypeLabel Ct 'Acre'"
        ];
        
        foreach ($propertyFilters as $filter) {
            $this->line('   🔍 Searching: ' . $filter);
            
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => 'application/json',
                ])->timeout(25)->get($baseUrl . '/v1/listings', [
                    '_filter' => $filter,
                    '_limit' => min(500, $limit / count($propertyFilters)),
                    '_expand' => 'PrimaryPhoto'
                ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    $listings = $data['D']['Results'] ?? [];
                    
                    $jeremiahCount = 0;
                    foreach ($listings as $listing) {
                        if ($this->isJeremiahBrownListing($listing)) {
                            $foundListings[] = $listing;
                            $jeremiahCount++;
                        }
                    }
                    
                    if ($jeremiahCount > 0) {
                        $this->line('     ✅ Found ' . $jeremiahCount . ' Jeremiah listings');
                    }
                }
                
            } catch (\Exception $e) {
                $this->line('     ❌ Error: ' . $e->getMessage());
            }
            
            usleep(200000); // 0.2 second delay
        }
        
        return $foundListings;
    }
    
    private function searchByLocation(string $baseUrl, string $accessToken, int $limit = 1000): array
    {
        $this->info('📍 Strategy: Kentucky Geographic Areas');
        
        $foundListings = [];
        
        // Kentucky areas where Jeremiah operates
        $locationFilters = [
            "StateOrProvince Eq 'KY'",
            "StateOrProvince Eq 'Kentucky'", 
            "City Eq 'Morehead'",
            "City Eq 'Ewing'",
            "CountyOrParish Ct 'Carter'",
            "CountyOrParish Ct 'Rowan'",
            "CountyOrParish Ct 'Fleming'",
            "CountyOrParish Ct 'Lewis'"
        ];
        
        foreach ($locationFilters as $filter) {
            $this->line('   🔍 Searching: ' . $filter);
            
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => 'application/json',
                ])->timeout(20)->get($baseUrl . '/v1/listings', [
                    '_filter' => $filter,
                    '_limit' => min(300, $limit / count($locationFilters)),
                    '_expand' => 'PrimaryPhoto'
                ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    $listings = $data['D']['Results'] ?? [];
                    
                    $jeremiahCount = 0;
                    foreach ($listings as $listing) {
                        if ($this->isJeremiahBrownListing($listing)) {
                            $foundListings[] = $listing;
                            $jeremiahCount++;
                        }
                    }
                    
                    if ($jeremiahCount > 0) {
                        $this->line('     ✅ Found ' . $jeremiahCount . ' Jeremiah listings');
                    }
                }
                
            } catch (\Exception $e) {
                $this->line('     ❌ Error: ' . $e->getMessage());
            }
            
            usleep(200000); // 0.2 second delay
        }
        
        return $foundListings;
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
    
    private function displayResults(array $listings): void
    {
        $this->newLine();
        $this->info('🏆 FOCUSED SEARCH RESULTS');
        $this->info('=========================');
        
        if (empty($listings)) {
            $this->warn('No additional listings found with focused strategies.');
            return;
        }
        
        $this->info('📊 Total unique Jeremiah Brown listings: ' . count($listings));
        
        if (count($listings) >= 25) {
            $this->info('🎉 SUCCESS! Found the ~30 listings you expected!');
        } elseif (count($listings) > 2) {
            $this->info('✅ Found more listings than our initial search (' . count($listings) . ' vs 2)');
        }
        
        $this->newLine();
        $this->info('📋 Detailed Listings:');
        
        foreach ($listings as $index => $listing) {
            $standardFields = $listing['StandardFields'] ?? [];
            
            $listingId = $standardFields['ListingId'] ?? 'N/A';
            $address = $standardFields['UnparsedAddress'] ?? 'N/A';
            $price = $standardFields['ListPrice'] ?? 0;
            $status = $standardFields['MlsStatus'] ?? 'Unknown';
            $agentName = $standardFields['ListAgentName'] ?? 'Unknown';
            $agentMlsId = $standardFields['ListAgentMlsId'] ?? 'N/A';
            $propertyType = $standardFields['PropertyType'] ?? 'N/A';
            $acres = $this->parseAcres($standardFields);
            
            $this->line(sprintf(
                "%d. MLS #%s | %s | $%s | %s | %s acres | %s (%s)",
                $index + 1,
                $listingId,
                substr($address, 0, 35) . (strlen($address) > 35 ? '...' : ''),
                number_format($price),
                $status,
                $acres,
                $agentName,
                $agentMlsId
            ));
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
}
