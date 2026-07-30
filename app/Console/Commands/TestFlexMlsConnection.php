<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FlexMlsApiService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestFlexMlsConnection extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'flexmls:test-connection';

    /**
     * The console command description.
     */
    protected $description = 'Test the FlexMLS API connection and authentication';

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
        $this->info('🔧 Testing FlexMLS API Connection');
        $this->info('==================================');
        
        // Test 1: Configuration
        $this->testConfiguration();
        
        // Test 2: Basic API connectivity
        $this->testBasicConnectivity();
        
        // Test 3: Authentication
        $this->testAuthentication();
        
        // Test 4: System info endpoint
        $this->testSystemInfo();
        
        // Test 5: Listings endpoint with minimal filters
        $this->testListingsEndpoint();

        // Test 6: RESO Web API v3
        $this->testResoEndpoint();
        
        return Command::SUCCESS;
    }
    
    private function testConfiguration(): void
    {
        $this->info('1️⃣ Testing Configuration...');
        
        $accessToken = config('services.flexmls.access_token');
        $feedId = config('services.flexmls.feed_id');
        $baseUrl = config('services.flexmls.base_url');
        
        $this->line("   Access Token: " . (strlen($accessToken) > 10 ? substr($accessToken, 0, 10) . '...' : 'NOT SET'));
        $this->line("   Feed ID: " . ($feedId ?: 'NOT SET'));
        $this->line("   Base URL: " . ($baseUrl ?: 'NOT SET'));
        
        if (!$accessToken || !$feedId || !$baseUrl) {
            $this->error('   ❌ Missing required configuration');
        } else {
            $this->line('   ✅ Configuration looks good');
        }
        
        $this->newLine();
    }
    
    private function testBasicConnectivity(): void
    {
        $this->info('2️⃣ Testing Basic Connectivity...');
        
        $baseUrl = config('services.flexmls.base_url');
        
        try {
            $response = Http::timeout(10)->get($baseUrl);
            $this->line("   Status: " . $response->status());
            
            if ($response->status() >= 200 && $response->status() < 400) {
                $this->line('   ✅ Basic connectivity successful');
            } else {
                $this->error('   ❌ Unexpected response status');
            }
            
        } catch (\Exception $e) {
            $this->error('   ❌ Connection failed: ' . $e->getMessage());
        }
        
        $this->newLine();
    }
    
    private function testAuthentication(): void
    {
        $this->info('3️⃣ Testing Authentication...');
        
        $baseUrl = config('services.flexmls.base_url');
        $accessToken = config('services.flexmls.access_token');
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
            ])->timeout(10)->get($baseUrl . '/v1/system');
            
            $this->line("   Status: " . $response->status());
            $this->line("   Content-Type: " . $response->header('Content-Type'));
            
            if ($response->status() === 200) {
                $this->line('   ✅ Authentication successful');
                
                $data = $response->json();
                if (isset($data['D'])) {
                    $this->line('   ✅ Valid JSON response structure');
                } else {
                    $this->warn('   ⚠️  Unexpected JSON structure');
                    $this->line('   Response: ' . substr($response->body(), 0, 200) . '...');
                }
            } elseif ($response->status() === 401) {
                $this->error('   ❌ Authentication failed - Invalid access token');
            } elseif ($response->status() === 403) {
                $this->error('   ❌ Access forbidden - Check permissions');
                $this->line('   Response: ' . substr($response->body(), 0, 500) . '...');
            } else {
                $this->error('   ❌ Unexpected response status');
                $this->line('   Response: ' . substr($response->body(), 0, 300) . '...');
            }
            
        } catch (\Exception $e) {
            $this->error('   ❌ Authentication test failed: ' . $e->getMessage());
        }
        
        $this->newLine();
    }
    
    private function testSystemInfo(): void
    {
        $this->info('4️⃣ Testing System Info Endpoint...');
        
        $baseUrl = config('services.flexmls.base_url');
        $accessToken = config('services.flexmls.access_token');
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
            ])->timeout(10)->get($baseUrl . '/v1/system');
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['D']['Results'])) {
                    $this->line('   ✅ System info retrieved successfully');
                    $systemInfo = $data['D']['Results'];
                    
                    $this->newLine();
                    $this->info('   🏢 MLS SYSTEM INFORMATION:');
                    
                    // Display all available system info
                    foreach ($systemInfo as $key => $value) {
                        if (is_string($value) || is_numeric($value)) {
                            $this->line("   {$key}: {$value}");
                        } elseif (is_array($value)) {
                            $this->line("   {$key}: " . json_encode($value));
                        }
                    }
                    
                } else {
                    $this->warn('   ⚠️  Unexpected response structure');
                    $this->line('   Available keys: ' . implode(', ', array_keys($data)));
                }
            } else {
                $this->error('   ❌ Failed to get system info');
            }
            
        } catch (\Exception $e) {
            $this->error('   ❌ System info test failed: ' . $e->getMessage());
        }
        
        // Test for multiple systems/boards
        $this->newLine();
        $this->info('   🔍 Checking for available MLS boards/systems...');
        
        try {
            // Try to get a list of available systems
            $systemsResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
            ])->timeout(10)->get($baseUrl . '/v1/systeminfo');
            
            if ($systemsResponse->successful()) {
                $systemsData = $systemsResponse->json();
                $this->line('   Systems endpoint response: ' . json_encode($systemsData, JSON_PRETTY_PRINT));
            } else {
                $this->line("   Systems endpoint not available (status: {$systemsResponse->status()})");
            }
            
        } catch (\Exception $e) {
            $this->line('   Systems endpoint error: ' . $e->getMessage());
        }
        
        // Check for member info endpoint
        try {
            $memberResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
            ])->timeout(10)->get($baseUrl . '/v1/members/20271');
            
            if ($memberResponse->successful()) {
                $memberData = $memberResponse->json();
                $this->newLine();
                $this->info('   👤 MEMBER 20271 INFORMATION:');
                $this->line('   ' . json_encode($memberData, JSON_PRETTY_PRINT));
            } else {
                $this->line("   Member 20271 endpoint status: {$memberResponse->status()}");
            }
            
        } catch (\Exception $e) {
            $this->line('   Member endpoint error: ' . $e->getMessage());
        }
        
        $this->newLine();
    }
    
    private function testListingsEndpoint(): void
    {
        $this->info('5️⃣ Testing Listings Endpoint...');
        
        $baseUrl = config('services.flexmls.base_url');
        $accessToken = config('services.flexmls.access_token');
        
        try {
            // Test with minimal parameters using configured listings endpoint
            $listingsEndpoint = config('services.flexmls.listings_endpoint', '/v1/my/listings');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
            ])->timeout(30)->get($baseUrl . $listingsEndpoint, [
                '_limit' => 5,
                '_expand' => 'PrimaryPhoto',
                '_pagination' => 1,
            ]);

            $this->line('   Endpoint: ' . $listingsEndpoint);            
            $this->line("   Status: " . $response->status());
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['D']['Results'])) {
                    $results = $data['D']['Results'];
                    $this->line('   ✅ Listings endpoint accessible');
                    $this->line('   Results found: ' . count($results));
                    
                    if (!empty($results)) {
                        $listing = $results[0];
                        $this->line('   Sample listing ID: ' . ($listing['ListingId'] ?? $listing['Id'] ?? 'N/A'));
                        $this->line('   Sample MLS Status: ' . ($listing['MlsStatus'] ?? 'N/A'));
                        $this->line('   Sample Property Type: ' . ($listing['PropertyType'] ?? 'N/A'));
                        
                        $this->newLine();
                        $this->info('   📋 Sample Listing Data Structure:');
                        $this->line('   Available Keys: ' . implode(', ', array_keys($listing)));
                        $this->newLine();
                        
                        // Show a few key fields to understand the structure
                        foreach (['ListingId', 'Id', 'MlsStatus', 'PropertyType', 'ListPrice', 'City', 'StateOrProvince', 'Address'] as $key) {
                            if (isset($listing[$key])) {
                                $this->line("   {$key}: " . (is_string($listing[$key]) ? $listing[$key] : json_encode($listing[$key])));
                            }
                        }
                        
                        // Check if StandardFields exists and show its structure
                        if (isset($listing['StandardFields'])) {
                            $this->newLine();
                            $this->info('   📊 StandardFields Structure:');
                            $standardFields = $listing['StandardFields'];
                            if (is_array($standardFields)) {
                                $this->line('   StandardFields Keys: ' . implode(', ', array_keys($standardFields)));
                                $this->newLine();
                                
                                // Show key property data from StandardFields
                                foreach (['ListingId', 'MlsStatus', 'PropertyType', 'ListPrice', 'City', 'StateOrProvince', 'UnparsedAddress', 'PublicRemarks'] as $key) {
                                    if (isset($standardFields[$key])) {
                                        $value = is_string($standardFields[$key]) ? $standardFields[$key] : json_encode($standardFields[$key]);
                                        if (strlen($value) > 100) {
                                            $value = substr($value, 0, 100) . '...';
                                        }
                                        $this->line("   {$key}: {$value}");
                                    }
                                }
                                
                                $this->newLine();
                                $this->info('   👤 Agent Information:');
                                // Show agent-related fields
                                foreach (['ListAgentName', 'ListAgentId', 'ListAgentKey', 'ListAgentMlsId', 'ListOfficeName', 'ListOfficeId', 'ListAgentFirstName', 'ListAgentLastName'] as $key) {
                                    if (isset($standardFields[$key])) {
                                        $value = is_string($standardFields[$key]) ? $standardFields[$key] : json_encode($standardFields[$key]);
                                        $this->line("   {$key}: {$value}");
                                    }
                                }
                            }
                        }
                    }
                } else {
                    $this->warn('   ⚠️  No results in response structure');
                    $this->line('   Response keys: ' . implode(', ', array_keys($data)));
                }
            } elseif ($response->status() === 401) {
                $this->error('   ❌ Unauthorized - Check access token');
            } elseif ($response->status() === 403) {
                $this->error('   ❌ Forbidden - Check permissions for listings endpoint');
                $this->line('   Response: ' . substr($response->body(), 0, 500) . '...');
            } elseif ($response->status() === 404) {
                $this->error('   ❌ Endpoint not found - Check API version and URL');
            } else {
                $this->error('   ❌ Unexpected response');
                $this->line('   Response: ' . substr($response->body(), 0, 300) . '...');
            }
            
        } catch (\Exception $e) {
            $this->error('   ❌ Listings test failed: ' . $e->getMessage());
        }
        
        $this->newLine();
    }

    private function testResoEndpoint(): void
    {
        $this->info('6️⃣ Testing RESO Web API v3...');

        $resoUrl = rtrim((string) config('services.flexmls.reso_url'), '/');
        $accessToken = config('services.flexmls.access_token');

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
            ])->timeout(30)->get($resoUrl . '/Property', [
                '$top' => 3,
                '$count' => 'true',
                '$filter' => "MlsStatus eq 'Active'",
                '$select' => 'ListingId,ListPrice,UnparsedAddress,MlsStatus,ListAgentFullName',
            ]);

            $this->line('   Endpoint: ' . $resoUrl . '/Property');
            $this->line('   Status: ' . $response->status());

            if ($response->successful()) {
                $data = $response->json();
                $count = $data['@odata.count'] ?? count($data['value'] ?? []);
                $this->line('   ✅ RESO v3 accessible');
                $this->line('   Active properties (count): ' . $count);

                foreach (array_slice($data['value'] ?? [], 0, 3) as $i => $property) {
                    $this->line(sprintf(
                        '   %d. MLS #%s | %s | $%s',
                        $i + 1,
                        $property['ListingId'] ?? 'N/A',
                        $property['UnparsedAddress'] ?? 'N/A',
                        number_format($property['ListPrice'] ?? 0)
                    ));
                }
            } else {
                $this->error('   ❌ RESO request failed');
                $this->line('   Response: ' . substr($response->body(), 0, 300));
            }
        } catch (\Exception $e) {
            $this->error('   ❌ RESO test failed: ' . $e->getMessage());
        }

        $this->newLine();
    }
}
