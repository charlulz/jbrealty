<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FindRemainingListings extends Command
{
    protected $signature = 'flexmls:find-remaining-listings
                            {--deep-historical : Search deeper historical records}
                            {--alternative-names : Try alternative agent name variations}
                            {--broader-geographic : Search broader geographic areas}';

    protected $description = 'Try to find the remaining ~16 Jeremiah Brown listings to reach the expected ~30';

    public function handle()
    {
        $this->info('🔍 FINDING REMAINING Jeremiah Brown Listings');
        $this->info('===========================================');
        $this->info('🎯 Goal: Find remaining ~16 listings to reach ~30 total');
        $this->newLine();

        $baseUrl = config('services.flexmls.base_url');
        $accessToken = config('services.flexmls.access_token');

        $allFoundListings = [];

        // Strategy 1: Deep Historical Search
        if ($this->option('deep-historical') || !$this->hasOptions()) {
            $this->info('1️⃣ DEEP HISTORICAL SEARCH (5+ years)');
            $historicalListings = $this->searchDeepHistorical($baseUrl, $accessToken);
            $allFoundListings = array_merge($allFoundListings, $historicalListings);
            $this->line('   Found: ' . count($historicalListings) . ' historical listings');
            $this->newLine();
        }

        // Strategy 2: Alternative Agent Names/Variations
        if ($this->option('alternative-names') || !$this->hasOptions()) {
            $this->info('2️⃣ ALTERNATIVE AGENT NAME VARIATIONS');
            $nameVariationListings = $this->searchNameVariations($baseUrl, $accessToken);
            $allFoundListings = array_merge($allFoundListings, $nameVariationListings);
            $this->line('   Found: ' . count($nameVariationListings) . ' listings with name variations');
            $this->newLine();
        }

        // Strategy 3: Broader Geographic Search
        if ($this->option('broader-geographic') || !$this->hasOptions()) {
            $this->info('3️⃣ BROADER GEOGRAPHIC COVERAGE');
            $broaderListings = $this->searchBroaderAreas($baseUrl, $accessToken);
            $allFoundListings = array_merge($allFoundListings, $broaderListings);
            $this->line('   Found: ' . count($broaderListings) . ' listings in broader areas');
            $this->newLine();
        }

        // Remove duplicates
        $uniqueListings = $this->removeDuplicates($allFoundListings);
        
        $this->displayResults($uniqueListings);

        return Command::SUCCESS;
    }

    private function hasOptions(): bool
    {
        return $this->option('deep-historical') || 
               $this->option('alternative-names') || 
               $this->option('broader-geographic');
    }

    private function searchDeepHistorical(string $baseUrl, string $accessToken): array
    {
        $foundListings = [];

        // Search by year going back further
        $years = [2019, 2020, 2021, 2022];
        
        foreach ($years as $year) {
            $this->line("   📅 Searching year {$year}...");
            
            $startDate = "{$year}-01-01";
            $endDate = "{$year}-12-31";
            
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => 'application/json',
                ])->timeout(30)->get($baseUrl . '/v1/listings', [
                    '_filter' => "ModificationTimestamp Ge {$startDate} And ModificationTimestamp Le {$endDate}",
                    '_limit' => 1000,
                    '_expand' => 'PrimaryPhoto'
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $listings = $data['D']['Results'] ?? [];
                    
                    $yearCount = 0;
                    foreach ($listings as $listing) {
                        if ($this->isJeremiahBrownListing($listing)) {
                            $foundListings[] = $listing;
                            $yearCount++;
                        }
                    }
                    
                    if ($yearCount > 0) {
                        $this->line("     ✅ Found {$yearCount} listings from {$year}");
                    }
                }

            } catch (\Exception $e) {
                $this->line("     ❌ Error searching {$year}");
            }

            usleep(500000); // 0.5 second delay
        }

        return $foundListings;
    }

    private function searchNameVariations(string $baseUrl, string $accessToken): array
    {
        $foundListings = [];

        // Try different agent name patterns that might be in the data
        $nameVariations = [
            'Jeremy Brown',
            'Jerry Brown', 
            'J Brown',
            'J. Brown',
            'JB',
            'Jeremiah',
            'Brown, Jeremiah',
            'Brown, J',
        ];

        foreach ($nameVariations as $nameVariation) {
            $this->line("   🔍 Searching for agent name: '{$nameVariation}'...");

            try {
                // Use broad search then filter (since direct agent name filtering often fails)
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => 'application/json',
                ])->timeout(25)->get($baseUrl . '/v1/listings', [
                    '_limit' => 1000,
                    '_expand' => 'PrimaryPhoto'
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $listings = $data['D']['Results'] ?? [];
                    
                    $nameCount = 0;
                    foreach ($listings as $listing) {
                        $standardFields = $listing['StandardFields'] ?? [];
                        $agentName = $standardFields['ListAgentName'] ?? '';
                        
                        // Check for this specific name variation
                        if (stripos($agentName, $nameVariation) !== false && 
                            $this->isJeremiahBrownListing($listing)) {
                            $foundListings[] = $listing;
                            $nameCount++;
                        }
                    }
                    
                    if ($nameCount > 0) {
                        $this->line("     ✅ Found {$nameCount} listings with name '{$nameVariation}'");
                    }
                }

            } catch (\Exception $e) {
                $this->line("     ❌ Error searching name '{$nameVariation}'");
            }

            usleep(300000); // 0.3 second delay
        }

        return $foundListings;
    }

    private function searchBroaderAreas(string $baseUrl, string $accessToken): array
    {
        $foundListings = [];

        // Try additional Kentucky cities/areas that might be relevant
        $additionalAreas = [
            'Ashland',
            'Paintsville',
            'Prestonsburg',
            'Hazard',
            'Jackson',
            'Beattyville',
            'Campton',
            'Frenchburg',
            'West Liberty',
            'Sandy Hook',
            'Carlisle',
            'Mount Sterling',
            'Paris',
            'Georgetown',
        ];

        foreach ($additionalAreas as $area) {
            $this->line("   🗺️ Searching {$area}...");

            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => 'application/json',
                ])->timeout(20)->get($baseUrl . '/v1/listings', [
                    '_filter' => "City Eq '{$area}'",
                    '_limit' => 300,
                    '_expand' => 'PrimaryPhoto'
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $listings = $data['D']['Results'] ?? [];
                    
                    $areaCount = 0;
                    foreach ($listings as $listing) {
                        if ($this->isJeremiahBrownListing($listing)) {
                            $foundListings[] = $listing;
                            $areaCount++;
                            
                            $standardFields = $listing['StandardFields'] ?? [];
                            $listingId = $standardFields['ListingId'] ?? 'N/A';
                            $address = $standardFields['UnparsedAddress'] ?? 'N/A';
                            $this->line("     🎉 Found: MLS #{$listingId} - {$address}");
                        }
                    }
                    
                    if ($areaCount === 0) {
                        // Only show this for areas where we got data but no matches
                        // $this->line("     ⚪ No Jeremiah listings in {$area}");
                    }
                }

            } catch (\Exception $e) {
                // Silent fail for broader searches
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
        $this->info('🏆 ADDITIONAL LISTINGS SEARCH RESULTS');
        $this->info('====================================');
        
        if (empty($listings)) {
            $this->warn('No additional listings found with these strategies.');
            $this->info('💡 This suggests:');
            $this->line('   • The 14 listings we found might be most of his active/recent listings');
            $this->line('   • The remaining ~16 might be very old or in different systems');
            $this->line('   • Different agent variations we haven\'t tried');
            return;
        }

        $this->info('📊 Found ' . count($listings) . ' additional unique listings');
        $this->newLine();

        // Show breakdown
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

        $this->info('📊 By Status:');
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

        $this->newLine();
        $this->info('📋 Additional Listings Found:');
        
        foreach ($listings as $index => $listing) {
            $standardFields = $listing['StandardFields'] ?? [];
            
            $listingId = $standardFields['ListingId'] ?? 'N/A';
            $address = $standardFields['UnparsedAddress'] ?? 'N/A';
            $price = $standardFields['ListPrice'] ?? 0;
            $status = $standardFields['MlsStatus'] ?? 'Unknown';
            $city = $standardFields['City'] ?? 'Unknown';

            $this->line(sprintf(
                "%2d. MLS #%s | %s | $%s | %s",
                $index + 1,
                $listingId,
                substr("{$city} - {$address}", 0, 35) . '...',
                number_format($price),
                $status
            ));
        }
    }
}
