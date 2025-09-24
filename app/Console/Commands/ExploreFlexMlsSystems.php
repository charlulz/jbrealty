<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FlexMlsApiService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExploreFlexMlsSystems extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'flexmls:explore-systems
                            {--system-id= : Specific MLS system ID to query}
                            {--search-member=20271 : Member ID to search for across systems}';

    /**
     * The console command description.
     */
    protected $description = 'Explore different MLS systems/boards to find Jeremiah Brown listings';

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
        $this->info('🔍 Exploring FlexMLS Systems and Boards');
        $this->info('=====================================');
        
        $specificSystemId = $this->option('system-id');
        $searchMemberId = $this->option('search-member');
        
        if ($specificSystemId) {
            $this->info("🎯 Targeting specific system: {$specificSystemId}");
        }
        
        $this->info("👤 Looking for member ID: {$searchMemberId}");
        $this->newLine();
        
        // Known MLS system IDs from the system info response
        $mlsSystemIds = [
            '20250213134913285161000000' => 'ImagineMLS (Current)',
            '20240123174036063766000000' => 'Unknown MLS #2',
            '20210504182759685317000000' => 'Unknown MLS #3', 
            '20130925153233009157000000' => 'Metro Search MLS',
            '20130228193502179028000000' => 'Knoxville Area Association of REALTORS MLS',
        ];
        
        if ($specificSystemId) {
            $mlsSystemIds = [$specificSystemId => 'Specified System'];
        }
        
        $baseUrl = config('services.flexmls.base_url');
        $accessToken = config('services.flexmls.access_token');
        
        foreach ($mlsSystemIds as $systemId => $systemName) {
            $this->info("🏢 Testing System: {$systemName}");
            $this->line("   ID: {$systemId}");
            
            try {
                // Try to get listings from this specific system
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => 'application/json',
                    'X-SparkApi-System-Id' => $systemId, // Try system-specific header
                ])->timeout(30)->get($baseUrl . '/v1/listings', [
                    '_limit' => 50,
                    '_expand' => 'PrimaryPhoto',
                ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    $listings = $data['D']['Results'] ?? [];
                    
                    $this->line("   ✅ Success: Found " . count($listings) . " listings");
                    
                    // Look for our member ID in these listings
                    $memberFound = false;
                    $jeremiahListings = [];
                    
                    foreach ($listings as $listing) {
                        $standardFields = $listing['StandardFields'] ?? [];
                        $agentMlsId = $standardFields['ListAgentMlsId'] ?? '';
                        $agentName = $standardFields['ListAgentName'] ?? '';
                        $firstName = $standardFields['ListAgentFirstName'] ?? '';
                        $lastName = $standardFields['ListAgentLastName'] ?? '';
                        
                        if ($agentMlsId == $searchMemberId) {
                            $memberFound = true;
                            $jeremiahListings[] = [
                                'listing_id' => $standardFields['ListingId'] ?? 'N/A',
                                'address' => $standardFields['UnparsedAddress'] ?? 'N/A',
                                'price' => $standardFields['ListPrice'] ?? 0,
                                'status' => $standardFields['MlsStatus'] ?? 'Unknown',
                                'agent_name' => $agentName,
                                'agent_mls_id' => $agentMlsId,
                            ];
                        }
                        
                        // Also check for Jeremiah Brown by name
                        if ($this->isJeremiahBrownMatch($agentName, $firstName, $lastName)) {
                            $jeremiahListings[] = [
                                'listing_id' => $standardFields['ListingId'] ?? 'N/A',
                                'address' => $standardFields['UnparsedAddress'] ?? 'N/A',
                                'price' => $standardFields['ListPrice'] ?? 0,
                                'status' => $standardFields['MlsStatus'] ?? 'Unknown',
                                'agent_name' => $agentName,
                                'agent_mls_id' => $agentMlsId,
                            ];
                        }
                    }
                    
                    if ($memberFound) {
                        $this->info("   🎉 FOUND MEMBER {$searchMemberId} in this system!");
                    }
                    
                    if (!empty($jeremiahListings)) {
                        $this->info("   🏠 Found " . count($jeremiahListings) . " Jeremiah Brown listings:");
                        foreach ($jeremiahListings as $listing) {
                            $this->line("     • MLS #{$listing['listing_id']}: {$listing['address']} - ${$listing['price']} ({$listing['status']})");
                        }
                    } else {
                        $this->line("   ⚪ No Jeremiah Brown listings in this system");
                    }
                    
                } else {
                    $this->line("   ❌ Failed: HTTP " . $response->status());
                }
                
            } catch (\Exception $e) {
                $this->line("   ❌ Error: " . $e->getMessage());
            }
            
            $this->newLine();
            
            // Small delay between requests
            usleep(500000);
        }
        
        // Try alternative approaches
        $this->info('🔄 Trying Alternative API Approaches...');
        $this->newLine();
        
        // Try different endpoints that might reveal system information
        $alternativeEndpoints = [
            '/v1/account' => 'Account Information',
            '/v1/account/agents' => 'Account Agents',
            '/v1/agents' => 'All Agents',
            '/v1/agents/' . $searchMemberId => "Agent {$searchMemberId}",
            '/v1/contacts' => 'Contacts',
        ];
        
        foreach ($alternativeEndpoints as $endpoint => $description) {
            $this->line("Testing {$description}: {$endpoint}");
            
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => 'application/json',
                ])->timeout(10)->get($baseUrl . $endpoint);
                
                if ($response->successful()) {
                    $data = $response->json();
                    $this->line("  ✅ Success - Response keys: " . implode(', ', array_keys($data)));
                    
                    // If it's agents endpoint, look for our member
                    if (str_contains($endpoint, 'agents') && isset($data['D']['Results'])) {
                        foreach ($data['D']['Results'] as $agent) {
                            $agentMlsId = $agent['MlsId'] ?? '';
                            $agentName = $agent['Name'] ?? '';
                            
                            if ($agentMlsId == $searchMemberId || stripos($agentName, 'jeremiah') !== false) {
                                $this->info("  🎉 Found matching agent: " . json_encode($agent, JSON_PRETTY_PRINT));
                            }
                        }
                    }
                    
                } else {
                    $this->line("  ❌ Failed: HTTP " . $response->status());
                }
                
            } catch (\Exception $e) {
                $this->line("  ❌ Error: " . $e->getMessage());
            }
        }

        return Command::SUCCESS;
    }
    
    /**
     * Check if agent info matches Jeremiah Brown
     */
    private function isJeremiahBrownMatch(string $agentName, string $firstName, string $lastName): bool
    {
        $jeremiahVariations = ['jeremiah', 'jeremy', 'jer', 'j.', 'j '];
        $brownVariations = ['brown'];
        
        // Check full name
        $fullNameLower = strtolower($agentName);
        foreach ($jeremiahVariations as $jeremiah) {
            foreach ($brownVariations as $brown) {
                if (strpos($fullNameLower, $jeremiah) !== false && strpos($fullNameLower, $brown) !== false) {
                    return true;
                }
            }
        }
        
        // Check first/last name
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
        
        return false;
    }
}
