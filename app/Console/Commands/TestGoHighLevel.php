<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoHighLevelService;
use Illuminate\Support\Facades\Config;

class TestGoHighLevel extends Command
{
    protected $signature = 'test:ghl 
                           {--test-contact : Test contact creation}
                           {--connection : Test API connection only}';
    
    protected $description = 'Test GoHighLevel API integration';

    public function handle()
    {
        $this->info('🔗 Testing GoHighLevel Integration');
        $this->info('API Token: ' . (Config::get('services.gohighlevel.api_token') ? 'Configured ✓' : 'Missing ❌'));
        $this->info('Location ID: ' . Config::get('services.gohighlevel.location_id'));
        
        $ghlService = new GoHighLevelService();
        
        // Test basic connection
        $this->info("\n🧪 Testing API Connection...");
        $connectionResult = $ghlService->testConnection();
        
        if ($connectionResult['success']) {
            $this->info('✅ Connection successful!');
            $this->line('Location Data: ' . json_encode($connectionResult['data'], JSON_PRETTY_PRINT));
        } else {
            $this->error('❌ Connection failed: ' . $connectionResult['message']);
            if (isset($connectionResult['error'])) {
                $this->line('Error Details: ' . json_encode($connectionResult['error'], JSON_PRETTY_PRINT));
            }
            return 1;
        }
        
        // Test contact creation if requested
        if ($this->option('test-contact') && !$this->option('connection')) {
            $this->info("\n👤 Testing Contact Creation...");
            
            $testContactData = [
                'firstName' => 'Test',
                'lastName' => 'User',
                'email' => 'test+' . time() . '@jblandrealty.com',
                'phone' => '(555) 123-4567',
                'source' => 'API Test',
                'tags' => ['Test Lead', 'API Integration'],
                'customFields' => [
                    'test_field' => 'test_value',
                    'property_id' => '1',
                    'lead_source_detail' => 'Integration Test'
                ]
            ];
            
            $contactResult = $ghlService->createContact($testContactData);
            
            if ($contactResult['success']) {
                $this->info('✅ Test contact created successfully!');
                $this->line('Contact ID: ' . ($contactResult['contact_id'] ?? 'N/A'));
                
                // Test tag addition if contact was created
                if (!empty($contactResult['contact_id'])) {
                    $this->info("\n🏷️  Testing Tag Addition...");
                    $tagResult = $ghlService->addTagsToContact($contactResult['contact_id'], ['API Test Success']);
                    
                    if ($tagResult['success']) {
                        $this->info('✅ Tags added successfully!');
                    } else {
                        $this->warn('⚠️  Tag addition failed: ' . $tagResult['message']);
                    }
                }
                
            } else {
                $this->error('❌ Test contact creation failed: ' . $contactResult['message']);
                if (isset($contactResult['error'])) {
                    $this->line('Error Details: ' . json_encode($contactResult['error'], JSON_PRETTY_PRINT));
                }
                return 1;
            }
        }
        
        $this->info("\n🎉 GoHighLevel integration test completed successfully!");
        
        if (!$this->option('test-contact') && !$this->option('connection')) {
            $this->line("\nRun with --test-contact to test contact creation");
            $this->line("Run with --connection to test connection only");
        }
        
        return 0;
    }
}
