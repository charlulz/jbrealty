<?php

namespace App\Livewire;

use Livewire\Component;

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

        // In a real application, this would query the database
        $searchCriteria = [
            'property_type' => $this->propertyType,
            'location' => $this->location,
            'price_range' => $this->priceRange,
            'acreage' => $this->acreage,
            'features' => array_filter($this->features),
            'improvements' => array_filter($this->improvements),
            'recreation' => array_filter($this->recreation),
        ];

        // Simulate search results for demo
        $this->searchResults = [
            [
                'id' => 1,
                'title' => "Hunter's Paradise",
                'location' => 'Madison County, VA',
                'acres' => '124± acres',
                'price' => '$249,900',
                'type' => 'Hunting Land',
                'image' => 'placeholder'
            ],
            [
                'id' => 2,
                'title' => 'Historic Farm Estate',
                'location' => 'Fauquier County, VA',
                'acres' => '89± acres',
                'price' => '$425,000',
                'type' => 'Farm',
                'image' => 'placeholder'
            ]
        ];

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
            'features', 'improvements', 'recreation', 
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
