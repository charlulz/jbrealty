<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $propertyTypes = ['hunting', 'farms', 'ranches', 'residential', 'commercial', 'waterfront', 'timber'];
        $states = ['VA', 'MD', 'WV', 'NC', 'PA', 'TN'];
        $counties = ['Madison', 'Fauquier', 'Orange', 'Culpeper', 'Greene', 'Albemarle', 'Rappahannock'];
        $cities = ['Gordonsville', 'Orange', 'Culpeper', 'Madison', 'Warrenton', 'Front Royal', 'Winchester'];
        
        $acres = $this->faker->numberBetween(5, 500);
        $price = $acres * $this->faker->numberBetween(3000, 15000);
        
        return [
            'title' => $this->generatePropertyTitle(),
            'description' => $this->faker->paragraphs(3, true),
            'status' => 'active',
            'property_type' => $this->faker->randomElement($propertyTypes),
            'price' => $price,
            'price_per_acre' => round($price / $acres, 2),
            'city' => $this->faker->randomElement($cities),
            'county' => $this->faker->randomElement($counties),
            'state' => $this->faker->randomElement($states),
            'zip_code' => $this->faker->postcode,
            'latitude' => $this->faker->latitude(38, 40),
            'longitude' => $this->faker->longitude(-79, -77),
            'total_acres' => $acres,
            'tillable_acres' => $this->faker->optional(0.6)->numberBetween(0, $acres * 0.7),
            'wooded_acres' => $this->faker->optional(0.8)->numberBetween(0, $acres * 0.9),
            'pasture_acres' => $this->faker->optional(0.4)->numberBetween(0, $acres * 0.5),
            'water_access' => $this->faker->boolean(30),
            'mineral_rights' => $this->faker->boolean(40),
            'timber_rights' => $this->faker->boolean(60),
            'water_rights' => $this->faker->boolean(25),
            'hunting_rights' => $this->faker->boolean(70),
            'fishing_rights' => $this->faker->boolean(35),
            'electric_available' => $this->faker->boolean(80),
            'water_available' => $this->faker->boolean(70),
            'sewer_available' => $this->faker->boolean(30),
            'gas_available' => $this->faker->boolean(40),
            'internet_available' => $this->faker->boolean(60),
            'road_type' => $this->faker->randomElement(['paved', 'gravel', 'dirt']),
            'topography' => $this->faker->randomElement(['flat', 'rolling', 'hilly', 'mixed']),
            'drainage' => $this->faker->randomElement(['excellent', 'good', 'fair']),
            'has_home' => $this->faker->boolean(25),
            'home_sq_ft' => $this->faker->optional(0.25)->numberBetween(800, 3500),
            'home_bedrooms' => $this->faker->optional(0.25)->numberBetween(2, 5),
            'home_bathrooms' => $this->faker->optional(0.25)->numberBetween(1, 3),
            'has_barn' => $this->faker->boolean(40),
            'currently_leased' => $this->faker->boolean(20),
            'lease_income' => $this->faker->optional(0.2)->numberBetween(2000, 15000),
            'hunting_lease' => $this->faker->boolean(30),
            'property_tax' => $this->faker->numberBetween(500, 8000),
            'property_tax_year' => $this->faker->year,
            'survey_available' => $this->faker->boolean(60),
            'listing_agent_id' => User::factory(),
            'listing_date' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'days_on_market' => $this->faker->numberBetween(1, 180),
            'public_remarks' => $this->faker->paragraphs(2, true),
            'featured' => $this->faker->boolean(15),
            'views_count' => $this->faker->numberBetween(0, 500),
            'inquiries_count' => $this->faker->numberBetween(0, 25),
            'published_at' => now(),
            'slug' => null, // Will be auto-generated from title
        ];
    }
    
    private function generatePropertyTitle(): string
    {
        $adjectives = ['Beautiful', 'Stunning', 'Prime', 'Exceptional', 'Magnificent', 'Pristine', 'Spectacular'];
        $landTypes = ['Hunting Land', 'Farm', 'Ranch', 'Estate', 'Tract', 'Acreage', 'Property'];
        $features = ['with Creek', 'with Pond', 'with Views', 'with Timber', 'with Home', ''];
        
        return $this->faker->randomElement($adjectives) . ' ' . 
               $this->faker->randomElement($landTypes) . ' ' .
               $this->faker->randomElement($features);
    }

    public function huntingProperty()
    {
        return $this->state([
            'property_type' => 'hunting',
            'hunting_rights' => true,
            'timber_rights' => true,
            'wooded_acres' => function (array $attributes) {
                return $attributes['total_acres'] * 0.8;
            },
        ]);
    }

    public function farmProperty()
    {
        return $this->state([
            'property_type' => 'farms',
            'tillable_acres' => function (array $attributes) {
                return $attributes['total_acres'] * 0.6;
            },
            'has_barn' => true,
            'electric_available' => true,
            'water_available' => true,
        ]);
    }

    public function waterfrontProperty()
    {
        return $this->state([
            'property_type' => 'waterfront',
            'water_access' => true,
            'fishing_rights' => true,
            'water_rights' => true,
        ]);
    }

    public function withHome()
    {
        return $this->state([
            'has_home' => true,
            'home_sq_ft' => $this->faker->numberBetween(1200, 4000),
            'home_bedrooms' => $this->faker->numberBetween(2, 5),
            'home_bathrooms' => $this->faker->numberBetween(2, 4),
            'home_year_built' => $this->faker->numberBetween(1950, 2020),
            'electric_available' => true,
            'water_available' => true,
        ]);
    }

    public function featured()
    {
        return $this->state([
            'featured' => true,
            'views_count' => $this->faker->numberBetween(100, 1000),
            'inquiries_count' => $this->faker->numberBetween(10, 50),
        ]);
    }
}
