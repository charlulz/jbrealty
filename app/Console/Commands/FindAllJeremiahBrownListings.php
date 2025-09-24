<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FlexMlsApiService;
use Illuminate\Support\Facades\Log;

class FindAllJeremiahBrownListings extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'flexmls:find-all-jeremiah-brown
                            {--batch-size=200 : Number of listings to fetch per batch}
                            {--max-batches=20 : Maximum number of batches to fetch}';

    /**
     * The console command description.
     */
    protected $description = 'Find ALL Jeremiah Brown listings by paginating through the entire MLS system';

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
        $this->info('🔍 Comprehensive Search for ALL Jeremiah Brown Listings');
        $this->info('======================================================');
        
        $batchSize = (int) $this->option('batch-size');
        $maxBatches = (int) $this->option('max-batches');
        
        $this->info("📡 Searching in batches of {$batchSize} listings, up to {$maxBatches} batches");
        $this->info("🎯 Looking for all variations of 'Jeremiah Brown'");
        $this->newLine();
        
        try {
            $jeremiahListings = [];
            $totalProcessed = 0;
            $agentCounts = [];
            
            $progressBar = $this->output->createProgressBar($maxBatches);
            $progressBar->setFormat('Batch %current%/%max% [%bar%] %percent:3s%% - Found: %found% Jeremiah Brown listings');
            $progressBar->setMessage('0', 'found');
            
            for ($batch = 0; $batch < $maxBatches; $batch++) {
                $offset = $batch * $batchSize;
                
                $response = $this->flexMlsService->makeRawApiRequest('GET', '/v1/listings', [
                    '_limit' => $batchSize,
                    '_offset' => $offset,
                    '_expand' => 'PrimaryPhoto',
                ]);
                
                if (!$response || !isset($response['D']['Results'])) {
                    $this->newLine();
                    $this->warn("⚠️  No more data available at batch {$batch}");
                    break;
                }
                
                $listings = $response['D']['Results'];
                if (empty($listings)) {
                    $this->newLine();
                    $this->warn("⚠️  No listings in batch {$batch} - reached end of data");
                    break;
                }
                
                // Process this batch
                foreach ($listings as $listing) {
                    $totalProcessed++;
                    $standardFields = $listing['StandardFields'] ?? [];
                    
                    $agentName = $standardFields['ListAgentName'] ?? 'Unknown Agent';
                    $firstName = $standardFields['ListAgentFirstName'] ?? '';
                    $lastName = $standardFields['ListAgentLastName'] ?? '';
                    $mlsId = $standardFields['ListAgentMlsId'] ?? '';
                    $officeName = $standardFields['ListOfficeName'] ?? '';
                    
                    // Count agents
                    $agentCounts[$agentName] = ($agentCounts[$agentName] ?? 0) + 1;
                    
                    // Check if this is Jeremiah Brown
                    if ($this->isJeremiahBrownMatch($agentName, $firstName, $lastName)) {
                        $jeremiahListings[] = [
                            'listing_id' => $standardFields['ListingId'] ?? 'N/A',
                            'address' => $standardFields['UnparsedAddress'] ?? 'N/A',
                            'price' => $standardFields['ListPrice'] ?? 0,
                            'status' => $standardFields['MlsStatus'] ?? 'Unknown',
                            'property_type' => $this->mapPropertyType($standardFields['PropertyType'] ?? ''),
                            'agent_name' => $agentName,
                            'first_name' => $firstName,
                            'last_name' => $lastName,
                            'mls_id' => $mlsId,
                            'office_name' => $officeName,
                            'on_market_date' => $standardFields['OnMarketDate'] ?? 'N/A',
                        ];
                        
                        $progressBar->setMessage((string)count($jeremiahListings), 'found');
                    }
                }
                
                $progressBar->advance();
                
                // Small delay between batches to be polite to the API
                usleep(500000); // 0.5 seconds
            }
            
            $progressBar->finish();
            $this->newLine(2);
            
            // Results
            $this->info("📊 SEARCH COMPLETE:");
            $this->line("  • Total listings processed: " . number_format($totalProcessed));
            $this->line("  • Jeremiah Brown listings found: " . count($jeremiahListings));
            $this->newLine();
            
            if (!empty($jeremiahListings)) {
                $this->info("🎉 JEREMIAH BROWN LISTINGS FOUND:");
                $this->newLine();
                
                foreach ($jeremiahListings as $index => $listing) {
                    $this->info("Listing " . ($index + 1) . ":");
                    $this->line("  MLS #: {$listing['listing_id']}");
                    $this->line("  Address: {$listing['address']}");
                    $this->line("  Price: $" . number_format($listing['price']));
                    $this->line("  Status: {$listing['status']}");
                    $this->line("  Type: {$listing['property_type']}");
                    $this->line("  Agent: {$listing['agent_name']}");
                    $this->line("  MLS Agent ID: {$listing['mls_id']}");
                    $this->line("  Office: {$listing['office_name']}");
                    $this->line("  On Market: {$listing['on_market_date']}");
                    $this->newLine();
                }
                
                // Summary by status
                $statusCounts = [];
                foreach ($jeremiahListings as $listing) {
                    $status = $listing['status'];
                    $statusCounts[$status] = ($statusCounts[$status] ?? 0) + 1;
                }
                
                $this->info("📈 Listings by Status:");
                foreach ($statusCounts as $status => $count) {
                    $this->line("  • {$status}: {$count}");
                }
                
            } else {
                $this->warn("⚠️  No Jeremiah Brown listings found");
                $this->newLine();
                
                $this->info("📊 Top 15 agents found (by listing count):");
                arsort($agentCounts);
                $topAgents = array_slice($agentCounts, 0, 15, true);
                
                foreach ($topAgents as $agent => $count) {
                    $this->line("  • {$agent}: {$count}");
                }
                
                $this->newLine();
                $this->info("💡 Suggestions:");
                $this->line("  • Jeremiah Brown might be listed under a different name");
                $this->line("  • He might not have any active listings currently");
                $this->line("  • Try searching historical data with different MLS statuses");
            }
            
        } catch (\Exception $e) {
            $this->newLine();
            $this->error('❌ Search failed: ' . $e->getMessage());
            
            if ($this->option('verbose')) {
                $this->error($e->getTraceAsString());
            }
            
            return Command::FAILURE;
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
    
    /**
     * Map property type
     */
    private function mapPropertyType(string $type): string
    {
        $typeMap = [
            'R' => 'residential',
            'C' => 'commercial', 
            'L' => 'land',
            'F' => 'farm',
            'Single Family Residence' => 'residential',
            'Commercial' => 'commercial',
            'Land' => 'land',
            'Farm' => 'farm',
        ];

        return $typeMap[$type] ?? ($type ?: 'unknown');
    }
}
