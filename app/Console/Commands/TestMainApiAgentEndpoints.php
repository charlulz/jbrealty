<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestMainApiAgentEndpoints extends Command
{
    protected $signature = 'flexmls:test-main-agent-endpoints';
    protected $description = 'Test agent endpoints on main Spark API (sparkapi.com) instead of replication';

    public function handle()
    {
        $this->info('🔧 Testing Agent Endpoints on Main Spark API');
        $this->info('============================================');
        
        $mainApiUrl = 'https://sparkapi.com'; // Try main API instead of replication
        $replicationUrl = config('services.flexmls.base_url'); // Current replication URL
        $accessToken = config('services.flexmls.access_token');
        
        $this->info('🌐 Main API: ' . $mainApiUrl);
        $this->info('🔄 Replication API: ' . $replicationUrl);
        $this->info('🔑 Using Access Token: ' . substr($accessToken, 0, 10) . '...');
        $this->newLine();
        
        // First, test what our token is associated with
        $this->info('1️⃣ Testing Token Identity...');
        $this->testTokenIdentity($mainApiUrl, $accessToken);
        $this->newLine();
        
        // Test agent endpoints on main API
        $this->info('2️⃣ Testing Agent Endpoints on Main API...');
        $endpoints = [
            'My Listings' => '/v1/my/listings',
            'Office Listings' => '/v1/office/listings', 
            'Company Listings' => '/v1/company/listings'
        ];
        
        foreach ($endpoints as $name => $endpoint) {
            $this->testEndpointOnMainApi($name, $endpoint, $mainApiUrl, $accessToken);
        }
        
        $this->newLine();
        
        // Also test accounts endpoints to see what we have access to
        $this->info('3️⃣ Testing Accounts/Profile Endpoints...');
        $accountEndpoints = [
            'My Account' => '/v1/my/account',
            'My Profile' => '/v1/my/profile',
            'System Info' => '/v1/system'
        ];
        
        foreach ($accountEndpoints as $name => $endpoint) {
            $this->testEndpointOnMainApi($name, $endpoint, $mainApiUrl, $accessToken);
        }

        return Command::SUCCESS;
    }
    
    private function testTokenIdentity(string $baseUrl, string $accessToken): void
    {
        // Try to understand what this token represents
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
            ])->timeout(10)->get($baseUrl . '/v1/system');
            
            if ($response->successful()) {
                $data = $response->json();
                $results = $data['D']['Results'] ?? [];
                
                $this->line('   ✅ Token is valid for main API');
                
                if (isset($results['Name'])) {
                    $this->line('   🏢 System: ' . $results['Name']);
                }
                
                if (isset($results['MlsSystems']) && is_array($results['MlsSystems'])) {
                    $this->line('   🌐 Available MLS Systems: ' . count($results['MlsSystems']));
                    foreach (array_slice($results['MlsSystems'], 0, 3) as $mls) {
                        $this->line('     • ' . ($mls['Name'] ?? 'Unknown'));
                    }
                    if (count($results['MlsSystems']) > 3) {
                        $this->line('     ... and ' . (count($results['MlsSystems']) - 3) . ' more');
                    }
                }
                
            } else {
                $this->line('   ❌ Token not valid for main API: HTTP ' . $response->status());
                if ($response->status() === 403) {
                    $this->line('   🔒 Confirmed: Token is restricted to replication API only');
                }
            }
            
        } catch (\Exception $e) {
            $this->line('   ❌ Error testing token identity: ' . $e->getMessage());
        }
    }
    
    private function testEndpointOnMainApi(string $name, string $endpoint, string $baseUrl, string $accessToken): void
    {
        $this->line("📋 Testing: {$name} on main API");
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
            ])->timeout(15)->get($baseUrl . $endpoint, [
                '_limit' => 10
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['D']['Results'])) {
                    $results = $data['D']['Results'];
                    $count = is_array($results) ? count($results) : 1;
                    $this->info("   ✅ Success: Found data ({$count} items)");
                    
                    // If this is listings data, check for Jeremiah Brown
                    if (strpos($endpoint, 'listings') !== false && is_array($results)) {
                        $jeremiahCount = 0;
                        foreach ($results as $listing) {
                            if ($this->isJeremiahBrownListing($listing)) {
                                $jeremiahCount++;
                            }
                        }
                        
                        if ($jeremiahCount > 0) {
                            $this->info("   🎉 FOUND {$jeremiahCount} Jeremiah Brown listings!");
                        }
                    }
                    
                    // Show sample data structure
                    if (is_array($results) && !empty($results)) {
                        $sample = $results[0];
                        if (isset($sample['StandardFields'])) {
                            $standardFields = $sample['StandardFields'];
                            if (isset($standardFields['ListAgentName'])) {
                                $this->line("   👤 Sample Agent: " . $standardFields['ListAgentName']);
                            }
                            if (isset($standardFields['ListOfficeName'])) {
                                $this->line("   🏢 Sample Office: " . $standardFields['ListOfficeName']);
                            }
                        }
                    }
                    
                } else {
                    $this->line("   ✅ Success: Response received (non-array data)");
                    
                    // For account/profile endpoints
                    if (isset($data['D']['Results']['Name'])) {
                        $this->line("   👤 Account Name: " . $data['D']['Results']['Name']);
                    }
                    if (isset($data['D']['Results']['Id'])) {
                        $this->line("   🆔 Account ID: " . $data['D']['Results']['Id']);
                    }
                }
                
            } else {
                $this->error("   ❌ Failed: HTTP " . $response->status());
                
                $errorData = $response->json();
                if (isset($errorData['D']['Message'])) {
                    $this->line("   💬 Error: " . $errorData['D']['Message']);
                }
                
                if ($response->status() === 403) {
                    $this->line("   🔒 This confirms our token is replication-only");
                }
            }
            
        } catch (\Exception $e) {
            $this->error("   ❌ Exception: " . $e->getMessage());
        }
        
        $this->newLine();
    }
    
    private function isJeremiahBrownListing(array $listing): bool
    {
        $standardFields = $listing['StandardFields'] ?? [];
        
        $agentMlsId = $standardFields['ListAgentMlsId'] ?? '';
        $agentName = $standardFields['ListAgentName'] ?? '';
        $officeName = $standardFields['ListOfficeName'] ?? '';
        
        $knownIds = ['20271', '429520271'];
        
        return in_array($agentMlsId, $knownIds) || 
               (stripos($agentName, 'jeremiah') !== false && stripos($agentName, 'brown') !== false) ||
               stripos($officeName, 'jb land') !== false;
    }
}
