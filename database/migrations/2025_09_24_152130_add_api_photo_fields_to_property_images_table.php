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
        Schema::table('property_images', function (Blueprint $table) {
            $table->string('api_photo_id')->nullable()->after('url')
                  ->comment('Spark API photo ID for external photo tracking');
            
            $table->json('photo_urls')->nullable()->after('api_photo_id')
                  ->comment('JSON object containing all Spark API photo URLs (thumb, 640, 800, 1024, etc.)');
                  
            $table->json('tags')->nullable()->after('photo_urls')
                  ->comment('Photo tags from API (room types, categories, etc.)');
            
            $table->string('api_source')->nullable()->after('tags')
                  ->comment('Source of photo data (flexmls, local, etc.)');
            
            // Add index for API photo tracking
            $table->index('api_photo_id');
            $table->index('api_source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_images', function (Blueprint $table) {
            $table->dropIndex(['api_photo_id']);
            $table->dropIndex(['api_source']);
            $table->dropColumn(['api_photo_id', 'photo_urls', 'tags', 'api_source']);
        });
    }
};
