# JB Land & Home Realty - Property Import System

Complete guide for importing properties with descriptions and images from imagineyourhome.com.

## Overview

This system scrapes property listings from imagineyourhome.com, extracts clean property descriptions (not CSS/HTML junk), and downloads all property images in manageable batches.

## Features

✅ **Property Data Scraping**: Extracts complete property information from individual listing pages  
✅ **Clean Description Extraction**: Gets actual property descriptions from Next.js `PublicRemarks` field  
✅ **Batch Image Downloads**: Downloads 100+ images per property in manageable batches to avoid timeouts  
✅ **Database Integration**: Stores properties and images with proper relationships  
✅ **CSV Export/Import**: Saves scraped data for backup and re-processing  
✅ **Error Handling**: Robust error handling with logging and recovery options  

## Prerequisites

- Laravel application with TALL stack (Tailwind, Alpine, Livewire, Laravel)
- MySQL database with properties and property_images tables
- Storage symlink configured: `php artisan storage:link`
- Required PHP packages: `guzzlehttp/guzzle`, `symfony/dom-crawler`

## Quick Start

### Step 1: Full Property Import (Descriptions Only)
```bash
# Import all properties with correct descriptions (no images yet)
php artisan properties:import scrape "https://www.imagineyourhome.com/realtor/offices/JB-Land-Home-Realty/1860"
```

### Step 2: Batch Image Downloads
```bash
# Download images for first 5 properties that need them
php artisan properties:download-images --only-missing --batch-size=5

# Continue with next batch (system will tell you the next command)
php artisan properties:download-images --start-from=6 --only-missing --batch-size=5

# Repeat until all properties have images
```

## Detailed Process

### 1. Property Scraping & Import

#### Command Options
```bash
php artisan properties:import scrape [URL] [options]

Options:
  --delay=N              Seconds between requests (default: 2)
  --download-images      Download images during import (may timeout)
  --dry-run             Show what would be imported without saving
  --save-csv            Save scraped data to CSV file
```

#### What Happens During Import:
1. **Scrapes listing page** to get basic property data (34 properties found)
2. **Visits each individual property page** for detailed information
3. **Extracts clean descriptions** from Next.js `__NEXT_DATA__` → `PublicRemarks` field
4. **Saves properties** to database with proper slugs for SEO-friendly URLs
5. **Creates CSV backup** in `storage/app/private/imports/`

#### Example Import Output:
```
🏠 Property Import Tool
🕷️ Starting enhanced web scraping from: https://www.imagineyourhome.com/realtor/offices/JB-Land-Home-Realty/1860
✅ Successfully scraped 34 listings with full details
📄 Importing enhanced scraped data from CSV...
✅ IMPORT COMPLETED:
+-----------------------+-------+
| Properties Processed  | 34    |
| Successfully Imported | 34    |
| Failed                | 0     |
+-----------------------+-------+
```

### 2. Batch Image Download System

Since each property has 50-150+ images (totaling ~3,400 images), we use a batch system to avoid timeouts.

#### Command Options
```bash
php artisan properties:download-images [options]

Options:
  --batch-size=N        Properties per batch (default: 5)
  --start-from=N        Starting property number (default: 1)  
  --only-missing        Only download for properties with no images
  --dry-run            Show what would be processed without downloading
```

#### Batch Download Process:
1. **Identifies properties** needing images (33 properties missing images)
2. **Processes 5 properties** at a time to prevent timeouts
3. **Builds correct URLs** using format: `address-city-KY-ZIP/MLS#`
4. **Downloads all images** from each property's Next.js data
5. **Stores images** in organized directory structure
6. **Creates database records** with proper metadata

#### Example Batch Output:
```
🖼️  Property Image Batch Downloader
📋 Properties in this batch:
   #1: 1335 Greenbrier Road, Wallingford, KY 41093 (Images: 0)
   #2: 790 Arthur Lane, Corinth, KY 41010 (Images: 0)
   ...

🚀 Processing batch...
--- #1: 1335 Greenbrier Road, Wallingford, KY 41093 ---
🔗 Fetching: https://www.imagineyourhome.com/address/1335-Greenbrier-Road-Wallingford-KY-41093/25500447
📥 Downloading 149 images...
✅ Downloaded 149 images (Total: 149)

🎉 Batch completed! Downloaded 149 images
🔄 Next batch: php artisan properties:download-images --start-from=2 --only-missing
```

### 3. Complete Workflow Example

```bash
# Step 1: Clean import (descriptions only)
php artisan properties:import scrape "https://www.imagineyourhome.com/realtor/offices/JB-Land-Home-Realty/1860"

# Step 2: Batch download images (repeat until done)
php artisan properties:download-images --only-missing --batch-size=5
php artisan properties:download-images --start-from=6 --only-missing --batch-size=5
php artisan properties:download-images --start-from=11 --only-missing --batch-size=5
# ... continue until all properties have images

# Step 3: Verify completion
php artisan tinker --execute="
echo 'Properties: ' . App\Models\Property::count() . PHP_EOL;
echo 'Total Images: ' . App\Models\PropertyImage::count() . PHP_EOL;
echo 'Properties with Images: ' . App\Models\Property::has('images')->count() . PHP_EOL;
"
```

## File Structure

### Storage Organization
```
storage/app/public/properties/
├── 1/images/exterior/
│   ├── batch_1726089553_1.jpg
│   ├── batch_1726089553_2.jpg
│   └── ...
├── 2/images/exterior/
│   └── ...
└── .../
```

### CSV Backups
```
storage/app/private/imports/
├── scraped_listings_imagineyourhome_enhanced_2025-09-11_20-59-13.csv
└── ...
```

## Key Technical Details

### URL Format Discovery
Properties use this URL pattern:
```
https://www.imagineyourhome.com/address/{STREET-CITY-KY-ZIP}/{MLS_NUMBER}
```

Example: `https://www.imagineyourhome.com/address/1335-Greenbrier-Road-Wallingford-KY-41093/25500447`

### Description Extraction Method
1. **Fetch individual property page**
2. **Extract `__NEXT_DATA__` JSON** from script tag
3. **Navigate to**: `props.pageProps.properties[0].PublicRemarks`
4. **Clean and normalize** text (remove line breaks, excess whitespace)

### Image Extraction Method
1. **Extract `__NEXT_DATA__` JSON** from property page
2. **Navigate to**: `props.pageProps.properties[0].Media[]`
3. **Filter for**: `sparkplatform.com` URLs ending in `.jpg`
4. **Download and store** with proper metadata

## Database Schema

### Properties Table
Key fields used:
- `title` - Property address/title
- `description` - Clean property description (840+ chars)
- `mls_number` - MLS ID for URL building  
- `slug` - SEO-friendly URL slug
- `street_address` - Physical address (often empty, falls back to title)

### PropertyImages Table
- `property_id` - Foreign key to properties
- `filename` - Generated filename (batch_timestamp_index.jpg)
- `url` - Web-accessible URL (/storage/properties/ID/images/exterior/filename.jpg)
- `is_primary` - Boolean (first image per property)
- `category` - Image category (exterior, land, etc.)
- `sort_order` - Display order

## Troubleshooting

### Common Issues

#### 1. "Could not build URL for property"
**Cause**: Missing MLS number or address data  
**Solution**: Check property has `mls_number` and `title` fields populated

#### 2. Import timeouts during image download
**Cause**: Too many images being downloaded at once  
**Solution**: Use batch system instead of `--download-images` flag

#### 3. "Properties: 0, Images: 0"
**Cause**: Database was cleared but import failed  
**Solution**: Re-run the scrape command to restore data

#### 4. Images not displaying in admin
**Cause**: Storage symlink not configured  
**Solution**: Run `php artisan storage:link`

### Verification Commands

```bash
# Check import status
php artisan tinker --execute="
echo 'Properties: ' . App\Models\Property::count() . PHP_EOL;
echo 'Images: ' . App\Models\PropertyImage::count() . PHP_EOL;
\$props = App\Models\Property::has('images')->get();
echo 'Properties with images: ' . \$props->count() . PHP_EOL;
foreach(\$props as \$p) {
    echo '  ' . \$p->title . ': ' . \$p->images->count() . ' images' . PHP_EOL;
}
"

# Check description quality
php artisan tinker --execute="
\$prop = App\Models\Property::first();
echo 'Description length: ' . strlen(\$prop->description) . PHP_EOL;
echo 'Contains CSS: ' . (str_contains(\$prop->description, '.noscript') ? 'YES - BAD' : 'NO - GOOD') . PHP_EOL;
echo 'First 200 chars: ' . substr(\$prop->description, 0, 200) . '...' . PHP_EOL;
"

# Check file system
ls -la storage/app/public/properties/1/images/exterior/ | head -5
```

## Performance Notes

- **Rate Limiting**: 2-second delay between requests (configurable)
- **Memory Usage**: Processes properties individually to avoid memory issues
- **Batch Size**: 5 properties per batch prevents timeouts
- **Total Time**: ~10 minutes per batch of 5 properties (depending on image count)
- **Storage**: ~2-3GB total for all property images

## Legal & Ethical Considerations

⚠️ **Important**: This tool includes built-in ethical scraping practices:
- Respects robots.txt
- Includes rate limiting (2s delays)
- User confirmation required
- Only scrapes data you have permission to use

Always ensure you have permission to scrape and use the data before running these commands.

## Support

For issues or questions:
1. Check the Laravel logs: `tail -f storage/logs/laravel.log`
2. Review the CSV files in `storage/app/private/imports/`
3. Use the verification commands above to check system status

---

**Last Updated**: September 2025  
**System Status**: ✅ Fully Operational - 34 properties with descriptions, 1 property with 149+ images, batch system ready for remaining 32 properties.
