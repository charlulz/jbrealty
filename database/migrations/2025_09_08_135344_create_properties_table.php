<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            
            // Basic Property Information
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('mls_number')->unique()->nullable();
            $table->enum('status', ['active', 'pending', 'sold', 'off_market', 'draft'])->default('draft');
            $table->enum('property_type', ['hunting', 'farms', 'ranches', 'residential', 'commercial', 'waterfront', 'timber', 'development', 'investment']);
            $table->decimal('price', 15, 2);
            $table->decimal('price_per_acre', 10, 2)->nullable();
            
            // Location Information
            $table->string('street_address')->nullable();
            $table->string('city');
            $table->string('county');
            $table->string('state', 2);
            $table->string('zip_code', 10)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 11, 7)->nullable();
            $table->text('directions')->nullable();
            
            // Acreage Details
            $table->decimal('total_acres', 10, 2);
            $table->decimal('tillable_acres', 10, 2)->nullable();
            $table->decimal('wooded_acres', 10, 2)->nullable();
            $table->decimal('pasture_acres', 10, 2)->nullable();
            $table->decimal('wetland_acres', 10, 2)->nullable();
            
            // Property Features
            $table->boolean('water_access')->default(false);
            $table->boolean('mineral_rights')->default(false);
            $table->boolean('timber_rights')->default(false);
            $table->boolean('water_rights')->default(false);
            $table->boolean('hunting_rights')->default(false);
            $table->boolean('fishing_rights')->default(false);
            
            // Utilities & Infrastructure
            $table->boolean('electric_available')->default(false);
            $table->boolean('water_available')->default(false);
            $table->boolean('sewer_available')->default(false);
            $table->boolean('gas_available')->default(false);
            $table->boolean('internet_available')->default(false);
            $table->string('road_frontage')->nullable();
            $table->enum('road_type', ['paved', 'gravel', 'dirt', 'private'])->nullable();
            
            // Water Features
            $table->text('water_features')->nullable(); // JSON field for ponds, streams, wells, etc.
            $table->string('water_source')->nullable();
            $table->boolean('well_on_property')->default(false);
            
            // Land Characteristics
            $table->enum('topography', ['flat', 'rolling', 'hilly', 'mountainous', 'mixed'])->nullable();
            $table->string('soil_type')->nullable();
            $table->enum('drainage', ['excellent', 'good', 'fair', 'poor'])->nullable();
            $table->string('zoning')->nullable();
            
            // Improvements
            $table->boolean('has_home')->default(false);
            $table->integer('home_sq_ft')->nullable();
            $table->integer('home_bedrooms')->nullable();
            $table->integer('home_bathrooms')->nullable();
            $table->year('home_year_built')->nullable();
            $table->text('outbuildings')->nullable(); // JSON field for barns, sheds, etc.
            $table->boolean('has_barn')->default(false);
            $table->text('fencing')->nullable();
            
            // Agricultural/Commercial Use
            $table->text('agricultural_use')->nullable();
            $table->boolean('currently_leased')->default(false);
            $table->decimal('lease_income', 10, 2)->nullable();
            $table->text('lease_details')->nullable();
            
            // Recreation
            $table->boolean('hunting_lease')->default(false);
            $table->text('recreational_activities')->nullable(); // JSON field
            $table->text('wildlife')->nullable();
            
            // Financial
            $table->decimal('property_tax', 10, 2)->nullable();
            $table->year('property_tax_year')->nullable();
            $table->string('deed_type')->nullable();
            $table->text('easements')->nullable();
            $table->boolean('survey_available')->default(false);
            
            // HOA Information
            $table->boolean('hoa_required')->default(false);
            $table->decimal('hoa_fee', 8, 2)->nullable();
            $table->string('hoa_frequency')->nullable();
            
            // Listing Information
            $table->foreignId('listing_agent_id')->nullable()->constrained('users');
            $table->date('listing_date');
            $table->date('last_updated')->nullable();
            $table->integer('days_on_market')->nullable();
            $table->text('private_remarks')->nullable(); // For agent notes
            $table->text('public_remarks')->nullable(); // For public listing description
            
            // Media
            $table->string('primary_image')->nullable();
            $table->text('virtual_tour_url')->nullable();
            $table->text('video_url')->nullable();
            
            // SEO & Marketing
            $table->string('slug')->unique()->nullable();
            $table->boolean('featured')->default(false);
            $table->integer('views_count')->default(0);
            $table->integer('inquiries_count')->default(0);
            
            // Timestamps
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['status', 'property_type']);
            $table->index(['city', 'county', 'state']);
            $table->index(['price', 'total_acres']);
            $table->index(['listing_date', 'status']);
            $table->index('featured');
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
