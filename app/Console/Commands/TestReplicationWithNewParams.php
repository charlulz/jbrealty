<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestReplicationWithNewParams extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'flexmls:test-replication-params
                            {--member-id=20271 : Member ID to search for}';

    /**
     * The console command description.
     */
    protected $description = 'Test replication API with new parameters from documentation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Testing Replication API with New Parameters');
        $this->info('=============================================');
        
        $baseUrl = config('services.flexmls.base_url');
        $accessToken = config('services.flexmls.access_token');
        $memberId = $this->option('member-id');
        
        $this->info('🎯 Target Member ID: ' . $memberId);
        $this->info('🌐 API Endpoint: ' . $baseUrl);
        $this->newLine();
        
        // Test different search strategies based on documentation
        $this->testHistoricalAgentSearch($baseUrl, $accessToken, $memberId);
        $this->testAgentNameFiltering($baseUrl, $accessToken);
        $this->testMultipleMlsIds($baseUrl, $accessToken, $memberId);
        $this->testRecentListingsSearch($baseUrl, $accessToken);

        return Command::SUCCESS;
    }
    
    private function testHistoricalAgentSearch(string $baseUrl, string $accessToken, string $memberId): void
    {
        $this->info('1️⃣ Testing HistoricalListAgentId Search...');
        
        // According to docs: "includes all listings from past offices for that user"
        $params = [
            '_filter' => "HistoricalListAgentId Eq '{$memberId}'",
            '_limit' => 200,
            '_expand' => 'PrimaryPhoto'
        ];
        
        $this->testListingsQuery($baseUrl, $accessToken, $params, 'HistoricalListAgentId');
        $this->newLine();
    }
    
    private function testAgentNameFiltering(string $baseUrl, string $accessToken): void
    {
        $this->info('2️⃣ Testing Agent Name Filtering...');
        
        // Test filtering by ListAgentName containing "Brown"
        $searches = [
            "ListAgentName Co 'Brown'" => 'Agent name contains Brown',
            "ListAgentName Co 'Jeremiah'" => 'Agent name contains Jeremiah',
            "ListAgentFirstName Co 'Jeremiah'" => 'First name contains Jeremiah',
            "ListAgentLastName Co 'Brown'" => 'Last name contains Brown',
        ];
        
        foreach ($searches as $filter => $description) {
            $this->line("   Testing: {$description}");
            $params = [
                '_filter' => $filter,
                '_limit' => 100,
                '_expand' => 'PrimaryPhoto'
            ];
            
            $this->testListingsQuery($baseUrl, $accessToken, $params, $description);
            usleep(500000); // 0.5 second delay
        }
        
        $this->newLine();
    }
    
    private function testMultipleMlsIds(string $baseUrl, string $accessToken, string $memberId): void
    {
        $this->info('3️⃣ Testing Multiple MLS IDs...');
        
        // From previous discovery - test each MLS individually with our member ID
        $mlsIds = [
            '20250213134913285161000000' => 'ImagineMLS',
            '20240123174036063766000000' => 'Unknown MLS #2',
            '20210504182759685317000000' => 'Unknown MLS #3',
            '20130925153233009157000000' => 'Metro Search MLS',
            '20130228193502179028000000' => 'Knoxville MLS',
        ];
        
        foreach ($mlsIds as $mlsId => $mlsName) {
            $this->line("   Testing {$mlsName}...");
            
            // Search for our specific agent in this MLS
            $params = [
                '_filter' => "MlsId Eq '{$mlsId}' And ListAgentMlsId Eq '{$memberId}'",
                '_limit' => 100,
                '_expand' => 'PrimaryPhoto'
            ];
            
            $this->testListingsQuery($baseUrl, $accessToken, $params, $mlsName);
            usleep(500000);
        }
        
        $this->newLine();
    }
    
    private function testRecentListingsSearch(string $baseUrl, string $accessToken): void
    {
        $this->info('4️⃣ Testing Recent Listings with Agent Filters...');
        
        // Search recent listings (last 2 years) with agent filters
        $twoYearsAgo = date('Y-m-d', strtotime('-2 years'));
        
        $params = [
            '_filter' => "OnMarketDate Gt {$twoYearsAgo} And (ListAgentName Co 'Brown' Or ListAgentName Co 'Jeremiah')",
            '_limit' => 200,
            '_expand' => 'PrimaryPhoto',
            '_orderby' => 'OnMarketDate desc'
        ];
        
        $this->testListingsQuery($baseUrl, $accessToken, $params, 'Recent listings with agent filter');
        $this->newLine();
    }
    
    private function testListingsQuery(string $baseUrl, string $accessToken, array $params, string $description): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
            ])->timeout(30)->get($baseUrl . '/v1/listings', $params);
            
            if ($response->successful()) {
                $data = $response->json();
                $listings = $data['D']['Results'] ?? [];
                $count = count($listings);
                
                $this->line("     ✅ {$description}: {$count} listings found");
                
                // Look for Jeremiah Brown matches
                $jeremiahListings = [];
                foreach ($listings as $listing) {
                    $standardFields = $listing['StandardFields'] ?? [];
                    $agentMlsId = $standardFields['ListAgentMlsId'] ?? '';
                    $agentName = $standardFields['ListAgentName'] ?? '';
                    $firstName = $standardFields['ListAgentFirstName'] ?? '';
                    $lastName = $standardFields['ListAgentLastName'] ?? '';
                    $listingId = $standardFields['ListingId'] ?? '';
                    $address = $standardFields['UnparsedAddress'] ?? '';
                    $price = $standardFields['ListPrice'] ?? 0;
                    $status = $standardFields['MlsStatus'] ?? '';
                    
                    // Check if this is potentially Jeremiah Brown
                    $isJeremiah = false;
                    $matchReason = '';
                    
                    if ($agentMlsId === '20271') {
                        $isJeremiah = true;
                        $matchReason = 'MLS ID 20271';
                    } elseif (stripos($agentName, 'jeremiah') !== false && stripos($agentName, 'brown') !== false) {
                        $isJeremiah = true;
                        $matchReason = 'Name: Jeremiah Brown';
                    } elseif (stripos($firstName, 'jeremiah') !== false && stripos($lastName, 'brown') !== false) {
                        $isJeremiah = true;
                        $matchReason = 'First/Last: Jeremiah Brown';
                    } elseif (stripos($agentName, 'j') !== false && stripos($agentName, 'brown') !== false) {
                        $isJeremiah = true;
                        $matchReason = 'Possible: J Brown';
                    }
                    
                    if ($isJeremiah) {
                        $jeremiahListings[] = $listing;
                        $this->info("       🎉 MATCH: {$listingId} - {$address} (${$price}) - {$status}");
                        $this->line("           Agent: {$agentName} (ID: {$agentMlsId}) - {$matchReason}");
                    }
                }
                
                if (!empty($jeremiahListings)) {
                    $this->info("     🎯 Found " . count($jeremiahListings) . " Jeremiah Brown matches!");
                } else {
                    $this->line("     ⚪ No Jeremiah Brown matches found");
                }
                
                return $jeremiahListings;
                
            } else {
                $this->line("     ❌ {$description}: HTTP " . $response->status());
                
                if ($response->status() === 400) {
                    $errorBody = $response->json();
                    if (isset($errorBody['D']['Message'])) {
                        $this->line("     Error: " . $errorBody['D']['Message']);
                    }
                }
                
                return [];
            }
            
        } catch (\Exception $e) {
            $this->line("     ❌ {$description}: " . $e->getMessage());
            return [];
        }
    }
}
