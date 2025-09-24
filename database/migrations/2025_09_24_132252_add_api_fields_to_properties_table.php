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
        Schema::table('properties', function (Blueprint $table) {
            $table->string('api_source')->nullable()->after('published_at')
                  ->comment('Source of property data (flexmls, scraped, manual, etc.)');
            $table->json('api_data')->nullable()->after('api_source')
                  ->comment('Raw API response data for reference');
            
            // Add index for api_source for easier querying
            $table->index('api_source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropIndex(['api_source']);
            $table->dropColumn(['api_source', 'api_data']);
        });
    }
};
