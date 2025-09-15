<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Feature;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            // Land Features
            [
                'name' => 'Water Access',
                'slug' => 'water-access',
                'category' => 'land',
                'description' => 'Direct access to water bodies like lakes, rivers, or streams',
                'sort_order' => 10
            ],
            [
                'name' => 'Mineral Rights',
                'slug' => 'mineral-rights',
                'category' => 'land',
                'description' => 'Mineral rights included with property',
                'sort_order' => 20
            ],
            [
                'name' => 'Timber Rights',
                'slug' => 'timber',
                'category' => 'land',
                'description' => 'Timber harvesting rights included',
                'sort_order' => 30
            ],
            [
                'name' => 'Creek/Stream',
                'slug' => 'creek-stream',
                'category' => 'land',
                'description' => 'Property has creek or stream running through it',
                'sort_order' => 40
            ],
            [
                'name' => 'Pond',
                'slug' => 'pond',
                'category' => 'land',
                'description' => 'Property includes pond or lake',
                'sort_order' => 50
            ],
            [
                'name' => 'Well Water',
                'slug' => 'well-water',
                'category' => 'utilities',
                'description' => 'Property has well for water supply',
                'sort_order' => 60
            ],

            // Improvements
            [
                'name' => 'Home/Cabin',
                'slug' => 'home-cabin',
                'category' => 'improvements',
                'description' => 'Property includes residential structure',
                'sort_order' => 100
            ],
            [
                'name' => 'Barn',
                'slug' => 'barn',
                'category' => 'improvements',
                'description' => 'Property includes barn or large outbuilding',
                'sort_order' => 110
            ],
            [
                'name' => 'Outbuildings',
                'slug' => 'outbuildings',
                'category' => 'improvements',
                'description' => 'Additional structures like sheds, workshops, etc.',
                'sort_order' => 120
            ],
            [
                'name' => 'Fencing',
                'slug' => 'fencing',
                'category' => 'improvements',
                'description' => 'Property is fenced (partial or complete)',
                'sort_order' => 130
            ],

            // Utilities
            [
                'name' => 'Electricity',
                'slug' => 'electricity',
                'category' => 'utilities',
                'description' => 'Electrical service available',
                'sort_order' => 200
            ],
            [
                'name' => 'Public Water',
                'slug' => 'public-water',
                'category' => 'utilities',
                'description' => 'Public water service available',
                'sort_order' => 210
            ],
            [
                'name' => 'Sewer',
                'slug' => 'sewer',
                'category' => 'utilities',
                'description' => 'Public sewer service available',
                'sort_order' => 220
            ],
            [
                'name' => 'Natural Gas',
                'slug' => 'natural-gas',
                'category' => 'utilities',
                'description' => 'Natural gas service available',
                'sort_order' => 230
            ],
            [
                'name' => 'High-Speed Internet',
                'slug' => 'high-speed-internet',
                'category' => 'utilities',
                'description' => 'High-speed internet available',
                'sort_order' => 240
            ],

            // Recreation
            [
                'name' => 'Hunting',
                'slug' => 'hunting',
                'category' => 'recreation',
                'description' => 'Excellent hunting opportunities',
                'sort_order' => 300
            ],
            [
                'name' => 'Fishing',
                'slug' => 'fishing',
                'category' => 'recreation',
                'description' => 'Great fishing opportunities',
                'sort_order' => 310
            ],
            [
                'name' => 'ATV/Trail Access',
                'slug' => 'atv-trails',
                'category' => 'recreation',
                'description' => 'ATV trails or trail access available',
                'sort_order' => 320
            ],
            [
                'name' => 'Wildlife Viewing',
                'slug' => 'wildlife-viewing',
                'category' => 'recreation',
                'description' => 'Excellent wildlife viewing opportunities',
                'sort_order' => 330
            ],

            // Agricultural
            [
                'name' => 'Tillable Land',
                'slug' => 'tillable-land',
                'category' => 'agricultural',
                'description' => 'Property includes tillable/farmable land',
                'sort_order' => 400
            ],
            [
                'name' => 'Pasture Land',
                'slug' => 'pasture-land',
                'category' => 'agricultural',
                'description' => 'Property includes pasture for livestock',
                'sort_order' => 410
            ],
            [
                'name' => 'Hay Fields',
                'slug' => 'hay-fields',
                'category' => 'agricultural',
                'description' => 'Property includes hay production fields',
                'sort_order' => 420
            ],
            [
                'name' => 'Irrigation',
                'slug' => 'irrigation',
                'category' => 'agricultural',
                'description' => 'Irrigation system in place',
                'sort_order' => 430
            ],
        ];

        foreach ($features as $feature) {
            Feature::updateOrCreate(
                ['slug' => $feature['slug']],
                $feature
            );
        }
    }
}
