<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;

class SyncAllPropertyData extends Command
{
    protected $signature = 'properties:sync-all 
                            {--dry-run : Show what would be done without making changes}
                            {--skip-import : Skip the property import step}
                            {--skip-types : Skip the property type fixes}
                            {--skip-dates : Skip the created date fixes}';

    protected $description = 'Complete property database synchronization - imports listings, photos, fixes types and dates';

    public function handle()
    {
        $this->info('🚀 COMPLETE PROPERTY DATABASE SYNC');
        $this->info('=================================');
        
        $dryRun = $this->option('dry-run');
        $skipImport = $this->option('skip-import');
        $skipTypes = $this->option('skip-types');
        $skipDates = $this->option('skip-dates');
        
        if ($dryRun) {
            $this->warn('🔍 DRY RUN MODE - No data will be changed');
        }
        
        $startTime = microtime(true);
        $this->newLine();

        // Step 1: Import/Update Property Listings & Photos
        if (!$skipImport) {
            $this->info('📥 STEP 1: Importing Property Listings & Photos');
            $this->info('============================================');
            
            $importArgs = [];
            if ($dryRun) {
                $this->warn('   ⚠️ Skipping import in dry-run mode (import command doesn\'t support dry-run)');
            } else {
                $importResult = $this->call('import:jeremiah-brown-geographic');
                
                if ($importResult !== 0) {
                    $this->error('❌ Property import failed! Aborting sync.');
                    return Command::FAILURE;
                }
                
                $this->info('   ✅ Property listings and photos imported successfully');
            }
        } else {
            $this->warn('⏭️ SKIPPING: Property import (--skip-import flag)');
        }

        $this->newLine();

        // Step 2: Fix Property Types
        if (!$skipTypes) {
            $this->info('🏠 STEP 2: Fixing Property Types');
            $this->info('===============================');
            
            $typeArgs = [];
            if ($dryRun) {
                $typeArgs['--dry-run'] = true;
            }
            
            $typeResult = $this->call('properties:fix-types', $typeArgs);
            
            if ($typeResult !== 0) {
                $this->error('❌ Property type fixes failed! Continuing with remaining steps...');
            } else {
                $this->info('   ✅ Property types fixed successfully');
            }
        } else {
            $this->warn('⏭️ SKIPPING: Property type fixes (--skip-types flag)');
        }

        $this->newLine();

        // Step 3: Fix Created Dates
        if (!$skipDates) {
            $this->info('📅 STEP 3: Fixing Created Dates');
            $this->info('==============================');
            
            $dateArgs = [];
            if ($dryRun) {
                $dateArgs['--dry-run'] = true;
            }
            
            $dateResult = $this->call('properties:fix-created-dates', $dateArgs);
            
            if ($dateResult !== 0) {
                $this->error('❌ Created date fixes failed! Continuing...');
            } else {
                $this->info('   ✅ Created dates fixed successfully');
            }
        } else {
            $this->warn('⏭️ SKIPPING: Created date fixes (--skip-dates flag)');
        }

        $this->newLine();

        // Final Summary
        $endTime = microtime(true);
        $duration = round($endTime - $startTime, 2);
        
        $this->info('🎉 SYNC COMPLETE!');
        $this->info('================');
        $this->line("   ⏱️ Total time: {$duration} seconds");
        
        if ($dryRun) {
            $this->newLine();
            $this->info('🔍 This was a dry run. Run without --dry-run to apply all changes.');
        } else {
            $this->newLine();
            $this->info('✨ Your property database is now fully synchronized!');
            
            // Show final stats
            $this->showFinalStats();
        }

        return Command::SUCCESS;
    }

    private function showFinalStats()
    {
        $this->line('📊 CURRENT DATABASE STATS:');
        $this->line('=========================');
        
        $totalProperties = Property::count();
        $activeProperties = Property::published()->available()->count();
        $featuredProperties = Property::published()->available()->featured()->count();
        
        $this->line("   📋 Total Properties: {$totalProperties}");
        $this->line("   🟢 Active Properties: {$activeProperties}");  
        $this->line("   ⭐ Featured Properties: {$featuredProperties}");
        
        // Property type breakdown
        $types = Property::selectRaw('property_type, COUNT(*) as count')
            ->groupBy('property_type')
            ->orderByDesc('count')
            ->get();
        
        $this->line('   🏘️ Property Types:');
        foreach ($types as $type) {
            $icon = match($type->property_type) {
                'residential' => '🏠',
                'farms' => '🌾', 
                'hunting' => '🦌',
                'commercial' => '🏢',
                'waterfront' => '🏖️',
                'ranches' => '🤠',
                default => '📦'
            };
            $this->line("      {$icon} {$type->property_type}: {$type->count}");
        }
    }
}
