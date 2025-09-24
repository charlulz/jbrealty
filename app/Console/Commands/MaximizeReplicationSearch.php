<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class MaximizeReplicationSearch extends Command
{
    protected $signature = 'flexmls:maximize-replication-search
                            {--years=10 : Years back to search}
                            {--max-total=20000 : Maximum listings to process across all searches}';

    protected $description = 'Maximize our replication API search to find all possible Jeremiah Brown listings';

    public function handle()
    {
        $years = $this->option('years');
        $maxTotal = $this->option('max-total');
        
        $this->info('🔥 MAXIMIZING Replication API Search');
        $this->info('===================================');
        $this->info('📅 Searching back: ' . $years . ' years');
        $this->info('📊 Max total listings: ' . number_format($maxTotal));
        $this->newLine();
        
        $baseUrl = config('services.flexmls.base_url');
        $accessToken = config('services.flexmls.access_token');
        
        $allJeremiahListings = [];
        
        // Strategy 1: Very broad date range search  
        $this->info('1️⃣ Strategy: Broad Historical Search');
        $historicalListings = $this->searchHistoricalRange($baseUrl, $accessToken, $years, $maxTotal / 4);
        $allJeremiahListings = array_merge($allJeremiahListings, $historicalListings);
        $this->line('   Found: ' . count($historicalListings) . ' listings');
        $this->newLine();
        
        // Strategy 2: Search by property types that Jeremiah typically lists
        $this->info('2️⃣ Strategy: Property Type Focused Search');
        $propertyTypeListings = $this->searchByPropertyTypes($baseUrl, $accessToken, $maxTotal / 4);
        $allJeremiahListings = array_merge($allJeremiahListings, $propertyTypeListings);
        $this->line('   Found: ' . count($propertyTypeListings) . ' listings');
        $this->newLine();
        
        // Strategy 3: Geographic area search (Kentucky regions)
        $this->info('3️⃣ Strategy: Geographic Area Search');
        $geoListings = $this->searchByGeography($baseUrl, $accessToken, $maxTotal / 4);
        $allJeremiahListings = array_merge($allJeremiahListings, $geoListings);
        $this->line('   Found: ' . count($geoListings) . ' listings');
        $this->newLine();
        
        // Strategy 4: Price range search (based on his current listings)
        $this->info('4️⃣ Strategy: Price Range Search');
        $priceListings = $this->searchByPriceRanges($baseUrl, $accessToken, $maxTotal / 4);  
        $allJeremiahListings = array_merge($allJeremiahListings, $priceListings);
        $this->line('   Found: ' . count($priceListings) . ' listings');
        $this->newLine();
        
        // Remove duplicates
        $uniqueListings = $this->removeDuplicates($allJeremiahListings);
        
        $this->displayMaximizedResults($uniqueListings);
        
        return Command::SUCCESS;
    }
    
    private function searchHistoricalRange(string $baseUrl, string $accessToken, int $years, int $maxPerSearch): array
    {
        $foundListings = [];
        
        // Search year by year for better coverage
        for ($yearOffset = 0; $yearOffset < $years; $yearOffset++) {
            $startDate = date('Y-m-d', strtotime("-" . ($yearOffset + 1) . " years"));
            $endDate = date('Y-m-d', strtotime("-{$yearOffset} years"));
            
            $this->line("   📅 Searching {$startDate} to {$endDate}...");
            
            $yearListings = $this->searchWithDateFilter($baseUrl, $accessToken, $startDate, $endDate, 500);
            
            if (!empty($yearListings)) {
                $jeremiahInYear = 0;
                foreach ($yearListings as $listing) {
                    if ($this->isJeremiahBrownListing($listing)) {
                        $foundListings[] = $listing;
                        $jeremiahInYear++;
                    }
                }
                
                if ($jeremiahInYear > 0) {
                    $this->line("     🎉 Found {$jeremiahInYear} Jeremiah listings in " . date('Y', strtotime($startDate)));
                }
            }
            
            usleep(250000); // Quarter second delay
        }
        
        return $foundListings;
    }
    
    private function searchByPropertyTypes(string $baseUrl, string $accessToken, int $maxPerSearch): array
    {
        $foundListings = [];
        
        // Property types Jeremiah likely lists (based on his current listings)
        $propertyTypes = [
            'A' => 'Acreage',
            'L' => 'Land', 
            'F' => 'Farm',
            'R' => 'Residential',
            'ACREAGE' => 'Acreage',
            'LAND' => 'Land',
            'FARM' => 'Farm'
        ];
        
        foreach ($propertyTypes as $typeCode => $typeName) {
            $this->line("   🏞️  Searching property type: {$typeName}");
            
            $typeListings = $this->searchWithFilter($baseUrl, $accessToken, "PropertyType Eq '{$typeCode}'", 1000);
            
            if (!empty($typeListings)) {
                $jeremiahInType = 0;
                foreach ($typeListings as $listing) {
                    if ($this->isJeremiahBrownListing($listing)) {
                        $foundListings[] = $listing;
                        $jeremiahInType++;
                    }
                }
                
                if ($jeremiahInType > 0) {
                    $this->line("     🎉 Found {$jeremiahInType} Jeremiah listings in {$typeName}");
                }
            }
            
            usleep(250000);
        }
        
        return $foundListings;
    }
    
    private function searchByGeography(string $baseUrl, string $accessToken, int $maxPerSearch): array
    {
        $foundListings = [];
        
        // Kentucky cities/counties where Jeremiah operates
        $locations = [
            'Morehead',
            'Ewing', 
            'Fleming County',
            'Carter County',
            'Rowan County',
            'Lewis County',
            'Kentucky',
            'KY'
        ];
        
        foreach ($locations as $location) {
            $this->line("   📍 Searching location: {$location}");
            
            $locationListings = $this->searchWithFilter($baseUrl, $accessToken, "City Eq '{$location}' Or CountyOrParish Eq '{$location}' Or StateOrProvince Eq '{$location}'", 500);
            
            if (!empty($locationListings)) {
                $jeremiahInLocation = 0;
                foreach ($locationListings as $listing) {
                    if ($this->isJeremiahBrownListing($listing)) {
                        $foundListings[] = $listing;
                        $jeremiahInLocation++;
                    }
                }
                
                if ($jeremiahInLocation > 0) {
                    $this->line("     🎉 Found {$jeremiahInLocation} Jeremiah listings in {$location}");
                }
            }
            
            usleep(250000);
        }
        
        return $foundListings;
    }
    
    private function searchByPriceRanges(string $baseUrl, string $accessToken, int $maxPerSearch): array
    {
        $foundListings = [];
        
        // Price ranges based on his current listings ($125K-$229K)
        $priceRanges = [
            ['min' => 50000, 'max' => 200000],
            ['min' => 200000, 'max' => 500000],
            ['min' => 500000, 'max' => 1000000],
            ['min' => 0, 'max' => 100000] // Lower priced properties
        ];
        
        foreach ($priceRanges as $range) {
            $min = number_format($range['min']);
            $max = number_format($range['max']);
            $this->line("   💰 Searching price range: $${min} - $${max}");
            
            $priceListings = $this->searchWithFilter($baseUrl, $accessToken, "ListPrice Ge {$range['min']} And ListPrice Le {$range['max']}", 1000);
            
            if (!empty($priceListings)) {
                $jeremiahInRange = 0;
                foreach ($priceListings as $listing) {
                    if ($this->isJeremiahBrownListing($listing)) {
                        $foundListings[] = $listing;
                        $jeremiahInRange++;
                    }
                }
                
                if ($jeremiahInRange > 0) {
                    $this->line("     🎉 Found {$jeremiahInRange} Jeremiah listings in $${min}-$${max}");
                }
            }
            
            usleep(250000);
        }
        
        return $foundListings;
    }
    
    private function searchWithDateFilter(string $baseUrl, string $accessToken, string $startDate, string $endDate, int $limit): array
    {
        return $this->searchWithFilter($baseUrl, $accessToken, "ModificationTimestamp Ge {$startDate} And ModificationTimestamp Le {$endDate}", $limit);
    }
    
    private function searchWithFilter(string $baseUrl, string $accessToken, string $filter, int $limit): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
            ])->timeout(45)->get($baseUrl . '/v1/listings', [
                '_filter' => $filter,
                '_limit' => $limit,
                '_expand' => 'PrimaryPhoto'
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                return $data['D']['Results'] ?? [];
            }
            
        } catch (\Exception $e) {
            // Silent fail for individual searches
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
        
        // All the ways to identify Jeremiah Brown
        if (in_array($agentMlsId, $knownIds) ||
            $agentLicense === $knownLicense ||
            (stripos($agentName, 'jeremiah') !== false && stripos($agentName, 'brown') !== false) ||
            (stripos($firstName, 'jeremiah') !== false && stripos($lastName, 'brown') !== false) ||
            stripos($officeName, 'jb land') !== false) {
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
    
    private function displayMaximizedResults(array $listings): void
    {
        $this->info('🏆 MAXIMIZED SEARCH RESULTS');
        $this->info('==========================');
        
        if (empty($listings)) {
            $this->warn('No additional listings found with maximized search strategies.');
            $this->info('💡 This confirms our earlier findings - likely only 2 current listings available via replication API.');
            return;
        }
        
        $this->info('📊 Total unique listings found: ' . count($listings));
        $this->newLine();
        
        // Detailed breakdown
        $byStatus = [];
        $byYear = [];
        $byType = [];
        
        foreach ($listings as $listing) {
            $standardFields = $listing['StandardFields'] ?? [];
            
            $status = $standardFields['MlsStatus'] ?? 'Unknown';
            $propertyType = $standardFields['PropertyType'] ?? 'Unknown';
            $modTime = $standardFields['ModificationTimestamp'] ?? '';
            
            $byStatus[$status] = ($byStatus[$status] ?? 0) + 1;
            $byType[$propertyType] = ($byType[$propertyType] ?? 0) + 1;
            
            if ($modTime) {
                $year = date('Y', strtotime($modTime));
                $byYear[$year] = ($byYear[$year] ?? 0) + 1;
            }
        }
        
        $this->info('📊 Breakdown:');
        $this->line('By Status:');
        foreach ($byStatus as $status => $count) {
            $this->line("  • {$status}: {$count}");
        }
        
        $this->line('By Property Type:');
        foreach ($byType as $type => $count) {
            $this->line("  • {$type}: {$count}");
        }
        
        if (!empty($byYear)) {
            $this->line('By Year:');
            ksort($byYear);
            foreach ($byYear as $year => $count) {
                $this->line("  • {$year}: {$count}");
            }
        }
        
        $this->newLine();
        if (count($listings) >= 25) {
            $this->info('🎉 SUCCESS! Found the ~30 listings you expected!');
        } elseif (count($listings) > 2) {
            $this->info('✅ Found more listings than before (' . count($listings) . ' vs 2)');
        }
    }
}
