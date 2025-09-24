<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FlexMlsApiService;
use Illuminate\Support\Facades\Log;

class FindJeremiahBrownListings extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'flexmls:find-jeremiah-brown
                            {--limit=100 : Maximum number of listings to search through}
                            {--mls-id= : Search for specific MLS agent ID}
                            {--agent-name= : Search for specific agent name}';

    /**
     * The console command description.
     */
    protected $description = 'Search for Jeremiah Brown listings in the FlexMLS system';

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
        $this->info('🔍 Searching for Jeremiah Brown Listings');
        $this->info('========================================');
        
        $limit = (int) $this->option('limit');
        $specificMlsId = $this->option('mls-id');
        $specificAgentName = $this->option('agent-name');
        
        if ($specificMlsId) {
            $this->info("🎯 Searching for MLS Agent ID: {$specificMlsId}");
        } elseif ($specificAgentName) {
            $this->info("🎯 Searching for Agent Name: {$specificAgentName}");
        } else {
            $this->info("🎯 Searching for 'Jeremiah Brown' and variations");
        }
        
        $this->newLine();
        
        try {
            // Get all listings (without the Jeremiah Brown filter)
            $this->info("📡 Fetching up to {$limit} listings from FlexMLS...");
            
            $allListings = $this->getRawListingsFromApi($limit);
            
            if (empty($allListings)) {
                $this->error('❌ No listings found in the MLS system');
                return Command::FAILURE;
            }
            
            $this->info("✅ Retrieved " . count($allListings) . " total listings");
            $this->newLine();
            
            $jeremiahListings = [];
            $agentCounts = [];
            
            foreach ($allListings as $listing) {
                $apiData = json_decode($listing['api_data'] ?? '{}', true);
                $standardFields = $apiData['StandardFields'] ?? [];
                
                $agentName = $standardFields['ListAgentName'] ?? 'Unknown Agent';
                $firstName = $standardFields['ListAgentFirstName'] ?? '';
                $lastName = $standardFields['ListAgentLastName'] ?? '';
                $mlsId = $standardFields['ListAgentMlsId'] ?? '';
                $officeName = $standardFields['ListOfficeName'] ?? '';
                
                // Count agents for summary
                $agentCounts[$agentName] = ($agentCounts[$agentName] ?? 0) + 1;
                
                // Check if this matches our search criteria
                $isMatch = false;
                
                if ($specificMlsId) {
                    $isMatch = ($mlsId == $specificMlsId);
                } elseif ($specificAgentName) {
                    $isMatch = (stripos($agentName, $specificAgentName) !== false);
                } else {
                    // Default Jeremiah Brown search
                    $isMatch = $this->isJeremiahBrownMatch($agentName, $firstName, $lastName);
                }
                
                if ($isMatch) {
                    $jeremiahListings[] = [
                        'listing_id' => $listing['mls_number'] ?? 'N/A',
                        'address' => $listing['title'] ?? 'N/A',
                        'price' => $listing['price'] ?? 0,
                        'agent_name' => $agentName,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'mls_id' => $mlsId,
                        'office_name' => $officeName,
                        'property_type' => $listing['property_type'] ?? 'N/A',
                    ];
                }
            }
            
            if (!empty($jeremiahListings)) {
                $this->info("🎉 Found " . count($jeremiahListings) . " matching listings!");
                $this->newLine();
                
                foreach ($jeremiahListings as $index => $listing) {
                    $this->info("Listing " . ($index + 1) . ":");
                    $this->line("  MLS #: {$listing['listing_id']}");
                    $this->line("  Address: {$listing['address']}");
                    $this->line("  Price: $" . number_format($listing['price']));
                    $this->line("  Agent: {$listing['agent_name']}");
                    $this->line("  First Name: {$listing['first_name']}");
                    $this->line("  Last Name: {$listing['last_name']}");
                    $this->line("  MLS Agent ID: {$listing['mls_id']}");
                    $this->line("  Office: {$listing['office_name']}");
                    $this->line("  Type: {$listing['property_type']}");
                    $this->newLine();
                }
            } else {
                $this->warn("⚠️  No matching listings found");
                $this->newLine();
                
                $this->info("📊 Top agents in the system (showing first 10):");
                arsort($agentCounts);
                $topAgents = array_slice($agentCounts, 0, 10, true);
                
                foreach ($topAgents as $agent => $count) {
                    $this->line("  • {$agent}: {$count} listings");
                }
                
                $this->newLine();
                $this->info("💡 Tips:");
                $this->line("  • Try searching for a specific MLS Agent ID: --mls-id=12345");
                $this->line("  • Try searching for partial agent name: --agent-name='Brown'");
                $this->line("  • Check if Jeremiah Brown might be listed under a different name variation");
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Search failed: ' . $e->getMessage());
            
            if ($this->option('verbose')) {
                $this->error($e->getTraceAsString());
            }
            
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
    
    /**
     * Get raw listings from API without filtering
     */
    private function getRawListingsFromApi(int $limit): array
    {
        try {
            // Call the API service but get raw listings before filtering
            $response = $this->flexMlsService->makeRawApiRequest('GET', '/v1/listings', [
                '_limit' => $limit,
                '_expand' => 'PrimaryPhoto',
            ]);
            
            if (!$response || !isset($response['D']['Results'])) {
                return [];
            }
            
            $listings = [];
            foreach ($response['D']['Results'] as $listing) {
                $transformed = $this->transformListing($listing);
                if ($transformed) {
                    $listings[] = $transformed;
                }
            }
            
            return $listings;
            
        } catch (\Exception $e) {
            Log::error('Error fetching raw listings', ['error' => $e->getMessage()]);
            return [];
        }
    }
    
    /**
     * Transform a raw listing to our format
     */
    private function transformListing(array $listing): array
    {
        $data = $listing['StandardFields'] ?? $listing;
        
        return [
            'mls_number' => $data['ListingId'] ?? $data['MlsNumber'] ?? null,
            'title' => $data['UnparsedAddress'] ?? 'Property Listing',
            'price' => (float) ($data['ListPrice'] ?? 0),
            'property_type' => $this->mapPropertyType($data['PropertyType'] ?? 'Residential'),
            'api_data' => json_encode($listing),
        ];
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

        return $typeMap[$type] ?? 'residential';
    }
    
    /**
     * Check if agent info matches Jeremiah Brown
     */
    private function isJeremiahBrownMatch(string $agentName, string $firstName, string $lastName): bool
    {
        $jeremiahVariations = ['jeremiah', 'jeremy', 'jer'];
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
