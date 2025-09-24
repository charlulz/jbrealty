<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FlexMlsApiService;
use App\Services\PropertyImportService;
use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImportFromFlexMls extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'properties:import-flexmls
                            {--limit=500 : Maximum number of listings to import}
                            {--property-type= : Filter by property type (e.g., Residential, Farm, Land)}
                            {--min-price= : Minimum listing price filter}
                            {--max-price= : Maximum listing price filter}
                            {--min-acres= : Minimum acreage filter}
                            {--status=All : MLS status filter (Active, Pending, Sold, All)}
                            {--dry-run : Preview import without saving to database}
                            {--download-images : Download and save property images}
                            {--update-existing : Update existing properties based on MLS number}
                            {--clear-existing : Clear all existing properties before import}
                            {--chunk=50 : Process records in chunks}';

    /**
     * The console command description.
     */
    protected $description = 'Import properties from FlexMLS API using Spark Platform';

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
        $this->info('🏠 FlexMLS Property Import Tool');
        $this->info('================================');
        $this->warn('📌 AGENT FILTER: Only importing listings for Jeremiah Brown');
        
        $dryRun = $this->option('dry-run');
        $downloadImages = $this->option('download-images');
        $updateExisting = $this->option('update-existing');
        $clearExisting = $this->option('clear-existing');
        $limit = (int) $this->option('limit');
        $chunk = (int) $this->option('chunk');
        
        if ($dryRun) {
            $this->warn('DRY RUN MODE - No data will be saved');
        }
        
        $this->newLine();
        $this->displayFilters();
        
        if (!$dryRun && $clearExisting) {
            if ($this->confirm('⚠️  This will delete ALL existing properties. Are you sure?')) {
                $this->clearExistingProperties();
            } else {
                $clearExisting = false;
            }
        }
        
        try {
            // Build filters from command options
            $filters = $this->buildFilters();
            
            $this->info('🔍 Fetching listings from FlexMLS API...');
            $this->line('   API Filters: ' . json_encode($filters));
            
            $listings = $this->flexMlsService->getListings($filters);
            
            $this->line('   Raw listings count: ' . count($listings));
            
            if (empty($listings)) {
                $this->error('❌ No listings found with the specified filters');
                
                // Try without any filters to see if there's data
                $this->info('🔍 Trying to fetch listings without filters...');
                $allListings = $this->flexMlsService->getListings([]);
                $this->line('   Total listings available: ' . count($allListings));
                
                if (!empty($allListings)) {
                    $this->info('✅ Data is available, but no Jeremiah Brown listings found');
                    $this->info('ℹ️  Total listings in MLS: ' . count($allListings));
                    $this->info('ℹ️  This tool only imports listings where Jeremiah Brown is the listing agent');
                    
                    // Show a sample of what was found
                    $sample = $allListings[0] ?? null;
                    if ($sample) {
                        $this->line('   Sample listing agent info (from first listing):');
                        $apiData = json_decode($sample['api_data'] ?? '{}', true);
                        $standardFields = $apiData['StandardFields'] ?? [];
                        $this->line('   - Agent Name: ' . ($standardFields['ListAgentName'] ?? 'Unknown'));
                        $this->line('   - First Name: ' . ($standardFields['ListAgentFirstName'] ?? 'Unknown'));
                        $this->line('   - Last Name: ' . ($standardFields['ListAgentLastName'] ?? 'Unknown'));
                        $this->line('   - Office: ' . ($standardFields['ListOfficeName'] ?? 'Unknown'));
                    }
                } else {
                    $this->info('ℹ️  No listings found in MLS at all');
                }
                
                return Command::FAILURE;
            }
            
            $this->info("✅ Found " . count($listings) . " listings");
            
            if ($dryRun) {
                $this->displayDryRunResults($listings);
                return Command::SUCCESS;
            }
            
            // Process listings in chunks
            $results = $this->processListings($listings, $chunk, $updateExisting, $downloadImages);
            
            $this->displayResults($results);
            
            if ($results['imported'] > 0 || $results['updated'] > 0) {
                $this->info('🎉 Import completed successfully!');
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Import failed: ' . $e->getMessage());
            
            if ($this->option('verbose')) {
                $this->error($e->getTraceAsString());
            }
            
            Log::error('FlexMLS import failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * Build API filters from command options
     */
    private function buildFilters(): array
    {
        $filters = [
            'limit' => $this->option('limit'),
            'status' => $this->option('status'),
        ];

        if ($this->option('property-type')) {
            $filters['property_type'] = $this->option('property-type');
        }

        if ($this->option('min-price')) {
            $filters['min_price'] = (float) $this->option('min-price');
        }

        if ($this->option('max-price')) {
            $filters['max_price'] = (float) $this->option('max-price');
        }

        if ($this->option('min-acres')) {
            $filters['min_acres'] = (float) $this->option('min-acres');
        }

        return array_filter($filters);
    }

    /**
     * Display current filters
     */
    private function displayFilters(): void
    {
        $this->info('Current Filters:');
        $this->line('  • Limit: ' . $this->option('limit'));
        $this->line('  • Status: ' . $this->option('status') . ' (use --status=All to include all statuses)');
        
        if ($this->option('property-type')) {
            $this->line('  • Property Type: ' . $this->option('property-type'));
        }
        
        if ($this->option('min-price')) {
            $this->line('  • Min Price: $' . number_format($this->option('min-price')));
        }
        
        if ($this->option('max-price')) {
            $this->line('  • Max Price: $' . number_format($this->option('max-price')));
        }
        
        if ($this->option('min-acres')) {
            $this->line('  • Min Acres: ' . $this->option('min-acres'));
        }
        
        $this->newLine();
    }

    /**
     * Clear existing properties from database
     */
    private function clearExistingProperties(): void
    {
        $this->info('🗑️ Clearing existing properties...');
        
        try {
            // Delete images and documents first
            PropertyImage::query()->delete();
            \App\Models\PropertyDocument::query()->delete();
            
            // Delete properties
            $deletedCount = Property::query()->delete();
            
            $this->info("✅ Cleared {$deletedCount} existing properties");
            
        } catch (\Exception $e) {
            $this->error('Failed to clear existing properties: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Process listings and save to database
     */
    private function processListings(array $listings, int $chunk, bool $updateExisting, bool $downloadImages): array
    {
        $results = [
            'processed' => 0,
            'imported' => 0,
            'updated' => 0,
            'skipped' => 0,
            'failed' => 0,
            'images_downloaded' => 0,
            'errors' => []
        ];

        $chunks = array_chunk($listings, $chunk);
        $totalChunks = count($chunks);
        
        $this->info("📦 Processing {$totalChunks} chunks of {$chunk} listings each...");
        
        foreach ($chunks as $chunkIndex => $chunkListings) {
            $this->info("Processing chunk " . ($chunkIndex + 1) . "/{$totalChunks}...");
            
            foreach ($chunkListings as $listing) {
                $results['processed']++;
                
                try {
                    $this->line("  Processing: " . ($listing['title'] ?? 'Unknown Property'));
                    
                    // Check for existing property
                    $existingProperty = null;
                    if (!empty($listing['mls_number'])) {
                        $existingProperty = Property::where('mls_number', $listing['mls_number'])->first();
                    }
                    
                    if ($existingProperty && !$updateExisting) {
                        $results['skipped']++;
                        continue;
                    }
                    
                    // Create or update property
                    if ($existingProperty && $updateExisting) {
                        $existingProperty->update($listing);
                        $property = $existingProperty;
                        $results['updated']++;
                        $this->line("    ✅ Updated existing property");
                    } else {
                        $property = Property::create($listing);
                        $results['imported']++;
                        $this->line("    ✅ Created new property");
                    }
                    
                    // Download images if requested
                    if ($downloadImages) {
                        $imageResults = $this->downloadPropertyImages($property);
                        $results['images_downloaded'] += $imageResults['downloaded'];
                        $results['errors'] = array_merge($results['errors'], $imageResults['errors']);
                    }
                    
                } catch (\Exception $e) {
                    $results['failed']++;
                    $errorMsg = "Failed to process listing: " . $e->getMessage();
                    $results['errors'][] = $errorMsg;
                    $this->error("    ❌ " . $errorMsg);
                    
                    Log::error('FlexMLS property processing error', [
                        'listing' => $listing,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        return $results;
    }

    /**
     * Download and save property images
     */
    private function downloadPropertyImages(Property $property): array
    {
        $results = [
            'downloaded' => 0,
            'errors' => []
        ];

        try {
            $photos = $this->flexMlsService->getListingPhotos($property->mls_number);
            
            if (empty($photos)) {
                return $results;
            }
            
            $this->line("    📷 Downloading {count} images...", ['count' => count($photos)]);
            
            foreach ($photos as $index => $photo) {
                try {
                    if (empty($photo['url'])) {
                        continue;
                    }
                    
                    $response = Http::timeout(30)->get($photo['url']);
                    
                    if ($response->successful()) {
                        $filename = 'flexmls_' . $property->mls_number . '_' . ($index + 1) . '.jpg';
                        $path = "properties/{$property->id}/images/exterior/{$filename}";
                        
                        // Create directory if it doesn't exist
                        $directory = dirname(storage_path('app/public/' . $path));
                        if (!is_dir($directory)) {
                            mkdir($directory, 0755, true);
                        }
                        
                        Storage::disk('public')->put($path, $response->body());
                        
                        // Create PropertyImage record
                        PropertyImage::create([
                            'property_id' => $property->id,
                            'filename' => $filename,
                            'path' => $path,
                            'url' => Storage::disk('public')->url($path),
                            'title' => $photo['caption'] ?: "Property Image " . ($index + 1),
                            'alt_text' => "Property image for {$property->title}",
                            'file_size' => strlen($response->body()),
                            'mime_type' => 'image/jpeg',
                            'sort_order' => $photo['order'] ?: ($index + 1),
                            'is_primary' => $photo['primary'] && $index === 0,
                            'category' => 'exterior',
                        ]);
                        
                        $results['downloaded']++;
                        
                    } else {
                        $results['errors'][] = "Failed to download image from {$photo['url']}: HTTP {$response->status()}";
                    }
                    
                } catch (\Exception $e) {
                    $results['errors'][] = "Error downloading image: " . $e->getMessage();
                }
            }
            
        } catch (\Exception $e) {
            $results['errors'][] = "Error fetching photos for MLS {$property->mls_number}: " . $e->getMessage();
        }

        return $results;
    }

    /**
     * Display dry run results
     */
    private function displayDryRunResults(array $listings): void
    {
        $this->newLine();
        $this->info('🔍 DRY RUN RESULTS:');
        $this->newLine();
        
        $displayCount = min(5, count($listings));
        
        for ($i = 0; $i < $displayCount; $i++) {
            $listing = $listings[$i];
            
            $this->info("Listing " . ($i + 1) . ":");
            $this->line("  MLS Number: " . ($listing['mls_number'] ?? 'N/A'));
            $this->line("  Title: " . ($listing['title'] ?? 'N/A'));
            $this->line("  Price: $" . number_format($listing['price'] ?? 0));
            $this->line("  Type: " . ($listing['property_type'] ?? 'N/A'));
            $this->line("  Status: " . ($listing['status'] ?? 'N/A'));
            $this->line("  Acres: " . ($listing['total_acres'] ?? 'N/A'));
            $this->line("  Address: " . ($listing['street_address'] ?? 'N/A'));
            $this->line("  City: " . ($listing['city'] ?? 'N/A'));
            $this->newLine();
        }
        
        if (count($listings) > $displayCount) {
            $this->info("... and " . (count($listings) - $displayCount) . " more listings");
        }
        
        $this->newLine();
        $this->info("Total listings that would be imported: " . count($listings));
    }

    /**
     * Display final import results
     */
    private function displayResults(array $results): void
    {
        $this->newLine();
        $this->info('📊 IMPORT RESULTS:');
        
        $this->table(['Metric', 'Count'], [
            ['Properties Processed', $results['processed']],
            ['Successfully Imported', $results['imported']],
            ['Updated Existing', $results['updated']],
            ['Skipped (Duplicates)', $results['skipped']],
            ['Failed', $results['failed']],
            ['Images Downloaded', $results['images_downloaded']],
        ]);

        if (!empty($results['errors'])) {
            $this->newLine();
            $this->warn('⚠️  ERRORS ENCOUNTERED:');
            foreach (array_slice($results['errors'], 0, 10) as $error) {
                $this->error("• {$error}");
            }
            
            if (count($results['errors']) > 10) {
                $this->error("• ... and " . (count($results['errors']) - 10) . " more errors (check logs)");
            }
        }
    }
}
