<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user for JB Land & Home Realty
        User::factory()->create([
            'name' => 'JB Land & Home Admin',
            'email' => 'admin@jblandandhome.com',
            'password' => Hash::make('JbLand2025!@!@'), // Change this in production!
            'email_verified_at' => now(),
        ]);

        $this->command->info('✅ Created admin user: admin@jblandandhome.com');
    }
}
