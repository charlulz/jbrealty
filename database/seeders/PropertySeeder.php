<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Property;
use App\Models\User;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default agent if one doesn't exist
        $agent = User::firstOrCreate([
            'email' => 'agent@jblandhome.com'
        ], [
            'name' => 'JB Land Agent',
            'password' => bcrypt('password'),
        ]);

        // Create featured properties
        Property::factory(3)
            ->featured()
            ->huntingProperty()
            ->for($agent, 'listingAgent')
            ->create();

        Property::factory(2)
            ->featured()
            ->farmProperty()
            ->withHome()
            ->for($agent, 'listingAgent')
            ->create();

        Property::factory(1)
            ->featured()
            ->waterfrontProperty()
            ->withHome()
            ->for($agent, 'listingAgent')
            ->create();

        // Create regular properties
        Property::factory(10)
            ->huntingProperty()
            ->for($agent, 'listingAgent')
            ->create();

        Property::factory(8)
            ->farmProperty()
            ->for($agent, 'listingAgent')
            ->create();

        Property::factory(5)
            ->waterfrontProperty()
            ->for($agent, 'listingAgent')
            ->create();

        Property::factory(10)
            ->for($agent, 'listingAgent')
            ->create();

        // Create some with homes
        Property::factory(5)
            ->withHome()
            ->for($agent, 'listingAgent')
            ->create();

        echo "Created " . Property::count() . " properties\n";
    }
}
