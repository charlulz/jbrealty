<?php

namespace App\Console\Commands;

use App\Models\Property;
use App\Services\PropertyScrapingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DownloadPropertyImages extends Command
{
    protected $signature = 'properties:download-images 
                            {--batch-size=5 : Number of properties to process in each batch}
                            {--start-from=1 : Starting property number (1-based)}
                            {--only-missing : Only download for properties with no images}
                            {--dry-run : Show what would be processed without downloading}';

    protected $description = 'Download images for properties in manageable batches';

    protected PropertyScrapingService $scrapingService;

    public function __construct(PropertyScrapingService $scrapingService)
    {
        parent::__construct();
        $this->scrapingService = $scrapingService;
    }

    public function handle(): int
    {
        $batchSize = (int) $this->option('batch-size');
        $startFrom = (int) $this->option('start-from');
        $onlyMissing = $this->option('only-missing');
        $dryRun = $this->option('dry-run');

        $this->info("🖼️  Property Image Batch Downloader");
        $this->info("   Batch size: {$batchSize} properties");
        $this->info("   Starting from: #{$startFrom}");
        
        if ($dryRun) {
            $this->warn("   🔍 DRY RUN MODE - No images will be downloaded");
        }

        // Get properties that need images
        $query = Property::orderBy('id');
        
        if ($onlyMissing) {
            $query->doesntHave('images');
            $this->info("   Filter: Only properties with no images");
        }

        $totalProperties = $query->count();
        $properties = $query->skip($startFrom - 1)->take($batchSize)->get();
        
        $this->info("   Total properties: {$totalProperties}");
        $this->info("   This batch: {$properties->count()} properties");

        if ($properties->count() === 0) {
            $this->warn("❌ No properties found for this batch");
            return 1;
        }

        // Show properties in batch
        $this->info("\n📋 Properties in this batch:");
        foreach ($properties as $index => $property) {
            $imageCount = $property->images->count();
            $globalIndex = $startFrom + $index;
            $this->info("   #{$globalIndex}: {$property->title} (Images: {$imageCount})");
        }

        if ($dryRun) {
            $nextStart = $startFrom + $batchSize;
            $this->info("\n✅ DRY RUN: Next batch --start-from={$nextStart}");
            return 0;
        }

        if (!$this->confirm("\nDownload images for these {$properties->count()} properties?")) {
            return 1;
        }

        // Process properties
        $this->processPropertyBatch($properties, $startFrom);

        $nextStart = $startFrom + $batchSize;
        if ($nextStart <= $totalProperties) {
            $this->info("\n🔄 Next batch: php artisan properties:download-images --start-from={$nextStart} --only-missing");
        } else {
            $this->info("\n🏁 All properties processed!");
        }

        return 0;
    }

    private function processPropertyBatch($properties, $startFrom)
    {
        $this->info("\n🚀 Processing batch...");
        $totalImagesDownloaded = 0;

        foreach ($properties as $index => $property) {
            $globalIndex = $startFrom + $index;
            $this->info("\n--- #{$globalIndex}: {$property->title} ---");
            
            try {
                $beforeCount = $property->images->count();
                $imageCount = $this->downloadPropertyImages($property);
                $newImages = $imageCount - $beforeCount;
                
                if ($newImages > 0) {
                    $this->info("✅ Downloaded {$newImages} images (Total: {$imageCount})");
                    $totalImagesDownloaded += $newImages;
                } else {
                    $this->warn("⚠️  No new images downloaded");
                }

                sleep(2); // Brief pause between properties

            } catch (\Exception $e) {
                $this->error("❌ Error: " . $e->getMessage());
            }
        }

        $this->info("\n🎉 Batch completed! Downloaded {$totalImagesDownloaded} images");
    }

    private function downloadPropertyImages(Property $property): int
    {
        // Build property URL for imagineyourhome.com
        $propertyUrl = $this->buildPropertyUrl($property);
        if (!$propertyUrl) {
            $this->warn("Could not build URL for property");
            return $property->images->count();
        }

        $this->info("🔗 Fetching: {$propertyUrl}");

        // Get page content
        $html = $this->fetchPropertyPage($propertyUrl);
        if (!$html) {
            return $property->images->count();
        }

        // Extract image URLs from Next.js data
        $imageUrls = $this->extractImageUrls($html);
        if (empty($imageUrls)) {
            $this->warn("No images found on page");
            return $property->images->count();
        }

        // Download and store images
        return $this->downloadAndStoreImages($property, $imageUrls);
    }

    private function buildPropertyUrl(Property $property): ?string
    {
        $mlsId = $property->mls_number;  // Fixed: use mls_number instead of mls_id
        $address = $property->street_address ?: $property->title;  // Use title if street_address is empty

        if (!$mlsId || !$address) {
            return null;
        }

        // Create address slug
        $addressSlug = $this->createAddressSlug($address);
        $url = "https://www.imagineyourhome.com/address/{$addressSlug}/{$mlsId}";
        
        return $url;
    }

    private function createAddressSlug(string $address): string
    {
        // Keep the full address including state and ZIP in format: Street-City-KY-ZIP
        if (preg_match('/^(.*?),\s*([^,]+),\s*(KY|Kentucky)\s*(\d{5}).*$/i', $address, $matches)) {
            $street = trim($matches[1]);
            $city = trim($matches[2]);
            $state = 'KY'; // Always use KY format
            $zip = trim($matches[4]);
            $fullAddress = "{$street}, {$city}, {$state} {$zip}";
        } else {
            // Fallback - use the address as-is
            $fullAddress = $address;
        }
        
        // Convert to slug: replace non-alphanumeric with nothing, spaces with hyphens
        $slug = preg_replace('/[^\w\s-]/', '', $fullAddress);
        $slug = preg_replace('/\s+/', '-', trim($slug));
        return $slug;
    }

    private function fetchPropertyPage(string $url): ?string
    {
        try {
            $context = stream_context_create([
                'http' => [
                    'header' => "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36\r\n",
                    'timeout' => 30
                ]
            ]);
            
            return file_get_contents($url, false, $context);
        } catch (\Exception $e) {
            $this->warn("Failed to fetch page: " . $e->getMessage());
            return null;
        }
    }

    private function extractImageUrls(string $html): array
    {
        if (preg_match('/<script id="__NEXT_DATA__" type="application\/json">(.*?)<\/script>/s', $html, $matches)) {
            $jsonData = json_decode($matches[1], true);
            
            if ($jsonData && isset($jsonData['props']['pageProps']['properties'][0]['Media'])) {
                $imageUrls = [];
                
                foreach ($jsonData['props']['pageProps']['properties'][0]['Media'] as $media) {
                    if (isset($media['MediaURL']) && 
                        str_contains($media['MediaURL'], 'sparkplatform.com') && 
                        str_contains($media['MediaURL'], '.jpg')) {
                        $imageUrls[] = $media['MediaURL'];
                    }
                }
                
                return $imageUrls;
            }
        }
        
        return [];
    }

    private function downloadAndStoreImages(Property $property, array $imageUrls): int
    {
        $this->info("📥 Downloading " . count($imageUrls) . " images...");
        $downloaded = 0;
        
        $maxSortOrder = $property->images()->max('sort_order') ?? 0;
        $hasPrimary = $property->images()->where('is_primary', true)->exists();
        
        foreach ($imageUrls as $index => $imageUrl) {
            try {
                $imageContent = file_get_contents($imageUrl);
                if ($imageContent === false) continue;

                // Create filename and directory
                $filename = 'batch_' . time() . '_' . ($index + 1) . '.jpg';
                $directory = "properties/{$property->id}/images/exterior";
                $fullPath = storage_path("app/public/{$directory}");
                
                if (!file_exists($fullPath)) {
                    mkdir($fullPath, 0755, true);
                }

                // Save file
                file_put_contents("{$fullPath}/{$filename}", $imageContent);

                // Create database record
                $property->images()->create([
                    'filename' => $filename,
                    'original_filename' => basename($imageUrl),
                    'path' => "{$directory}/{$filename}",
                    'url' => "/storage/{$directory}/{$filename}",
                    'category' => 'exterior',
                    'is_primary' => !$hasPrimary && $index === 0,
                    'sort_order' => $maxSortOrder + $index + 1,
                    'file_size' => strlen($imageContent),
                    'mime_type' => 'image/jpeg',
                    'alt_text' => "{$property->title} - Property Image",
                    'title' => "Property Image " . ($index + 1)
                ]);

                $downloaded++;
                
                // Don't set primary for subsequent images
                if ($index === 0) $hasPrimary = true;

            } catch (\Exception $e) {
                $this->warn("Failed to download image " . ($index + 1));
            }
        }

        return $property->fresh()->images->count();
    }
}