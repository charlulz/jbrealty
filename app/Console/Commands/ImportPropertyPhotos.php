<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use App\Services\FlexMlsApiService;
use Illuminate\Support\Facades\Log;

class ImportPropertyPhotos extends Command
{
    protected $signature = 'properties:import-photos
                            {--property= : Database ID (properties.id), not MLS number}
                            {--mls= : MLS number (ListingId), e.g. 25015817}
                            {--update-existing : Update existing photos instead of skipping them}
                            {--limit= : Limit number of properties to process}';

    protected $description = 'Import photos for Jeremiah Brown properties from Spark API';

    protected FlexMlsApiService $flexMlsService;

    public function __construct(FlexMlsApiService $flexMlsService)
    {
        parent::__construct();
        $this->flexMlsService = $flexMlsService;
    }

    public function handle()
    {
        $this->info('📸 Property Photo Import');
        $this->info('======================');
        
        $propertyId = $this->option('property');
        $mls = $this->option('mls');
        $updateExisting = $this->option('update-existing');
        $limit = $this->option('limit');

        if ($propertyId !== null && $propertyId !== '' && $mls !== null && $mls !== '') {
            $this->error('Use only one of --property or --mls');

            return Command::FAILURE;
        }

        if ($mls !== null && $mls !== '') {
            $property = Property::where('api_source', 'flexmls')
                ->where('mls_number', $mls)
                ->first();

            if (! $property) {
                $this->error("❌ No FlexMLS property found with mls_number: {$mls}");
                $this->line('   Tip: confirm the listing is synced locally (properties.api_source = flexmls).');

                return Command::FAILURE;
            }

            $this->importPhotosForProperty($property, $updateExisting);
        } elseif ($propertyId !== null && $propertyId !== '') {
            $property = Property::where('api_source', 'flexmls')->find($propertyId);
            if (! $property) {
                $this->error("❌ Property with database id {$propertyId} not found or not api_source flexmls.");
                $this->line('   Tip: use --mls=YOUR_LISTING_ID if you only know the MLS number from the site.');

                return Command::FAILURE;
            }

            $this->importPhotosForProperty($property, $updateExisting);
        } else {
            // Import photos for all FlexMLS properties
            $this->importPhotosForAllProperties($updateExisting, $limit);
        }

        return Command::SUCCESS;
    }

    /**
     * Import photos for a specific property
     */
    private function importPhotosForProperty(Property $property, bool $updateExisting): void
    {
        $this->info("Importing photos for: {$property->title}");
        
        try {
            $photosImported = $this->flexMlsService->importPropertyPhotos($property, $updateExisting);
            
            if ($photosImported > 0) {
                $this->info("✅ Successfully imported {$photosImported} photos");
            } else {
                $this->warn("⚠️ No new photos imported (may already exist or no photos available)");
            }
        } catch (\Exception $e) {
            $this->error("❌ Failed to import photos: " . $e->getMessage());
            Log::error('Photo import failed for property', [
                'property_id' => $property->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Import photos for all FlexMLS properties
     */
    private function importPhotosForAllProperties(bool $updateExisting, ?int $limit): void
    {
        $query = Property::where('api_source', 'flexmls');
        
        if ($limit) {
            $query->limit($limit);
        }
        
        $properties = $query->get();
        $totalProperties = $properties->count();
        
        if ($totalProperties === 0) {
            $this->warn('⚠️ No FlexMLS properties found');
            return;
        }
        
        $this->info("Found {$totalProperties} properties for photo import");
        $this->newLine();
        
        $progressBar = $this->output->createProgressBar($totalProperties);
        $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% - %message%');
        $progressBar->setMessage('Starting...');
        $progressBar->start();
        
        $totalPhotos = 0;
        $successCount = 0;
        $errorCount = 0;
        
        foreach ($properties as $index => $property) {
            $progressBar->setMessage("Processing: " . \Illuminate\Support\Str::limit($property->title, 40));
            
            try {
                $photosImported = $this->flexMlsService->importPropertyPhotos($property, $updateExisting);
                $totalPhotos += $photosImported;
                
                if ($photosImported > 0) {
                    $successCount++;
                }
                
                // Small delay to be respectful to the API
                usleep(500000); // 0.5 second delay
                
            } catch (\Exception $e) {
                $errorCount++;
                Log::error('Photo import failed for property', [
                    'property_id' => $property->id,
                    'property_title' => $property->title,
                    'error' => $e->getMessage()
                ]);
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine(2);
        
        // Summary
        $this->info('📊 PHOTO IMPORT SUMMARY');
        $this->info('======================');
        $this->table(['Metric', 'Count'], [
            ['Total Properties Processed', $totalProperties],
            ['Properties with New Photos', $successCount],
            ['Total Photos Imported', $totalPhotos],
            ['Errors', $errorCount],
        ]);
        
        if ($errorCount > 0) {
            $this->warn("⚠️ {$errorCount} properties had import errors. Check logs for details.");
        }
        
        $this->info("\n✨ Photo import completed!");
    }
}
