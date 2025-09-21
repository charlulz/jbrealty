<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Property;

class PropertySearch extends Component
{
    public $propertyType = 'All Properties';
    public $location = '';
    public $priceRange = 'Any Price';
    public $acreage = 'Any Size';
    public $advancedSearchOpen = false;
    
    // Advanced search features
    public $features = [
        'waterAccess' => false,
        'mineralRights' => false,
        'timber' => false,
    ];
    
    public $improvements = [
        'home' => false,
        'barn' => false,
        'utilities' => false,
    ];
    
    public $recreation = [
        'hunting' => false,
        'fishing' => false,
        'atv' => false,
    ];
    
    public $financing = [
        'ownerFinancing' => false,
    ];
    
    public $searchResults = [];
    public $hasSearched = false;

    public function toggleAdvancedSearch()
    {
        $this->advancedSearchOpen = !$this->advancedSearchOpen;
    }

    public function searchProperties()
    {
        $this->validate([
            'propertyType' => 'required|string',
            'location' => 'nullable|string|max:255',
            'priceRange' => 'required|string',
            'acreage' => 'required|string',
        ]);

        // Build search query
        $query = Property::published()->active();

        // Property type filter
        if ($this->propertyType && $this->propertyType !== 'All Properties') {
            $query->byType($this->propertyType);
        }

        // Location filter
        if ($this->location) {
            $query->byLocation($this->location);
        }

        // Price range filter
        if ($this->priceRange && $this->priceRange !== 'Any Price') {
            $query->byPriceRange($this->priceRange);
        }

        // Acreage filter
        if ($this->acreage && $this->acreage !== 'Any Size') {
            $query->byAcreage($this->acreage);
        }

        // Features filter
        $activeFeatures = array_filter($this->features);
        if (!empty($activeFeatures)) {
            foreach ($activeFeatures as $feature => $enabled) {
                if ($enabled) {
                    $query->where($feature, true);
                }
            }
        }

        // Improvements filter
        $activeImprovements = array_filter($this->improvements);
        if (!empty($activeImprovements)) {
            foreach ($activeImprovements as $improvement => $enabled) {
                if ($enabled) {
                    switch ($improvement) {
                        case 'home':
                            $query->where('has_home', true);
                            break;
                        case 'barn':
                            $query->where('has_barn', true);
                            break;
                        case 'utilities':
                            $query->where(function ($q) {
                                $q->where('electric_available', true)
                                  ->orWhere('water_available', true)
                                  ->orWhere('sewer_available', true);
                            });
                            break;
                    }
                }
            }
        }

        // Recreation filter
        $activeRecreation = array_filter($this->recreation);
        if (!empty($activeRecreation)) {
            foreach ($activeRecreation as $recreation => $enabled) {
                if ($enabled) {
                    switch ($recreation) {
                        case 'hunting':
                            $query->where('hunting_rights', true);
                            break;
                        case 'fishing':
                            $query->where('fishing_rights', true);
                            break;
                        case 'atv':
                            // For now, we'll assume properties with recreational activities include ATV
                            break;
                    }
                }
            }
        }

        // Financing filter
        $activeFinancing = array_filter($this->financing);
        if (!empty($activeFinancing)) {
            foreach ($activeFinancing as $financingType => $enabled) {
                if ($enabled) {
                    switch ($financingType) {
                        case 'ownerFinancing':
                            $query->ownerFinancing();
                            break;
                    }
                }
            }
        }

        // Execute search
        $properties = $query->with(['images' => function ($query) {
            $query->where('is_primary', true)->orWhere(function ($q) {
                $q->orderBy('sort_order')->limit(1);
            });
        }])->get();

        // Format results for the view
        $this->searchResults = $properties->map(function ($property) {
            return [
                'id' => $property->id,
                'title' => $property->title,
                'location' => $property->location_display,
                'acres' => $property->total_acres . '± acres',
                'price' => $property->formatted_price,
                'type' => ucfirst($property->property_type),
                'image' => $property->primary_image ?: null,
                'slug' => $property->slug
            ];
        })->toArray();

        $this->hasSearched = true;

        // Flash a success message
        session()->flash('message', 'Found ' . count($this->searchResults) . ' properties matching your criteria!');
        
        // Dispatch browser event to scroll to results
        $this->dispatch('scroll-to-results');
    }

    public function resetSearch()
    {
        $this->reset([
            'propertyType', 'location', 'priceRange', 'acreage',
            'features', 'improvements', 'recreation', 'financing',
            'searchResults', 'hasSearched', 'advancedSearchOpen'
        ]);
        
        $this->propertyType = 'All Properties';
        $this->priceRange = 'Any Price';
        $this->acreage = 'Any Size';
    }

    public function render()
    {
        return view('livewire.property-search');
    }
}
