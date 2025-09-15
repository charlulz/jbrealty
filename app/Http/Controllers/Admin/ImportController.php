<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PropertyImportService;
use App\Services\PropertyScrapingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImportController extends Controller
{
    protected PropertyImportService $importService;
    protected PropertyScrapingService $scrapingService;

    public function __construct(PropertyImportService $importService, PropertyScrapingService $scrapingService)
    {
        $this->importService = $importService;
        $this->scrapingService = $scrapingService;
    }

    /**
     * Show the import interface
     */
    public function index()
    {
        return view('admin.import.index');
    }

    /**
     * Handle CSV file upload and import
     */
    public function uploadCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
            'source_type' => 'required|in:generic,zillow,mls',
            'update_existing' => 'boolean',
            'download_images' => 'boolean',
        ]);

        try {
            // Store the uploaded file temporarily
            $path = $request->file('csv_file')->store('imports', 'local');
            $fullPath = Storage::disk('local')->path($path);

            $options = [
                'dry_run' => false,
                'update_existing' => $request->boolean('update_existing'),
                'download_images' => $request->boolean('download_images'),
                'chunk_size' => 50, // Smaller chunks for web interface
            ];

            // Import based on source type
            $result = match($request->source_type) {
                'generic' => $this->importService->importFromCsv($fullPath, $options),
                'zillow' => $this->importService->importFromZillowCsv($fullPath, $options),
                'mls' => $this->importService->importFromCsv($fullPath, $options), // Generic CSV for now
                default => throw new \Exception('Invalid source type')
            };

            // Clean up temporary file
            Storage::disk('local')->delete($path);

            return redirect()->back()->with('import_result', $result);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Preview CSV file before import
     */
    public function previewCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        try {
            $path = $request->file('csv_file')->store('imports', 'local');
            $fullPath = Storage::disk('local')->path($path);

            $preview = $this->generateCsvPreview($fullPath);
            
            // Store file path in session for later import
            session(['preview_file_path' => $fullPath]);

            return response()->json([
                'success' => true,
                'preview' => $preview
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Download CSV template
     */
    public function downloadTemplate(string $type = 'generic')
    {
        $templates = [
            'generic' => $this->getGenericTemplate(),
            'zillow' => $this->getZillowTemplate(),
            'mls' => $this->getMlsTemplate(),
        ];

        if (!isset($templates[$type])) {
            abort(404, 'Template not found');
        }

        $filename = "{$type}_property_import_template.csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response($templates[$type], 200, $headers);
    }

    /**
     * Scrape listings from a URL
     */
    public function scrapeUrl(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'delay' => 'nullable|integer|min:1|max:10',
            'save_csv' => 'boolean',
            'dry_run' => 'boolean',
        ]);

        try {
            $options = [
                'delay' => $request->integer('delay', 2),
                'save_to_csv' => $request->boolean('save_csv', true),
            ];

            // Perform scraping
            $result = $this->scrapingService->scrapeListings($request->url, $options);

            if ($request->boolean('dry_run')) {
                return response()->json([
                    'success' => true,
                    'preview' => true,
                    'result' => $result
                ]);
            }

            // If not dry run and we have a CSV, import it
            if (!empty($result['csv_file'])) {
                $importResult = $this->importService->importFromCsv($result['csv_file'], [
                    'dry_run' => false,
                    'update_existing' => true,
                    'download_images' => true,
                    'chunk_size' => 50,
                ]);

                // Combine results
                $result = array_merge($result, $importResult);
            }

            return redirect()->back()->with('import_result', $result);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Scraping failed: ' . $e->getMessage());
        }
    }

    /**
     * Generate CSV preview
     */
    private function generateCsvPreview(string $filePath): array
    {
        $csv = \League\Csv\Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0);
        
        $headers = $csv->getHeader();
        $records = iterator_to_array($csv);
        
        return [
            'headers' => $headers,
            'sample_rows' => array_slice($records, 0, 5), // First 5 rows
            'total_rows' => count($records),
            'detected_mappings' => $this->detectColumnMappings($headers)
        ];
    }

    /**
     * Detect likely column mappings
     */
    private function detectColumnMappings(array $headers): array
    {
        $mappings = [];
        
        foreach ($headers as $header) {
            $normalized = strtolower(trim($header));
            
            $mappings[$header] = match(true) {
                str_contains($normalized, 'title') || str_contains($normalized, 'name') => 'title',
                str_contains($normalized, 'address') || str_contains($normalized, 'street') => 'street_address',
                str_contains($normalized, 'city') => 'city',
                str_contains($normalized, 'state') => 'state',
                str_contains($normalized, 'zip') => 'zip_code',
                str_contains($normalized, 'price') || str_contains($normalized, 'cost') => 'price',
                str_contains($normalized, 'acre') || str_contains($normalized, 'lot') => 'total_acres',
                str_contains($normalized, 'bed') => 'bedrooms',
                str_contains($normalized, 'bath') => 'bathrooms',
                str_contains($normalized, 'sqft') || str_contains($normalized, 'square') => 'sqft',
                str_contains($normalized, 'description') => 'description',
                str_contains($normalized, 'type') => 'property_type',
                str_contains($normalized, 'image') || str_contains($normalized, 'photo') => 'image_urls',
                default => 'unmapped'
            };
        }
        
        return $mappings;
    }

    /**
     * CSV Templates
     */
    private function getGenericTemplate(): string
    {
        $headers = [
            'title',
            'description',
            'price',
            'total_acres',
            'street_address',
            'city',
            'county',
            'state',
            'zip_code',
            'latitude',
            'longitude',
            'property_type',
            'bedrooms',
            'bathrooms',
            'sqft',
            'year_built',
            'water_access',
            'hunting_rights',
            'image_urls',
            'external_id'
        ];

        $sample = [
            "Beautiful Hunting Property",
            "Prime hunting land with mature timber and excellent deer population.",
            "249900",
            "124.5",
            "123 County Road",
            "Olive Hill",
            "Carter",
            "KY",
            "41164",
            "38.4022",
            "-82.9593",
            "hunting",
            "",
            "",
            "",
            "",
            "yes",
            "yes",
            "https://example.com/image1.jpg,https://example.com/image2.jpg",
            "EXT123"
        ];

        return implode(',', $headers) . "\n" . implode(',', array_map(fn($val) => '"' . $val . '"', $sample));
    }

    private function getZillowTemplate(): string
    {
        $headers = [
            'Address',
            'City',
            'State',
            'Zip',
            'Price',
            'Bedrooms',
            'Bathrooms',
            'Square Feet',
            'Lot Size',
            'Year Built',
            'Property Type',
            'Description',
            'Photo URLs',
            'Zillow ID',
            'URL',
            'List Date'
        ];

        $sample = [
            "123 County Road, Olive Hill, KY 41164",
            "Olive Hill",
            "KY",
            "41164",
            "$249,900",
            "",
            "",
            "",
            "124.5 acres",
            "",
            "Land",
            "Prime hunting land with mature timber.",
            "https://photos.zillowstatic.com/image1.jpg,https://photos.zillowstatic.com/image2.jpg",
            "12345678",
            "https://www.zillow.com/homedetails/123-County-Road-Olive-Hill-KY-41164/12345678_zpid/",
            "2024-01-15"
        ];

        return implode(',', $headers) . "\n" . implode(',', array_map(fn($val) => '"' . $val . '"', $sample));
    }

    private function getMlsTemplate(): string
    {
        $headers = [
            'MLS_Number',
            'PropertyType',
            'ListPrice',
            'Address',
            'City',
            'StateOrProvince',
            'PostalCode',
            'County',
            'Latitude',
            'Longitude',
            'LotSizeAcres',
            'BedroomsTotal',
            'BathroomsTotalInteger',
            'LivingArea',
            'YearBuilt',
            'PublicRemarks',
            'ListingDate',
            'PhotosCount',
            'PhotoURL1',
            'PhotoURL2'
        ];

        $sample = [
            "MLS123456",
            "Residential",
            "249900",
            "123 County Road",
            "Olive Hill",
            "KY",
            "41164",
            "Carter",
            "38.4022",
            "-82.9593",
            "124.5",
            "",
            "",
            "",
            "",
            "Prime hunting land with mature timber and excellent deer population.",
            "2024-01-15",
            "2",
            "https://mls.com/photo1.jpg",
            "https://mls.com/photo2.jpg"
        ];

        return implode(',', $headers) . "\n" . implode(',', array_map(fn($val) => '"' . $val . '"', $sample));
    }
}