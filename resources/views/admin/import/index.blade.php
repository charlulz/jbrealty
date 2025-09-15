<x-layouts.app :title="__('Import Properties')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Import Properties</h1>
                <p class="text-gray-600 dark:text-gray-400">Bulk import properties from CSV files, Zillow, or MLS feeds</p>
            </div>
        </div>

        <!-- Import Results -->
        @if (session('import_result'))
            <div class="overflow-hidden rounded-xl border border-green-200 dark:border-green-700 bg-green-50 dark:bg-green-900/20 p-6">
                <h3 class="text-lg font-semibold text-green-900 dark:text-green-300 mb-4">✅ Import Completed</h3>
                
                @php $result = session('import_result'); @endphp
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $result['processed'] ?? 0 }}</div>
                        <div class="text-sm text-green-700 dark:text-green-300">Processed</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $result['imported'] ?? 0 }}</div>
                        <div class="text-sm text-blue-700 dark:text-blue-300">Imported</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $result['updated'] ?? 0 }}</div>
                        <div class="text-sm text-yellow-700 dark:text-yellow-300">Updated</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-600 dark:text-gray-400">{{ $result['skipped'] ?? 0 }}</div>
                        <div class="text-sm text-gray-700 dark:text-gray-300">Skipped</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $result['failed'] ?? 0 }}</div>
                        <div class="text-sm text-red-700 dark:text-red-300">Failed</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $result['images_downloaded'] ?? 0 }}</div>
                        <div class="text-sm text-purple-700 dark:text-purple-300">Images</div>
                    </div>
                </div>

                @if (!empty($result['errors']))
                    <div class="border-t border-green-200 dark:border-green-700 pt-4">
                        <h4 class="font-medium text-green-900 dark:text-green-300 mb-2">Errors Encountered:</h4>
                        <div class="max-h-32 overflow-y-auto">
                            @foreach ($result['errors'] as $error)
                                <div class="text-sm text-red-600 dark:text-red-400">• {{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif

        @if (session('error'))
            <div class="overflow-hidden rounded-xl border border-red-200 dark:border-red-700 bg-red-50 dark:bg-red-900/20 p-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-red-800 dark:text-red-200">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Import Methods -->
        <div class="grid gap-6 lg:grid-cols-3">
            
            <!-- CSV Upload -->
            <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    CSV File Upload
                </h2>
                
                <form action="{{ route('admin.import.csv') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    
                    <!-- File Upload -->
                    <div>
                        <flux:field>
                            <flux:label>CSV File</flux:label>
                            <input type="file" name="csv_file" accept=".csv,.txt" required 
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900 dark:file:text-blue-300">
                            <flux:error name="csv_file" />
                            <flux:description>Upload a CSV file with property data (max 10MB)</flux:description>
                        </flux:field>
                    </div>

                    <!-- Source Type -->
                    <div>
                        <flux:field>
                            <flux:label>Data Source</flux:label>
                            <flux:select name="source_type" required>
                                <option value="generic">Generic CSV Format</option>
                                <option value="zillow">Zillow Export Format</option>
                                <option value="mls">MLS Export Format</option>
                            </flux:select>
                            <flux:error name="source_type" />
                        </flux:field>
                    </div>

                    <!-- Options -->
                    <div class="space-y-3">
                        <flux:checkbox name="update_existing" value="1" label="Update existing properties (match by address or external ID)" />
                        <flux:checkbox name="download_images" value="1" label="Download images from URLs (slower but more complete)" />
                    </div>

                    <div class="flex gap-3">
                        <flux:button type="submit">
                            <flux:icon.arrow-up-tray class="w-4 h-4 mr-2" />
                            Import Properties
                        </flux:button>
                        
                        <flux:button variant="outline" type="button" onclick="previewCsv()">
                            <flux:icon.eye class="w-4 h-4 mr-2" />
                            Preview First
                        </flux:button>
                    </div>
                </form>
            </div>

            <!-- Web Scraping -->
            <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.559-.5-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.559.5.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.498-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"/>
                    </svg>
                    Web Scraping
                </h2>
                
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <svg class="w-5 h-5 text-yellow-400 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-sm">
                            <p class="text-yellow-800 dark:text-yellow-200 font-medium">Legal Notice Required</p>
                            <p class="text-yellow-700 dark:text-yellow-300">Only scrape websites you have permission to use. Always check robots.txt and Terms of Service first.</p>
                        </div>
                    </div>
                </div>
                
                <form action="{{ route('admin.import.scrape') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <!-- URL Input -->
                    <div>
                        <flux:field>
                            <flux:label>Website URL</flux:label>
                            <flux:input type="url" name="url" placeholder="https://www.imagineyourhome.com/realtor/offices/JB-Land-Home-Realty/1860" required />
                            <flux:error name="url" />
                            <flux:description>Enter the full URL of the property listings page</flux:description>
                        </flux:field>
                    </div>

                    <!-- Delay Setting -->
                    <div>
                        <flux:field>
                            <flux:label>Request Delay (seconds)</flux:label>
                            <flux:select name="delay">
                                <option value="1">1 second (fast)</option>
                                <option value="2" selected>2 seconds (recommended)</option>
                                <option value="3">3 seconds (polite)</option>
                                <option value="5">5 seconds (very polite)</option>
                            </flux:select>
                            <flux:description>Time between requests to be respectful to the server</flux:description>
                        </flux:field>
                    </div>

                    <!-- Options -->
                    <div class="space-y-3">
                        <flux:checkbox name="save_csv" value="1" checked label="Save scraped data to CSV file" />
                        <flux:checkbox name="dry_run" value="1" label="Dry run - preview only (don't import to database)" />
                    </div>

                    <div class="flex gap-3">
                        <flux:button type="submit">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.559-.5-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.559.5.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.498-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"/>
                            </svg>
                            Start Scraping
                        </flux:button>
                        
                        <flux:button variant="outline" type="button" onclick="showScrapingHelp()">
                            <flux:icon.question-mark-circle class="w-4 h-4 mr-2" />
                            Help
                        </flux:button>
                    </div>
                </form>
            </div>

            <!-- Template Downloads -->
            <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    CSV Templates
                </h2>
                
                <p class="text-gray-600 dark:text-gray-400 mb-6">Download template CSV files to ensure your data imports correctly.</p>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.import.template', 'generic') }}" 
                       class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white">Generic Template</div>
                            <div class="text-sm text-gray-500">Standard property fields with examples</div>
                        </div>
                        <flux:icon.arrow-down-tray class="w-4 h-4 text-gray-400" />
                    </a>
                    
                    <a href="{{ route('admin.import.template', 'zillow') }}" 
                       class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white">Zillow Template</div>
                            <div class="text-sm text-gray-500">Matches Zillow CSV export format</div>
                        </div>
                        <flux:icon.arrow-down-tray class="w-4 h-4 text-gray-400" />
                    </a>
                    
                    <a href="{{ route('admin.import.template', 'mls') }}" 
                       class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white">MLS Template</div>
                            <div class="text-sm text-gray-500">Standard MLS/RETS field format</div>
                        </div>
                        <flux:icon.arrow-down-tray class="w-4 h-4 text-gray-400" />
                    </a>
                </div>
            </div>
        </div>

        <!-- Command Line Tools -->
        <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3.293 1.293a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 01-1.414-1.414L7.586 10 5.293 7.707a1 1 0 010-1.414zM11 12a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
            </svg>
            Command Line Tools
        </h2>
            
            <p class="text-gray-600 dark:text-gray-400 mb-6">For large imports or automated processes, use the command line tools:</p>
            
            <div class="space-y-4">
                <div class="bg-gray-900 dark:bg-gray-800 rounded-lg p-4 font-mono text-sm">
                    <div class="text-green-400 mb-2"># Import from CSV file</div>
                    <div class="text-gray-300">php artisan properties:import csv /path/to/file.csv --download-images</div>
                </div>
                
                <div class="bg-gray-900 dark:bg-gray-800 rounded-lg p-4 font-mono text-sm">
                    <div class="text-green-400 mb-2"># Dry run to preview import</div>
                    <div class="text-gray-300">php artisan properties:import csv /path/to/file.csv --dry-run</div>
                </div>
                
                <div class="bg-gray-900 dark:bg-gray-800 rounded-lg p-4 font-mono text-sm">
                    <div class="text-green-400 mb-2"># Import from Zillow CSV</div>
                    <div class="text-gray-300">php artisan properties:import zillow-csv /path/to/zillow-export.csv --update-existing</div>
                </div>
                
                <div class="bg-gray-900 dark:bg-gray-800 rounded-lg p-4 font-mono text-sm">
                    <div class="text-green-400 mb-2"># Scrape from ImagineYourHome (dry run first)</div>
                    <div class="text-gray-300">php artisan properties:import scrape "https://www.imagineyourhome.com/realtor/offices/JB-Land-Home-Realty/1860" --dry-run --delay=3</div>
                </div>
                
                <div class="bg-gray-900 dark:bg-gray-800 rounded-lg p-4 font-mono text-sm">
                    <div class="text-green-400 mb-2"># Scrape and import with image downloads</div>
                    <div class="text-gray-300">php artisan properties:import scrape "https://example.com/listings" --download-images --update-existing</div>
                </div>
            </div>
        </div>

        <!-- Import Guidelines -->
        <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                Import Guidelines
            </h2>
            
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">✅ Best Practices</h3>
                    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        <li>• Use the provided CSV templates for best results</li>
                        <li>• Include latitude/longitude for accurate mapping</li>
                        <li>• Use high-quality image URLs for automatic download</li>
                        <li>• Test with a small file first (dry run recommended)</li>
                        <li>• Keep backup of your original data</li>
                        <li>• Use unique external IDs to prevent duplicates</li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">⚠️ Legal Considerations</h3>
                    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        <li>• Only import data you own or have permission to use</li>
                        <li>• Respect website Terms of Service</li>
                        <li>• Don't scrape data without permission</li>
                        <li>• Use official APIs when available</li>
                        <li>• Consider using MLS feeds for licensed data</li>
                        <li>• Verify image usage rights before downloading</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- CSV Preview Modal would go here -->
    <script>
        function previewCsv() {
            // This would open a modal to preview CSV contents
            alert('CSV preview functionality would be implemented here');
        }
        
        function showScrapingHelp() {
            const helpText = `
🕷️ Web Scraping Help:

SUPPORTED SITES:
• ImagineYourHome.com (optimized)
• Zillow.com (basic support)  
• Generic real estate sites

BEFORE SCRAPING:
1. Check robots.txt: [site]/robots.txt
2. Read Terms of Service
3. Start with dry-run mode
4. Use respectful delays (2-3 seconds)

BEST PRACTICES:
• Test with a small page first
• Use longer delays for large sites
• Monitor server response times
• Don't scrape during peak hours
• Consider reaching out for API access

LEGAL NOTES:
• Only scrape public data
• Respect copyright and ToS
• Don't overload servers
• This tool is for educational/personal use

Need help with a specific site? Contact support.
            `;
            
            alert(helpText);
        }
    </script>
</x-layouts.app>
