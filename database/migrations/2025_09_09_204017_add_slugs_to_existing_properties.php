<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Property;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Generate slugs for existing properties that don't have them
        $properties = Property::whereNull('slug')->orWhere('slug', '')->get();
        
        foreach ($properties as $property) {
            $slug = Str::slug($property->title);
            $originalSlug = $slug;
            $counter = 1;
            
            // Ensure slug is unique
            while (Property::where('slug', $slug)->where('id', '!=', $property->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            $property->update(['slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't want to remove slugs in the down migration
        // as they might be needed for existing URLs
    }
};
