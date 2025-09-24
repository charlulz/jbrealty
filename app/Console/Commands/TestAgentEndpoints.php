<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestAgentEndpoints extends Command
{
    protected $signature = 'flexmls:test-agent-endpoints';
    protected $description = 'Test My/Office/Company listings endpoints for Jeremiah Brown';

    public function handle()
    {
        $this->info('🔧 Testing Agent-Specific Endpoints');
        $this->info('===================================');
        
        $baseUrl = config('services.flexmls.base_url');
        $accessToken = config('services.flexmls.access_token');
        
        $this->info('🌐 API Endpoint: ' . $baseUrl);
        $this->info('🔑 Using Access Token: ' . substr($accessToken, 0, 10) . '...');
        $this->newLine();
        
        // Test each endpoint
        $endpoints = [
            'My Listings' => '/v1/my/listings',
            'Office Listings' => '/v1/office/listings', 
            'Company Listings' => '/v1/company/listings'
        ];
        
        foreach ($endpoints as $name => $endpoint) {
            $this->testEndpoint($name, $endpoint, $baseUrl, $accessToken);
            $this->newLine();
        }

        return Command::SUCCESS;
    }
    
    private function testEndpoint(string $name, string $endpoint, string $baseUrl, string $accessToken): void
    {
        $this->info("📋 Testing: {$name}");
        $this->line("   Endpoint: {$endpoint}");
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
            ])->timeout(30)->get($baseUrl . $endpoint, [
                '_limit' => 100,
                '_expand' => 'PrimaryPhoto'
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                $listings = $data['D']['Results'] ?? [];
                $count = count($listings);
                
                $this->line("   ✅ Success: Found {$count} listings");
                
                if ($count > 0) {
                    $this->line("   📊 Sample listings:");
                    
                    foreach (array_slice($listings, 0, 5) as $index => $listing) {
                        $standardFields = $listing['StandardFields'] ?? [];
                        
                        $listingId = $standardFields['ListingId'] ?? 'N/A';
                        $address = $standardFields['UnparsedAddress'] ?? 'N/A';
                        $price = $standardFields['ListPrice'] ?? 0;
                        $status = $standardFields['MlsStatus'] ?? 'Unknown';
                        $agentName = $standardFields['ListAgentName'] ?? 'Unknown';
                        $agentMlsId = $standardFields['ListAgentMlsId'] ?? 'N/A';
                        $officeName = $standardFields['ListOfficeName'] ?? 'Unknown';
                        $acres = $this->parseAcres($standardFields);
                        
                        $this->line("     " . ($index + 1) . ". MLS #{$listingId}: {$address}");
                        $this->line("        Price: $" . number_format($price) . " ({$status}) - {$acres} acres");
                        $this->line("        Agent: {$agentName} (ID: {$agentMlsId})");
                        $this->line("        Office: {$officeName}");
                    }
                    
                    if ($count > 5) {
                        $this->line("     ... and " . ($count - 5) . " more listings");
                    }
                    
                    // Check if these are Jeremiah Brown's listings
                    $jeremiahCount = 0;
                    foreach ($listings as $listing) {
                        if ($this->isJeremiahBrownListing($listing)) {
                            $jeremiahCount++;
                        }
                    }
                    
                    if ($jeremiahCount > 0) {
                        $this->info("   🎉 FOUND {$jeremiahCount} Jeremiah Brown listings in {$name}!");
                    } else {
                        $this->line("   ⚪ No Jeremiah Brown listings found (might be different agent's token)");
                    }
                }
                
            } else {
                $this->error("   ❌ Failed: HTTP " . $response->status());
                
                if ($response->status() === 401) {
                    $this->line("   🔐 Authentication issue - token might not be associated with Jeremiah Brown");
                } elseif ($response->status() === 403) {
                    $this->line("   🚫 Access forbidden - endpoint might require different permissions");
                } elseif ($response->status() === 404) {
                    $this->line("   🔍 Endpoint not found - might not be available in replication API");
                }
                
                $errorBody = $response->json();
                if (isset($errorBody['D']['Message'])) {
                    $this->line("   💬 Error: " . $errorBody['D']['Message']);
                }
            }
            
        } catch (\Exception $e) {
            $this->error("   ❌ Exception: " . $e->getMessage());
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
    
    private function isJeremiahBrownListing(array $listing): bool
    {
        $standardFields = $listing['StandardFields'] ?? [];
        
        $agentMlsId = $standardFields['ListAgentMlsId'] ?? '';
        $agentLicense = $standardFields['ListAgentStateLicense'] ?? '';
        $agentName = $standardFields['ListAgentName'] ?? '';
        $firstName = $standardFields['ListAgentFirstName'] ?? '';
        $lastName = $standardFields['ListAgentLastName'] ?? '';
        $officeName = $standardFields['ListOfficeName'] ?? '';
        
        // Known Jeremiah Brown identifiers
        $knownIds = ['20271', '429520271'];
        $knownLicense = '294658';
        
        // Check various identifiers
        if (in_array($agentMlsId, $knownIds) || 
            $agentLicense === $knownLicense ||
            (stripos($agentName, 'jeremiah') !== false && stripos($agentName, 'brown') !== false) ||
            (stripos($firstName, 'jeremiah') !== false && stripos($lastName, 'brown') !== false) ||
            stripos($officeName, 'jb land') !== false) {
            return true;
        }
        
        return false;
    }
}
