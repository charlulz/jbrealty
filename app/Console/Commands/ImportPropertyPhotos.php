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
                            {--statuses= : Comma separated statuses to process, e.g. active,pending}
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
            $this->importPhotosForAllProperties($updateExisting, $limit, $this->parseStatuses());
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
     * Statuses requested via --statuses, e.g. "active,pending"
     *
     * @return array<int, string>
     */
    private function parseStatuses(): array
    {
        $raw = (string) $this->option('statuses');

        if (trim($raw) === '') {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $raw))));
    }

    /**
     * Import photos for all FlexMLS properties.
     *
     * Properties are streamed in chunks so memory stays flat regardless of how
     * many listings exist — each record carries a sizeable api_data payload.
     *
     * @param  array<int, string>  $statuses
     */
    private function importPhotosForAllProperties(bool $updateExisting, ?int $limit, array $statuses = []): void
    {
        $query = Property::where('api_source', 'flexmls');

        if ($statuses !== []) {
            $query->whereIn('status', $statuses);
            $this->line('   Statuses: ' . implode(', ', $statuses));
        }

        $totalProperties = (clone $query)->count();

        if ($limit) {
            $totalProperties = min($limit, $totalProperties);
        }

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

        $processed = 0;
        $totalPhotos = 0;
        $successCount = 0;
        $errorCount = 0;

        $query->orderBy('id')->chunkById(25, function ($properties) use (
            $updateExisting, $totalProperties, $progressBar,
            &$processed, &$totalPhotos, &$successCount, &$errorCount
        ) {
            foreach ($properties as $property) {
                $progressBar->setMessage('Processing: ' . \Illuminate\Support\Str::limit($property->title, 40));

                try {
                    $photosImported = $this->flexMlsService->importPropertyPhotos($property, $updateExisting);
                    $totalPhotos += $photosImported;

                    if ($photosImported > 0) {
                        $successCount++;
                        // Only pace ourselves when we actually hit the API
                        usleep(250000);
                    }
                } catch (\Throwable $e) {
                    $errorCount++;
                    Log::error('Photo import failed for property', [
                        'property_id' => $property->id,
                        'property_title' => $property->title,
                        'error' => $e->getMessage(),
                    ]);
                }

                $processed++;
                $progressBar->advance();

                if ($processed >= $totalProperties) {
                    return false;
                }
            }

            return true;
        });

        $progressBar->finish();
        $this->newLine(2);

        // Summary
        $this->info('📊 PHOTO IMPORT SUMMARY');
        $this->info('======================');
        $this->table(['Metric', 'Count'], [
            ['Total Properties Processed', $processed],
            ['Properties with New Photos', $successCount],
            ['Total Photos Imported', $totalPhotos],
            ['Errors', $errorCount],
            ['Peak Memory (MB)', round(memory_get_peak_usage(true) / 1048576, 1)],
        ]);

        if ($errorCount > 0) {
            $this->warn("⚠️ {$errorCount} properties had import errors. Check logs for details.");
        }

        $this->info("\n✨ Photo import completed!");
    }
}
