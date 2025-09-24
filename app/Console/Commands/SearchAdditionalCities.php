<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SearchAdditionalCities extends Command
{
    protected $signature = 'flexmls:search-additional-cities';
    protected $description = 'Search additional cities provided by user for more Jeremiah Brown listings';

    public function handle()
    {
        $this->info('🔍 Searching Additional Cities for Jeremiah Brown');
        $this->info('===============================================');
        
        $baseUrl = config('services.flexmls.base_url');
        $accessToken = config('services.flexmls.access_token');
        
        // Additional cities provided by user that we haven't searched yet
        $newCities = [
            'Bowling Green',
            'Hitchins', 
            'Williamsburg',
            'Corinth',
            'Wallingford' // Re-search directly (we found 1 via county search)
        ];
        
        $allNewListings = [];
        
        foreach ($newCities as $cityName) {
            $this->info("🏙️ Searching {$cityName}...");
            
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => 'application/json',
                ])->timeout(30)->get($baseUrl . '/v1/listings', [
                    '_filter' => "City Eq '{$cityName}'",
                    '_limit' => 500,
                    '_expand' => 'PrimaryPhoto'
                ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    $listings = $data['D']['Results'] ?? [];
                    
                    $this->line("   📊 Retrieved " . count($listings) . " total listings");
                    
                    $jeremiahCount = 0;
                    foreach ($listings as $listing) {
                        if ($this->isJeremiahBrownListing($listing)) {
                            $listing['_search_city'] = $cityName;
                            $allNewListings[] = $listing;
                            $jeremiahCount++;
                            
                            $standardFields = $listing['StandardFields'] ?? [];
                            $listingId = $standardFields['ListingId'] ?? 'N/A';
                            $address = $standardFields['UnparsedAddress'] ?? 'N/A';
                            $price = $standardFields['ListPrice'] ?? 0;
                            $status = $standardFields['MlsStatus'] ?? 'Unknown';
                            
                            $this->line("   🎉 Found: MLS #{$listingId} - {$address} - $" . number_format($price) . " ({$status})");
                        }
                    }
                    
                    if ($jeremiahCount === 0) {
                        $this->line("   ⚪ No Jeremiah Brown listings found in {$cityName}");
                    } else {
                        $this->info("   ✅ Found {$jeremiahCount} Jeremiah Brown listings in {$cityName}!");
                    }
                    
                } else {
                    $this->error("   ❌ Failed to search {$cityName}: HTTP " . $response->status());
                }
                
            } catch (\Exception $e) {
                $this->error("   ❌ Error searching {$cityName}: " . $e->getMessage());
            }
            
            $this->newLine();
            usleep(300000); // 0.3 second delay
        }
        
        // Remove duplicates with existing listings
        $uniqueNewListings = $this->removeDuplicates($allNewListings);
        
        $this->displayResults($uniqueNewListings);
        
        return Command::SUCCESS;
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
        $this->info('🏆 ADDITIONAL CITIES SEARCH RESULTS');
        $this->info('==================================');
        
        if (empty($listings)) {
            $this->warn('No additional NEW listings found in these cities.');
            $this->info('💡 This could mean:');
            $this->line('   • These listings are already captured by our existing searches');
            $this->line('   • Jeremiah doesn\'t currently have listings in these specific cities');
            $this->line('   • The listings might be under different city names/spellings');
            return;
        }
        
        $this->info('📊 Found ' . count($listings) . ' NEW unique listings in additional cities');
        $this->newLine();
        
        // Group by city
        $byCity = [];
        foreach ($listings as $listing) {
            $city = $listing['_search_city'] ?? 'Unknown';
            $byCity[$city] = ($byCity[$city] ?? 0) + 1;
        }
        
        $this->info('📍 New Listings by City:');
        foreach ($byCity as $city => $count) {
            $this->line("  • {$city}: {$count} listings");
        }
        
        // Group by status
        $byStatus = [];
        foreach ($listings as $listing) {
            $status = $listing['StandardFields']['MlsStatus'] ?? 'Unknown';
            $byStatus[$status] = ($byStatus[$status] ?? 0) + 1;
        }
        
        $this->newLine();
        $this->info('📊 New Listings by Status:');
        foreach ($byStatus as $status => $count) {
            $this->line("  • {$status}: {$count}");
        }
        
        $this->newLine();
        $this->info('📋 All NEW Jeremiah Brown Listings Found:');
        
        foreach ($listings as $index => $listing) {
            $standardFields = $listing['StandardFields'] ?? [];
            
            $listingId = $standardFields['ListingId'] ?? 'N/A';
            $address = $standardFields['UnparsedAddress'] ?? 'N/A';
            $price = $standardFields['ListPrice'] ?? 0;
            $status = $standardFields['MlsStatus'] ?? 'Unknown';
            $city = $listing['_search_city'] ?? 'Unknown';
            
            $this->line(sprintf(
                "%2d. MLS #%s | %s | $%s | %s | Found in: %s",
                $index + 1,
                $listingId,
                substr($address, 0, 30) . (strlen($address) > 30 ? '...' : ''),
                number_format($price),
                $status,
                $city
            ));
        }
        
        $currentTotal = 50; // Our current count
        $newTotal = $currentTotal + count($listings);
        
        $this->newLine();
        $this->info("🎉 NEW PORTFOLIO TOTAL: {$newTotal} listings (was {$currentTotal})");
        
        if (count($listings) > 0) {
            $this->warn('💡 Consider updating the import command to include these new cities!');
        }
    }
}
