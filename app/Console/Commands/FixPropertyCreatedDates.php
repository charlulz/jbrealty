<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FixPropertyCreatedDates extends Command
{
    protected $signature = 'properties:fix-created-dates 
                            {--dry-run : Show what would be changed without making changes}';

    protected $description = 'Fix created_at dates for existing properties to use original MLS listing dates';

    public function handle()
    {
        $this->info('🔧 Fixing Property Created Dates');
        $this->info('================================');

        $dryRun = $this->option('dry-run');
        if ($dryRun) {
            $this->warn('🔍 DRY RUN MODE - No data will be changed');
        }

        $properties = Property::where('api_source', 'flexmls')
                             ->whereNotNull('api_data')
                             ->get();

        if ($properties->isEmpty()) {
            $this->warn('No FlexMLS properties found to fix.');
            return Command::FAILURE;
        }

        $this->info("Found {$properties->count()} FlexMLS properties to process");
        $this->newLine();

        $fixed = 0;
        $skipped = 0;
        $errors = 0;

        $progressBar = $this->output->createProgressBar($properties->count());
        $progressBar->setFormat('verbose');
        $progressBar->start();

        foreach ($properties as $property) {
            try {
                $apiData = is_string($property->api_data) 
                         ? json_decode($property->api_data, true) 
                         : $property->api_data;

                if (!$apiData || !isset($apiData['StandardFields'])) {
                    $this->line("\n   ⚠️ Skipping {$property->title}: No API data");
                    $skipped++;
                    $progressBar->advance();
                    continue;
                }

                $standardFields = $apiData['StandardFields'];
                $originalListingDate = $this->parseOriginalListingDate($standardFields);
                $currentCreatedAt = $property->created_at;

                if ($originalListingDate && !$currentCreatedAt->equalTo($originalListingDate)) {
                    $daysDifference = $currentCreatedAt->diffInDays($originalListingDate);
                    
                    $this->line("\n   🔄 {$property->title}");
                    $this->line("      Current: {$currentCreatedAt->format('Y-m-d H:i:s')}");
                    $this->line("      Should be: {$originalListingDate->format('Y-m-d H:i:s')}");
                    $this->line("      Difference: {$daysDifference} days");

                    if (!$dryRun) {
                        // Update created_at without triggering model events
                        DB::table('properties')
                          ->where('id', $property->id)
                          ->update(['created_at' => $originalListingDate]);

                        $this->line("      ✅ Fixed!");
                    } else {
                        $this->line("      🔍 Would fix in real run");
                    }

                    $fixed++;
                } else {
                    $skipped++;
                }

                $progressBar->advance();

            } catch (\Exception $e) {
                $this->error("\n   ❌ Error processing {$property->title}: " . $e->getMessage());
                $errors++;
                $progressBar->advance();
            }
        }

        $progressBar->finish();
        $this->newLine(2);

        // Summary
        $this->info('📊 SUMMARY:');
        $this->line("  • Properties processed: {$properties->count()}");
        $this->line("  • Properties fixed: {$fixed}");
        $this->line("  • Properties skipped: {$skipped}");
        $this->line("  • Errors: {$errors}");

        if ($dryRun) {
            $this->newLine();
            $this->info('🔍 This was a dry run. Run without --dry-run to apply changes.');
        } else {
            $this->newLine();
            $this->info('✅ Created dates have been fixed!');
        }

        return Command::SUCCESS;
    }

    private function parseOriginalListingDate(array $standardFields): ?Carbon
    {
        // Priority order for original listing date
        $dateFields = [
            'OriginalOnMarketTimestamp',
            'OnMarketDate',
            'OnMarketTimestamp',
            'ListingContractDate',
            'OnMarketContractDate',
        ];

        foreach ($dateFields as $field) {
            if (!empty($standardFields[$field])) {
                $date = $standardFields[$field];

                if (is_string($date) && strtotime($date) !== false) {
                    return Carbon::parse($date);
                }
            }
        }

        return null;
    }
}
