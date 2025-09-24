<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestMainSparkApi extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'flexmls:test-main-api
                            {--member-id=20271 : Member ID to search for}';

    /**
     * The console command description.
     */
    protected $description = 'Test the main Spark API endpoint and discover available MLS systems';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Testing Main Spark API (sparkapi.com)');
        $this->info('==========================================');
        
        $baseUrl = 'https://sparkapi.com';
        $accessToken = config('services.flexmls.access_token');
        $memberId = $this->option('member-id');
        
        $this->info('🎯 Target Member ID: ' . $memberId);
        $this->info('🌐 API Endpoint: ' . $baseUrl);
        $this->newLine();
        
        // Test basic connectivity
        $this->testConnectivity($baseUrl, $accessToken);
        
        // Test Standard Fields service to discover available MLSs
        $this->testStandardFields($baseUrl, $accessToken);
        
        // Test listings with different MLS IDs
        $this->testListingsWithMlsIds($baseUrl, $accessToken, $memberId);
        
        // Test Historical Agent search
        $this->testHistoricalAgentSearch($baseUrl, $accessToken, $memberId);

        return Command::SUCCESS;
    }
    
    private function testConnectivity(string $baseUrl, string $accessToken): void
    {
        $this->info('1️⃣ Testing Basic Connectivity...');
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
            ])->timeout(10)->get($baseUrl . '/v1/system');
            
            if ($response->successful()) {
                $this->line('   ✅ Connected successfully (HTTP ' . $response->status() . ')');
            } else {
                $this->error('   ❌ Connection failed (HTTP ' . $response->status() . ')');
                $this->line('   Response: ' . $response->body());
            }
            
        } catch (\Exception $e) {
            $this->error('   ❌ Connection error: ' . $e->getMessage());
        }
        
        $this->newLine();
    }
    
    private function testStandardFields(string $baseUrl, string $accessToken): void
    {
        $this->info('2️⃣ Testing Standard Fields Service (MLS Discovery)...');
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
            ])->timeout(10)->get($baseUrl . '/v1/standardfields');
            
            if ($response->successful()) {
                $data = $response->json();
                $this->line('   ✅ Standard Fields retrieved');
                
                // Look for MlsId field to discover available MLSs
                if (isset($data['D']['Results'])) {
                    $fields = $data['D']['Results'];
                    
                    foreach ($fields as $field) {
                        if (isset($field['Field']) && $field['Field'] === 'MlsId') {
                            $this->info('   🏢 Found MlsId Field - Available MLSs:');
                            
                            if (isset($field['FieldList'])) {
                                foreach ($field['FieldList'] as $mls) {
                                    $mlsId = $mls['Value'] ?? 'Unknown';
                                    $mlsName = $mls['Text'] ?? 'Unknown Name';
                                    $this->line("     • {$mlsName} (ID: {$mlsId})");
                                }
                            }
                            break;
                        }
                    }
                }
                
            } else {
                $this->error('   ❌ Failed (HTTP ' . $response->status() . ')');
                $this->line('   Response: ' . substr($response->body(), 0, 200));
            }
            
        } catch (\Exception $e) {
            $this->error('   ❌ Error: ' . $e->getMessage());
        }
        
        $this->newLine();
    }
    
    private function testListingsWithMlsIds(string $baseUrl, string $accessToken, string $memberId): void
    {
        $this->info('3️⃣ Testing Listings with MLS ID Parameters...');
        
        // Test without MLS ID first (current MLS only)
        $this->line('   Testing current MLS only...');
        $this->testListingsEndpoint($baseUrl, $accessToken, [], 'Current MLS');
        
        // Test with specific MLS IDs from previous discovery
        $mlsIdsToTest = [
            '20250213134913285161000000' => 'ImagineMLS',
            '20240123174036063766000000' => 'Unknown MLS #2',
            '20210504182759685317000000' => 'Unknown MLS #3',
            '20130925153233009157000000' => 'Metro Search MLS',
            '20130228193502179028000000' => 'Knoxville Area Association MLS',
        ];
        
        foreach ($mlsIdsToTest as $mlsId => $mlsName) {
            $this->line("   Testing {$mlsName}...");
            $params = ['_filter' => "MlsId Eq '{$mlsId}'"];
            $this->testListingsEndpoint($baseUrl, $accessToken, $params, $mlsName);
            
            usleep(500000); // 0.5 second delay
        }
        
        $this->newLine();
    }
    
    private function testHistoricalAgentSearch(string $baseUrl, string $accessToken, string $memberId): void
    {
        $this->info('4️⃣ Testing Historical Agent Search...');
        
        // Test HistoricalListAgentId
        $this->line("   Testing HistoricalListAgentId = {$memberId}...");
        $params = [
            '_filter' => "HistoricalListAgentId Eq '{$memberId}'",
            '_limit' => 100
        ];
        
        $jeremiahListings = $this->testListingsEndpoint($baseUrl, $accessToken, $params, 'Historical Search');
        
        if (!empty($jeremiahListings)) {
            $this->info('   🎉 Found ' . count($jeremiahListings) . ' listings with HistoricalListAgentId!');
            
            foreach ($jeremiahListings as $index => $listing) {
                $standardFields = $listing['StandardFields'] ?? [];
                $listingId = $standardFields['ListingId'] ?? 'N/A';
                $address = $standardFields['UnparsedAddress'] ?? 'N/A';
                $price = $standardFields['ListPrice'] ?? 0;
                $status = $standardFields['MlsStatus'] ?? 'Unknown';
                $agentName = $standardFields['ListAgentName'] ?? 'Unknown';
                
                $this->line("     Listing " . ($index + 1) . ":");
                $this->line("       MLS #: {$listingId}");
                $this->line("       Address: {$address}");
                $this->line("       Price: $" . number_format($price));
                $this->line("       Status: {$status}");
                $this->line("       Agent: {$agentName}");
                $this->newLine();
            }
        }
        
        $this->newLine();
    }
    
    private function testListingsEndpoint(string $baseUrl, string $accessToken, array $params, string $context): array
    {
        $defaultParams = [
            '_limit' => 50,
            '_expand' => 'PrimaryPhoto'
        ];
        
        $allParams = array_merge($defaultParams, $params);
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
            ])->timeout(30)->get($baseUrl . '/v1/listings', $allParams);
            
            if ($response->successful()) {
                $data = $response->json();
                $listings = $data['D']['Results'] ?? [];
                $count = count($listings);
                
                $this->line("     ✅ {$context}: {$count} listings");
                
                // Look for Jeremiah Brown in these listings
                $jeremiahListings = [];
                foreach ($listings as $listing) {
                    $standardFields = $listing['StandardFields'] ?? [];
                    $agentMlsId = $standardFields['ListAgentMlsId'] ?? '';
                    $agentName = $standardFields['ListAgentName'] ?? '';
                    $firstName = $standardFields['ListAgentFirstName'] ?? '';
                    $lastName = $standardFields['ListAgentLastName'] ?? '';
                    
                    // Check if this is Jeremiah Brown
                    if ($agentMlsId === '20271' || 
                        (stripos($agentName, 'jeremiah') !== false && stripos($agentName, 'brown') !== false) ||
                        (stripos($firstName, 'jeremiah') !== false && stripos($lastName, 'brown') !== false)) {
                        
                        $jeremiahListings[] = $listing;
                        $this->line("     🎉 Found Jeremiah Brown listing!");
                    }
                }
                
                return $jeremiahListings;
                
            } else {
                $this->line("     ❌ {$context}: HTTP " . $response->status());
                if ($response->status() === 400) {
                    $this->line("     Error: " . substr($response->body(), 0, 100));
                }
                return [];
            }
            
        } catch (\Exception $e) {
            $this->line("     ❌ {$context}: " . $e->getMessage());
            return [];
        }
    }
}
