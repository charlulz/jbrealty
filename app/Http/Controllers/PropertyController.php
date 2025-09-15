<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $properties = Property::published()
            ->active()
            ->with(['images' => function($query) {
                $query->where('is_primary', true)->orWhere(function($q) {
                    $q->orderBy('sort_order')->limit(1);
                });
            }])
            ->when($request->type, function($query, $type) {
                $query->byType($type);
            })
            ->when($request->location, function($query, $location) {
                $query->byLocation($location);
            })
            ->when($request->featured, function($query) {
                $query->featured();
            })
            ->orderByDesc('featured')
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('properties.index', compact('properties'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Property $property)
    {
        // Increment view count
        $property->incrementViews();
        
        // Load relationships
        $property->load([
            'listingAgent',
            'images' => function($query) {
                $query->orderBy('sort_order');
            },
            'documents' => function($query) {
                $query->where('is_public', true)->orderBy('sort_order');
            },
            'features' => function($query) {
                $query->active()->ordered();
            }
        ]);

        // Get similar properties
        $similarProperties = Property::published()
            ->active()
            ->where('id', '!=', $property->id)
            ->where('property_type', $property->property_type)
            ->whereBetween('total_acres', [
                $property->total_acres * 0.5,
                $property->total_acres * 1.5
            ])
            ->with(['images' => function($query) {
                $query->where('is_primary', true)->orWhere(function($q) {
                    $q->orderBy('sort_order')->limit(1);
                });
            }])
            ->limit(3)
            ->get();

        return view('properties.show', compact('property', 'similarProperties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.properties.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Basic Information
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'property_type' => 'required|in:hunting,farms,ranches,residential,commercial,waterfront,timber,development,investment',
            'status' => 'required|in:active,pending,sold,off_market,draft',
            
            // Location
            'street_address' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'county' => 'required|string|max:255',
            'state' => 'required|string|max:2',
            'zip_code' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            
            // Pricing & Acreage
            'price' => 'required|numeric|min:0',
            'total_acres' => 'required|numeric|min:0',
            'tillable_acres' => 'nullable|numeric|min:0',
            'wooded_acres' => 'nullable|numeric|min:0',
            'pasture_acres' => 'nullable|numeric|min:0',
            
            // Features (checkboxes)
            'water_access' => 'nullable|boolean',
            'mineral_rights' => 'nullable|boolean',
            'timber_rights' => 'nullable|boolean',
            'water_rights' => 'nullable|boolean',
            'hunting_rights' => 'nullable|boolean',
            'fishing_rights' => 'nullable|boolean',
            'electric_available' => 'nullable|boolean',
            'water_available' => 'nullable|boolean',
            'sewer_available' => 'nullable|boolean',
            'gas_available' => 'nullable|boolean',
            'internet_available' => 'nullable|boolean',
            
            // Improvements
            'has_home' => 'nullable|boolean',
            'has_barn' => 'nullable|boolean',
            'home_sq_ft' => 'nullable|integer|min:0',
            'home_bedrooms' => 'nullable|integer|min:0',
            'home_bathrooms' => 'nullable|integer|min:0',
            
            // Land Characteristics
            'topography' => 'nullable|in:flat,rolling,hilly,mountainous,mixed',
            'road_type' => 'nullable|in:paved,gravel,dirt,private',
            'survey_available' => 'nullable|boolean',
            
            // Listing
            'featured' => 'nullable|boolean',

            // Images
            'images' => 'nullable|array|max:20',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240', // 10MB max per image
            'exterior_images' => 'nullable|array|max:10',
            'exterior_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
            'land_images' => 'nullable|array|max:10',
            'land_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
            'aerial_images' => 'nullable|array|max:10',
            'aerial_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
            'interior_images' => 'nullable|array|max:10',
            'interior_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
            'amenity_images' => 'nullable|array|max:10',
            'amenity_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
            'document_images' => 'nullable|array|max:10',
            'document_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        // Set defaults for checkboxes (they won't be present if unchecked)
        $checkboxFields = [
            'water_access', 'mineral_rights', 'timber_rights', 'water_rights', 
            'hunting_rights', 'fishing_rights', 'electric_available', 'water_available',
            'sewer_available', 'gas_available', 'internet_available', 'has_home', 
            'has_barn', 'survey_available', 'featured'
        ];

        foreach ($checkboxFields as $field) {
            $validated[$field] = isset($validated[$field]) ? true : false;
        }

        // Set system fields
        $validated['listing_agent_id'] = auth()->id();
        $validated['listing_date'] = now();
        $validated['published_at'] = $validated['status'] === 'active' ? now() : null;

        $property = Property::create($validated);

        // Ensure the property has a slug before redirecting
        if (!$property->slug) {
            $property->slug = $property->generateUniqueSlug($property->title);
            $property->save();
        }

        // Handle image uploads
        $this->handleImageUploads($request, $property);

        return redirect()->route('properties.show', $property)
            ->with('success', 'Property created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Property $property)
    {
        return view('admin.properties.edit', compact('property'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'property_type' => 'required|in:hunting,farms,ranches,residential,commercial,waterfront,timber,development,investment',
            'price' => 'required|numeric|min:0',
            'city' => 'required|string|max:255',
            'county' => 'required|string|max:255',
            'state' => 'required|string|max:2',
            'total_acres' => 'required|numeric|min:0',
            'status' => 'required|in:active,pending,sold,off_market,draft',

            // Images
            'images' => 'nullable|array|max:20',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
            'exterior_images' => 'nullable|array|max:10',
            'exterior_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
            'land_images' => 'nullable|array|max:10',
            'land_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
            'aerial_images' => 'nullable|array|max:10',
            'aerial_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
            'interior_images' => 'nullable|array|max:10',
            'interior_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
            'amenity_images' => 'nullable|array|max:10',
            'amenity_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
            'document_images' => 'nullable|array|max:10',
            'document_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        $validated['published_at'] = $validated['status'] === 'active' ? now() : null;

        $property->update($validated);

        // Ensure the property has a slug after update
        if (!$property->slug) {
            $property->slug = $property->generateUniqueSlug($property->title);
            $property->save();
        }

        // Handle image uploads for updates (appends to existing images)
        $uploadedCount = $this->handleImageUploads($request, $property);
        
        $successMessage = 'Property updated successfully!';
        if ($uploadedCount > 0) {
            $successMessage .= " Added {$uploadedCount} new " . ($uploadedCount === 1 ? 'image' : 'images') . ".";
        }

        return redirect()->route('properties.show', $property)
            ->with('success', $successMessage);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        $property->delete();

        return redirect()->route('properties.index')
            ->with('success', 'Property deleted successfully!');
    }

    /**
     * Handle image uploads for a property
     */
    private function handleImageUploads(Request $request, Property $property)
    {
        $imageCategories = [
            'images' => 'exterior', // Default category for general images
            'exterior_images' => 'exterior',
            'land_images' => 'land',
            'aerial_images' => 'aerial',
            'interior_images' => 'interior',
            'amenity_images' => 'amenities',
            'document_images' => 'documents',
        ];

        $allImages = [];
        
        // Determine starting sort order and whether any image should be primary
        $maxSortOrder = $property->images()->max('sort_order') ?? 0;
        $sortOrder = $maxSortOrder + 1;
        $hasPrimary = $property->images()->where('is_primary', true)->exists();
        $isPrimary = !$hasPrimary; // Only set first image as primary if no primary exists

        foreach ($imageCategories as $inputName => $category) {
            if ($request->hasFile($inputName)) {
                $files = $request->file($inputName);
                
                foreach ($files as $file) {
                    $imageData = $this->processAndStoreImage($file, $property, $category, $sortOrder, $isPrimary);
                    if ($imageData) {
                        $allImages[] = $imageData;
                        $sortOrder++;
                        $isPrimary = false; // Only first image is primary
                    }
                }
            }
        }

        // Bulk insert all images
        if (!empty($allImages)) {
            $property->images()->createMany($allImages);
        }

        return count($allImages);
    }

    /**
     * Process and store a single image
     */
    private function processAndStoreImage($file, Property $property, $category, $sortOrder, $isPrimary = false)
    {
        try {
            // Generate unique filename
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $filename = pathinfo($originalName, PATHINFO_FILENAME);
            $safeName = \Illuminate\Support\Str::slug($filename);
            $uniqueFilename = $safeName . '_' . time() . '_' . uniqid() . '.' . $extension;

            // Store the file
            $path = $file->storeAs(
                "properties/{$property->id}/images/{$category}",
                $uniqueFilename,
                'public'
            );

            // Get image dimensions
            $dimensions = $this->getImageDimensions($file);

            // Create image record data
            return [
                'filename' => $originalName,
                'path' => $path,
                'title' => $this->generateImageTitle($originalName, $category),
                'alt_text' => $this->generateAltText($property, $category),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'width' => $dimensions['width'] ?? null,
                'height' => $dimensions['height'] ?? null,
                'sort_order' => $sortOrder,
                'is_primary' => $isPrimary,
                'category' => $category,
                'created_at' => now(),
                'updated_at' => now(),
            ];

        } catch (\Exception $e) {
            \Log::error('Image upload failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get image dimensions
     */
    private function getImageDimensions($file)
    {
        try {
            $imageInfo = getimagesize($file->getPathname());
            return [
                'width' => $imageInfo[0] ?? null,
                'height' => $imageInfo[1] ?? null,
            ];
        } catch (\Exception $e) {
            return ['width' => null, 'height' => null];
        }
    }

    /**
     * Generate image title based on category
     */
    private function generateImageTitle($originalName, $category)
    {
        $categoryTitles = [
            'exterior' => 'Exterior View',
            'land' => 'Land & Terrain',
            'aerial' => 'Aerial View',
            'interior' => 'Interior View',
            'amenities' => 'Property Amenities',
            'documents' => 'Property Documentation',
        ];

        $baseTitle = $categoryTitles[$category] ?? 'Property Image';
        $filename = pathinfo($originalName, PATHINFO_FILENAME);
        
        return $baseTitle . ' - ' . ucwords(str_replace(['_', '-'], ' ', $filename));
    }

    /**
     * Generate alt text for accessibility
     */
    private function generateAltText(Property $property, $category)
    {
        $altTexts = [
            'exterior' => "Exterior view of {$property->title} in {$property->city}, {$property->state}",
            'land' => "Land and terrain view of {$property->title} - {$property->total_acres} acre property",
            'aerial' => "Aerial view of {$property->title} showing property boundaries and features",
            'interior' => "Interior view of home on {$property->title} property",
            'amenities' => "Property amenities and features at {$property->title}",
            'documents' => "Property documentation for {$property->title}",
        ];

        return $altTexts[$category] ?? "{$property->title} property image";
    }
}
