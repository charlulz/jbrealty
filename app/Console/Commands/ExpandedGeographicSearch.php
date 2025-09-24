<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ExpandedGeographicSearch extends Command
{
    protected $signature = 'flexmls:expanded-geographic-search
                            {--discover-mls : Show which MLS systems serve each area}';

    protected $description = 'Search expanded geographic areas where Jeremiah Brown operates (Carter County, Grayson, etc.)';

    public function handle()
    {
        $this->info('🗺️  EXPANDED Geographic Search for Jeremiah Brown');
        $this->info('===============================================');
        
        $baseUrl = config('services.flexmls.base_url');
        $accessToken = config('services.flexmls.access_token');
        
        // Expanded geographic areas where Jeremiah operates
        $searchAreas = [
            // Original areas
            'Morehead' => ['type' => 'city', 'filter' => "City Eq 'Morehead'"],
            'Ewing' => ['type' => 'city', 'filter' => "City Eq 'Ewing'"],
            
            // New areas from user
            'Carter County' => ['type' => 'county', 'filter' => "CountyOrParish Ct 'Carter'"],
            'Grayson' => ['type' => 'city', 'filter' => "City Eq 'Grayson'"],
            
            // Additional Kentucky counties/cities that might be relevant
            'Fleming County' => ['type' => 'county', 'filter' => "CountyOrParish Ct 'Fleming'"],
            'Rowan County' => ['type' => 'county', 'filter' => "CountyOrParish Ct 'Rowan'"],
            'Lewis County' => ['type' => 'county', 'filter' => "CountyOrParish Ct 'Lewis'"],
            'Olive Hill' => ['type' => 'city', 'filter' => "City Eq 'Olive Hill'"],
            'Vanceburg' => ['type' => 'city', 'filter' => "City Eq 'Vanceburg'"],
            'Maysville' => ['type' => 'city', 'filter' => "City Eq 'Maysville'"],
            
            // Broader Kentucky search (in case city names are inconsistent)
            'Eastern Kentucky' => ['type' => 'region', 'filter' => "StateOrProvince Eq 'KY'"],
        ];
        
        $allJeremiahListings = [];
        $mlsSystemData = [];
        
        foreach ($searchAreas as $areaName => $areaData) {
            $this->info("🔍 Searching: {$areaName} ({$areaData['type']})");
            
            $searchResults = $this->searchArea($baseUrl, $accessToken, $areaName, $areaData, $this->option('discover-mls'));
            
            if (!empty($searchResults['listings'])) {
                $jeremiahCount = count($searchResults['listings']);
                $this->info("   🎉 Found {$jeremiahCount} Jeremiah Brown listings!");
                
                foreach ($searchResults['listings'] as $listing) {
                    $standardFields = $listing['StandardFields'] ?? [];
                    $listingId = $standardFields['ListingId'] ?? 'N/A';
                    $address = $standardFields['UnparsedAddress'] ?? 'N/A';
                    $price = $standardFields['ListPrice'] ?? 0;
                    $status = $standardFields['MlsStatus'] ?? 'Unknown';
                    
                    $this->line("     • MLS #{$listingId}: {$address} - $" . number_format($price) . " ({$status})");
                }
                
                $allJeremiahListings = array_merge($allJeremiahListings, $searchResults['listings']);
            } else {
                $this->line("   ⚪ No Jeremiah Brown listings found");
            }
            
            // Track MLS system data
            if (!empty($searchResults['mls_info'])) {
                $mlsSystemData[$areaName] = $searchResults['mls_info'];
            }
            
            $this->newLine();
            usleep(300000); // 0.3 second delay
        }
        
        // Remove duplicates
        $uniqueListings = $this->removeDuplicates($allJeremiahListings);
        
        $this->displayResults($uniqueListings, $mlsSystemData);
        
        return Command::SUCCESS;
    }
    
    private function searchArea(string $baseUrl, string $accessToken, string $areaName, array $areaData, bool $discoverMls): array
    {
        $jeremiahListings = [];
        $mlsInfo = [];
        
        try {
            $params = [
                '_filter' => $areaData['filter'],
                '_limit' => ($areaName === 'Eastern Kentucky') ? 2000 : 500, // Higher limit for broad search
                '_expand' => 'PrimaryPhoto'
            ];
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
            ])->timeout(45)->get($baseUrl . '/v1/listings', $params);
            
            if ($response->successful()) {
                $data = $response->json();
                $allListings = $data['D']['Results'] ?? [];
                
                $this->line("   📊 Retrieved " . count($allListings) . " total listings");
                
                // Analyze MLS systems if requested
                if ($discoverMls && !empty($allListings)) {
                    $mlsIds = [];
                    foreach (array_slice($allListings, 0, 20) as $listing) { // Sample first 20
                        $mlsId = $listing['StandardFields']['MlsId'] ?? 'Unknown';
                        $mlsIds[$mlsId] = ($mlsIds[$mlsId] ?? 0) + 1;
                    }
                    
                    $this->line("   🏢 MLS Systems serving this area:");
                    foreach ($mlsIds as $mlsId => $count) {
                        $this->line("     - {$mlsId}: {$count} listings sampled");
                    }
                    
                    $mlsInfo['mls_systems'] = array_keys($mlsIds);
                }
                
                // Filter for Jeremiah Brown
                foreach ($allListings as $listing) {
                    if ($this->isJeremiahBrownListing($listing)) {
                        // Add source area info
                        $listing['_search_area'] = $areaName;
                        $listing['_search_type'] = $areaData['type'];
                        $jeremiahListings[] = $listing;
                    }
                }
                
            } else {
                $this->error("   ❌ API Error: HTTP " . $response->status());
                
                // Try simplified filter if the complex one fails
                if (strpos($areaData['filter'], 'Ct') !== false) {
                    $this->line("   🔄 Trying simplified filter...");
                    
                    $simplifiedFilter = str_replace(' Ct ', ' Eq ', $areaData['filter']);
                    $simplifiedFilter = str_replace("'Carter'", "'Carter County'", $simplifiedFilter);
                    
                    $params['_filter'] = $simplifiedFilter;
                    
                    $response2 = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $accessToken,
                        'Accept' => 'application/json',
                    ])->timeout(45)->get($baseUrl . '/v1/listings', $params);
                    
                    if ($response2->successful()) {
                        $data = $response2->json();
                        $allListings = $data['D']['Results'] ?? [];
                        $this->line("   ✅ Simplified filter worked: " . count($allListings) . " listings");
                        
                        foreach ($allListings as $listing) {
                            if ($this->isJeremiahBrownListing($listing)) {
                                $listing['_search_area'] = $areaName . ' (simplified)';
                                $listing['_search_type'] = $areaData['type'];
                                $jeremiahListings[] = $listing;
                            }
                        }
                    }
                }
            }
            
        } catch (\Exception $e) {
            $this->error("   ❌ Exception: " . $e->getMessage());
        }
        
        return [
            'listings' => $jeremiahListings,
            'mls_info' => $mlsInfo
        ];
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
        
        // Check all possible matches
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
    
    private function displayResults(array $listings, array $mlsSystemData): void
    {
        $this->newLine();
        $this->info('🏆 EXPANDED SEARCH RESULTS');
        $this->info('=========================');
        
        $this->info('📊 Total unique Jeremiah Brown listings found: ' . count($listings));
        
        if (count($listings) >= 25) {
            $this->info('🎉 SUCCESS! Found the ~30 listings you expected!');
        } elseif (count($listings) > 5) {
            $this->info('✅ Found more listings than before (' . count($listings) . ' vs 5)');
        }
        
        if (empty($listings)) {
            $this->warn('Still not finding enough listings. Consider:');
            $this->line('• Different MLS systems serving Carter County/Grayson');
            $this->line('• Historical listings that are no longer active');
            $this->line('• Different agent name variations or office names');
            return;
        }
        
        $this->newLine();
        
        // Group by search area
        $byArea = [];
        foreach ($listings as $listing) {
            $area = $listing['_search_area'] ?? 'Unknown';
            $byArea[$area] = ($byArea[$area] ?? 0) + 1;
        }
        
        $this->info('📍 Listings by Search Area:');
        foreach ($byArea as $area => $count) {
            $this->line("  • {$area}: {$count} listings");
        }
        
        $this->newLine();
        
        // Group by status
        $byStatus = [];
        foreach ($listings as $listing) {
            $status = $listing['StandardFields']['MlsStatus'] ?? 'Unknown';
            $byStatus[$status] = ($byStatus[$status] ?? 0) + 1;
        }
        
        $this->info('📊 Listings by Status:');
        foreach ($byStatus as $status => $count) {
            $this->line("  • {$status}: {$count}");
        }
        
        $this->newLine();
        
        // Show recent listings first
        $this->info('📋 All Jeremiah Brown Listings Found:');
        
        usort($listings, function($a, $b) {
            $aDate = $a['StandardFields']['ModificationTimestamp'] ?? '';
            $bDate = $b['StandardFields']['ModificationTimestamp'] ?? '';
            return $bDate <=> $aDate; // Most recent first
        });
        
        foreach ($listings as $index => $listing) {
            $standardFields = $listing['StandardFields'] ?? [];
            
            $listingId = $standardFields['ListingId'] ?? 'N/A';
            $address = $standardFields['UnparsedAddress'] ?? 'N/A';
            $price = $standardFields['ListPrice'] ?? 0;
            $status = $standardFields['MlsStatus'] ?? 'Unknown';
            $city = $standardFields['City'] ?? 'Unknown';
            $county = $standardFields['CountyOrParish'] ?? '';
            $searchArea = $listing['_search_area'] ?? 'Unknown';
            
            $location = $city;
            if ($county && $county !== $city) {
                $location .= ", {$county}";
            }
            
            $this->line(sprintf(
                "%2d. MLS #%s | %s | $%s | %s | Found via: %s",
                $index + 1,
                $listingId,
                substr($location, 0, 25) . (strlen($location) > 25 ? '...' : ''),
                number_format($price),
                $status,
                $searchArea
            ));
        }
    }
}
