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
        Schema::create('property_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('filename'); // Original filename
            $table->string('path'); // Storage path
            $table->string('url')->nullable(); // Public URL
            $table->string('title')->nullable();
            $table->text('caption')->nullable();
            $table->text('alt_text')->nullable(); // For accessibility
            $table->integer('file_size')->nullable(); // in bytes
            $table->string('mime_type')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_primary')->default(false); // Primary listing image
            $table->enum('category', ['exterior', 'interior', 'land', 'aerial', 'amenities', 'documents'])->default('exterior');
            $table->timestamps();
            
            $table->index(['property_id', 'is_primary']);
            $table->index(['property_id', 'category']);
            $table->index(['property_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_images');
    }
};
