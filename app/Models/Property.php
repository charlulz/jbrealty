<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class Property extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'mls_number',
        'status',
        'property_type',
        'price',
        'price_per_acre',
        'street_address',
        'city',
        'county',
        'state',
        'zip_code',
        'latitude',
        'longitude',
        'directions',
        'total_acres',
        'tillable_acres',
        'wooded_acres',
        'pasture_acres',
        'wetland_acres',
        'water_access',
        'mineral_rights',
        'timber_rights',
        'water_rights',
        'hunting_rights',
        'fishing_rights',
        'electric_available',
        'water_available',
        'sewer_available',
        'gas_available',
        'internet_available',
        'road_frontage',
        'road_type',
        'water_features',
        'water_source',
        'well_on_property',
        'topography',
        'soil_type',
        'drainage',
        'zoning',
        'has_home',
        'home_sq_ft',
        'home_bedrooms',
        'home_bathrooms',
        'home_year_built',
        'outbuildings',
        'has_barn',
        'fencing',
        'agricultural_use',
        'currently_leased',
        'lease_income',
        'lease_details',
        'hunting_lease',
        'recreational_activities',
        'wildlife',
        'property_tax',
        'property_tax_year',
        'deed_type',
        'easements',
        'survey_available',
        'hoa_required',
        'hoa_fee',
        'hoa_frequency',
        'listing_agent_id',
        'listing_date',
        'last_updated',
        'days_on_market',
        'private_remarks',
        'public_remarks',
        'primary_image',
        'virtual_tour_url',
        'video_url',
        'slug',
        'featured',
        'views_count',
        'inquiries_count',
        'published_at',
        'owner_financing_available',
        'owner_financing_terms',
        'api_source',
        'api_data'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_per_acre' => 'decimal:2',
        'total_acres' => 'decimal:2',
        'tillable_acres' => 'decimal:2',
        'wooded_acres' => 'decimal:2',
        'pasture_acres' => 'decimal:2',
        'wetland_acres' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'water_access' => 'boolean',
        'mineral_rights' => 'boolean',
        'timber_rights' => 'boolean',
        'water_rights' => 'boolean',
        'hunting_rights' => 'boolean',
        'fishing_rights' => 'boolean',
        'electric_available' => 'boolean',
        'water_available' => 'boolean',
        'sewer_available' => 'boolean',
        'gas_available' => 'boolean',
        'internet_available' => 'boolean',
        'well_on_property' => 'boolean',
        'has_home' => 'boolean',
        'has_barn' => 'boolean',
        'currently_leased' => 'boolean',
        'lease_income' => 'decimal:2',
        'hunting_lease' => 'boolean',
        'property_tax' => 'decimal:2',
        'survey_available' => 'boolean',
        'hoa_required' => 'boolean',
        'hoa_fee' => 'decimal:2',
        'featured' => 'boolean',
        'owner_financing_available' => 'boolean',
        'listing_date' => 'date',
        'last_updated' => 'date',
        'published_at' => 'datetime',
        'water_features' => 'array',
        'outbuildings' => 'array',
        'recreational_activities' => 'array',
        'api_data' => 'array',
    ];

    // Relationships
    public function listingAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'listing_agent_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function primaryImage(): HasMany
    {
        return $this->hasMany(PropertyImage::class)->where('is_primary', true);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(PropertyDocument::class);
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(PropertyInquiry::class);
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'property_features')
                    ->withPivot('notes')
                    ->withTimestamps();
    }

    public function savedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'saved_properties')
                    ->withPivot('notes')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('property_type', $type);
    }

    public function scopeByLocation($query, $location)
    {
        return $query->where(function ($q) use ($location) {
            $q->where('city', 'like', "%{$location}%")
              ->orWhere('county', 'like', "%{$location}%")
              ->orWhere('state', 'like', "%{$location}%");
        });
    }

    public function scopeByPriceRange($query, $range)
    {
        $ranges = [
            'under-100k' => [0, 100000],
            '100k-250k' => [100000, 250000],
            '250k-500k' => [250000, 500000],
            '500k-1m' => [500000, 1000000],
            'over-1m' => [1000000, PHP_INT_MAX],
        ];

        if (isset($ranges[$range])) {
            return $query->whereBetween('price', $ranges[$range]);
        }

        return $query;
    }

    public function scopeByAcreage($query, $acreage)
    {
        $ranges = [
            '1-10' => [1, 10],
            '10-50' => [10, 50],
            '50-100' => [50, 100],
            '100-300' => [100, 300],
            '300+' => [300, PHP_INT_MAX],
        ];

        if (isset($ranges[$acreage])) {
            return $query->whereBetween('total_acres', $ranges[$acreage]);
        }

        return $query;
    }

    public function scopeOwnerFinancing($query)
    {
        return $query->where('owner_financing_available', true);
    }

    // Helper methods
    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price);
    }

    public function getFormattedPricePerAcreAttribute()
    {
        return $this->price_per_acre ? '$' . number_format($this->price_per_acre) . '/acre' : null;
    }

    public function getLocationDisplayAttribute()
    {
        return $this->city . ', ' . $this->county . ' County, ' . $this->state;
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Get the primary image URL for this property
     */
    public function getPrimaryImageAttribute()
    {
        $primaryImage = $this->images()->where('is_primary', true)->first();
        
        if ($primaryImage) {
            return $primaryImage->url;
        }
        
        // Fallback to first image if no primary is set
        $firstImage = $this->images()->ordered()->first();
        return $firstImage ? $firstImage->url : null;
    }

    /**
     * Get all images by category
     */
    public function getImagesByCategory($category)
    {
        return $this->images()->where('category', $category)->ordered()->get();
    }

    /**
     * The "boot" method of the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically generate slug when creating a property
        static::creating(function ($property) {
            if (empty($property->slug)) {
                $property->slug = $property->generateUniqueSlug($property->title);
            }
        });

        // Automatically update slug when updating title
        static::updating(function ($property) {
            if ($property->isDirty('title') && empty($property->slug)) {
                $property->slug = $property->generateUniqueSlug($property->title);
            }
        });
    }

    /**
     * Generate a unique slug for the property.
     */
    public function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        // Check if slug exists and make it unique
        while (static::where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
