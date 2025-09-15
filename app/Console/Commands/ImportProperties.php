<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PropertyImportService;
use App\Services\PropertyScrapingService;
use App\Models\Property;

class ImportProperties extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'properties:import 
                            {source : Import source (csv, zillow-csv, mls, api, scrape)}
                            {file? : Path to import file (required for csv sources) OR URL for scraping}
                            {--dry-run : Preview import without saving}
                            {--update-existing : Update existing properties based on external_id}
                            {--download-images : Download and import images from URLs}
                            {--chunk=100 : Process records in chunks}
                            {--delay=2 : Delay between requests for scraping (seconds)}
                            {--save-csv : Save scraped data to CSV file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import properties from various sources (CSV, APIs, MLS feeds)';

    protected PropertyImportService $importService;
    protected PropertyScrapingService $scrapingService;

    public function __construct(PropertyImportService $importService, PropertyScrapingService $scrapingService)
    {
        parent::__construct();
        $this->importService = $importService;
        $this->scrapingService = $scrapingService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $source = $this->argument('source');
        $file = $this->argument('file');
        $dryRun = $this->option('dry-run');
        $updateExisting = $this->option('update-existing');
        $downloadImages = $this->option('download-images');
        $chunkSize = (int) $this->option('chunk');

        $this->info("🏠 Property Import Tool");
        $this->info("Source: {$source}");
        
        if ($dryRun) {
            $this->warn("DRY RUN MODE - No data will be saved");
        }

        try {
            $result = match($source) {
                'csv' => $this->importFromCsv($file, $dryRun, $updateExisting, $downloadImages, $chunkSize),
                'zillow-csv' => $this->importFromZillowCsv($file, $dryRun, $updateExisting, $downloadImages, $chunkSize),
                'mls' => $this->importFromMls($dryRun, $updateExisting, $downloadImages),
                'api' => $this->importFromApi($dryRun, $updateExisting, $downloadImages),
                'scrape' => $this->scrapeFromWeb($file, $dryRun, $updateExisting, $downloadImages),
                default => $this->error("❌ Unsupported import source: {$source}")
            };

            if ($result) {
                $this->displayResults($result, $dryRun);
            }

        } catch (\Exception $e) {
            $this->error("❌ Import failed: " . $e->getMessage());
            if ($this->option('verbose')) {
                $this->error($e->getTraceAsString());
            }
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function scrapeFromWeb($url, $dryRun, $updateExisting, $downloadImages)
    {
        if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
            $this->error("❌ Valid URL is required for web scraping: {$url}");
            return false;
        }

        // Show important legal warning
        $this->newLine();
        $this->warn("⚠️  LEGAL & ETHICAL SCRAPING NOTICE:");
        $this->warn("   • Only scrape data you have permission to use");
        $this->warn("   • Respect the website's robots.txt and Terms of Service");
        $this->warn("   • This tool includes rate limiting to be respectful");
        $this->warn("   • Consider contacting the site for official API access");
        $this->newLine();
        
        if (!$this->confirm("Do you acknowledge and agree to scrape responsibly?")) {
            $this->info("Scraping cancelled by user.");
            return false;
        }

        // Clear existing properties first (if not dry run)
        if (!$dryRun) {
            if ($this->confirm("Clear all existing properties from the database first?")) {
                $this->info("🗑️ Clearing existing properties...");
                
                try {
                    // Disable foreign key checks temporarily
                    \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                    
                    // Clear related tables first
                    \DB::table('property_images')->truncate();
                    \DB::table('property_documents')->truncate();
                    \DB::table('properties')->truncate();
                    
                    // Re-enable foreign key checks
                    \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
                    
                    $this->info("✅ Database cleared successfully.");
                } catch (\Exception $e) {
                    // Fallback: delete records instead of truncate
                    $this->warn("Truncate failed, using delete instead...");
                    \App\Models\PropertyImage::query()->delete();
                    \App\Models\Property::query()->delete();
                    $this->info("✅ Database cleared successfully using delete.");
                }
            }
        }

        $this->info("🕷️ Starting enhanced web scraping from: {$url}");
        $this->info("   Mode: Deep scraping (individual property pages)");
        $this->info("   Delay between requests: {$this->option('delay')} seconds");
        
        $options = [
            'delay' => (int) $this->option('delay'),
            'save_to_csv' => $this->option('save-csv') || !$dryRun,
            'deep_scrape' => true, // Enable deep scraping mode
            'download_images' => $downloadImages,
            // 'limit' => 1, // Removed limit for full import
        ];
        
        // Perform the enhanced scraping
        $scrapeResult = $this->scrapingService->scrapeListingsWithDetails($url, $options);
        
        if (!empty($scrapeResult['errors'])) {
            $this->error("❌ Scraping encountered errors:");
            foreach ($scrapeResult['errors'] as $error) {
                $this->error("   • {$error}");
            }
        }
        
        if (empty($scrapeResult['listings'])) {
            $this->error("❌ No listings were found or extracted from the URL");
            return false;
        }
        
        $this->info("✅ Successfully scraped {$scrapeResult['processed']} listings with full details");
        
        // If dry run, just show the scraped data
        if ($dryRun) {
            $this->displayScrapedListings($scrapeResult['listings']);
            return $scrapeResult;
        }
        
        // If we have a CSV file, import it
        if (!empty($scrapeResult['csv_file'])) {
            $this->info("📄 Importing enhanced scraped data from CSV...");
            
            $importResult = $this->importService->importFromCsv($scrapeResult['csv_file'], [
                'dry_run' => false,
                'update_existing' => $updateExisting,
                'download_images' => $downloadImages,
                'chunk_size' => 25, // Smaller chunks for detailed imports
                'progress_callback' => function($current, $total, $item) {
                    $title = $item['title'] ?? 'Unknown';
                    $this->info("   Importing {$current}/{$total}: {$title}");
                }
            ]);
            
            // Combine scraping and import results
            return array_merge($scrapeResult, $importResult);
        }
        
        return $scrapeResult;
    }

    private function displayScrapedListings(array $listings): void
    {
        $this->newLine();
        $this->info("🔍 SCRAPED LISTINGS PREVIEW:");
        $this->newLine();
        
        $displayCount = min(5, count($listings));
        
        for ($i = 0; $i < $displayCount; $i++) {
            $listing = $listings[$i];
            
            $this->info("Listing " . ($i + 1) . ":");
            $this->line("  Title: " . ($listing['title'] ?? 'N/A'));
            $this->line("  Price: " . ($listing['price'] ?? 'N/A'));
            $this->line("  Address: " . ($listing['address'] ?? 'N/A'));
            $this->line("  Type: " . ($listing['property_type'] ?? 'N/A'));
            $this->line("  Source: " . ($listing['source'] ?? 'N/A'));
            if (!empty($listing['listing_url'])) {
                $this->line("  URL: " . $listing['listing_url']);
            }
            $this->newLine();
        }
        
        if (count($listings) > $displayCount) {
            $this->info("... and " . (count($listings) - $displayCount) . " more listings");
        }
    }

    private function importFromCsv($file, $dryRun, $updateExisting, $downloadImages, $chunkSize)
    {
        if (!$file || !file_exists($file)) {
            $this->error("❌ CSV file is required and must exist: {$file}");
            return false;
        }

        $this->info("📄 Importing from CSV: {$file}");
        
        return $this->importService->importFromCsv($file, [
            'dry_run' => $dryRun,
            'update_existing' => $updateExisting,
            'download_images' => $downloadImages,
            'chunk_size' => $chunkSize,
            'progress_callback' => function($current, $total, $item) {
                $title = $item['title'] ?? 'Unknown';
                $this->info("Processing {$current}/{$total}: {$title}");
            }
        ]);
    }

    private function importFromZillowCsv($file, $dryRun, $updateExisting, $downloadImages, $chunkSize)
    {
        if (!$file || !file_exists($file)) {
            $this->error("❌ Zillow CSV file is required and must exist: {$file}");
            return false;
        }

        $this->info("🏡 Importing from Zillow CSV: {$file}");
        
        return $this->importService->importFromZillowCsv($file, [
            'dry_run' => $dryRun,
            'update_existing' => $updateExisting,
            'download_images' => $downloadImages,
            'chunk_size' => $chunkSize,
            'progress_callback' => function($current, $total, $item) {
                $address = $item['address'] ?? 'Unknown';
                $this->info("Processing {$current}/{$total}: {$address}");
            }
        ]);
    }

    private function importFromMls($dryRun, $updateExisting, $downloadImages)
    {
        $this->info("🏢 Importing from MLS feed...");
        
        return $this->importService->importFromMls([
            'dry_run' => $dryRun,
            'update_existing' => $updateExisting,
            'download_images' => $downloadImages,
            'progress_callback' => function($current, $total, $item) {
                $mlsNumber = $item['mls_number'] ?? 'Unknown';
                $this->info("Processing MLS {$current}/{$total}: {$mlsNumber}");
            }
        ]);
    }

    private function importFromApi($dryRun, $updateExisting, $downloadImages)
    {
        $this->info("🌐 Importing from API sources...");
        
        return $this->importService->importFromApi([
            'dry_run' => $dryRun,
            'update_existing' => $updateExisting,
            'download_images' => $downloadImages,
            'progress_callback' => function($current, $total, $item) {
                $title = $item['title'] ?? 'Unknown';
                $this->info("Processing API {$current}/{$total}: {$title}");
            }
        ]);
    }

    private function displayResults($result, $dryRun)
    {
        $this->newLine();
        
        if ($dryRun) {
            $this->info("🔍 DRY RUN RESULTS:");
        } else {
            $this->info("✅ IMPORT COMPLETED:");
        }
        
        $this->table(['Metric', 'Count'], [
            ['Properties Processed', $result['processed'] ?? 0],
            ['Successfully Imported', $result['imported'] ?? 0],
            ['Updated', $result['updated'] ?? 0],
            ['Skipped (Duplicates)', $result['skipped'] ?? 0],
            ['Failed', $result['failed'] ?? 0],
            ['Images Downloaded', $result['images_downloaded'] ?? 0],
        ]);

        if (!empty($result['errors'])) {
            $this->newLine();
            $this->warn("⚠️  ERRORS ENCOUNTERED:");
            foreach ($result['errors'] as $error) {
                $this->error("• {$error}");
            }
        }

        if (!$dryRun && ($result['imported'] ?? 0) > 0) {
            $this->newLine();
            $this->info("🎉 Import successful! You can now view the properties in your admin panel.");
        }
    }
}