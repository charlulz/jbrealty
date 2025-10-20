<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use App\Services\FlexMlsApiService;

class FixPropertyTypes extends Command
{
    protected $signature = 'properties:fix-types 
                            {--dry-run : Show what would be changed without making changes}';

    protected $description = 'Fix property types based on MLS PropertySubType data';

    protected FlexMlsApiService $flexMlsService;

    public function __construct(FlexMlsApiService $flexMlsService)
    {
        parent::__construct();
        $this->flexMlsService = $flexMlsService;
    }

    public function handle()
    {
        $this->info('🔧 Fixing Property Types');
        $this->info('=======================');

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
        $typeChanges = [];

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
                $propertySubType = $standardFields['PropertySubType'] ?? $standardFields['PropertyType'] ?? 'Residential';
                $acres = $this->parseAcres($standardFields);
                $newType = $this->mapPropertyType($propertySubType, $acres);
                $currentType = $property->property_type;

                if ($newType !== $currentType) {
                    $this->line("\n   🔄 {$property->title}");
                    $this->line("      MLS PropertySubType: {$propertySubType}");
                    $this->line("      Current: {$currentType}");
                    $this->line("      Should be: {$newType}");

                    // Track changes by type
                    $changeKey = "{$currentType} → {$newType}";
                    $typeChanges[$changeKey] = ($typeChanges[$changeKey] ?? 0) + 1;

                    if (!$dryRun) {
                        $property->property_type = $newType;
                        $property->save();
                        $this->line("      ✅ Updated!");
                    } else {
                        $this->line("      🔍 Would update in real run");
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

        if (!empty($typeChanges)) {
            $this->newLine();
            $this->info('🔄 TYPE CHANGES:');
            foreach ($typeChanges as $change => $count) {
                $this->line("  • {$change}: {$count} properties");
            }
        }

        if ($dryRun) {
            $this->newLine();
            $this->info('🔍 This was a dry run. Run without --dry-run to apply changes.');
        } else {
            $this->newLine();
            $this->info('✅ Property types have been fixed!');
        }

        return Command::SUCCESS;
    }

    private function parseAcres(array $standardFields): float
    {
        $acreFields = ['LotSizeAcres', 'TotalAcres', 'Acres', 'LotSizeArea'];

        foreach ($acreFields as $field) {
            if (isset($standardFields[$field]) && is_numeric($standardFields[$field])) {
                return (float) $standardFields[$field];
            }
        }

        return 0.0;
    }

    private function mapPropertyType(string $type, float $acres = 0): string
    {
        $typeMap = [
            // Residential properties
            'Single Family Residence' => 'residential',
            'Single Family' => 'residential',
            'Residential' => 'residential',
            'Mobile Home' => 'residential',
            'Condo' => 'residential',
            'Townhouse' => 'residential',
            
            // Farm properties  
            'Farm' => 'farms',
            'Farm/Ranch' => 'farms',
            'Agriculture' => 'farms',
            
            // Land/Hunting properties
            'Land' => 'hunting',
            'Vacant Land' => 'hunting',
            'Unimproved Land' => 'hunting',
            
            // Mixed use and other
            'Mixed Use' => 'commercial',
            'Commercial' => 'commercial',
            'Ranch' => 'ranches',
            'Waterfront' => 'waterfront',
            
            // MLS PropertyType codes (fallback for single letters)
            'A' => 'hunting', // Usually Acreage, but PropertySubType should override
            'C' => 'commercial', // Commercial
            'D' => 'commercial', // Usually Mixed Use
            'G' => 'farms', // Usually Farm/Agricultural
            'R' => 'residential', // Residential
        ];

        $mappedType = $typeMap[$type] ?? 'hunting'; // Default to hunting for land properties
        
        // Override hunting/land properties with less than 25 acres to residential
        if (($mappedType === 'hunting' || $mappedType === 'farms' || strtolower($type) === 'land' || strtolower($type) === 'vacant land' || strtolower($type) === 'unimproved land') && $acres > 0 && $acres < 25) {
            return 'residential';
        }
        
        return $mappedType;
    }
}
