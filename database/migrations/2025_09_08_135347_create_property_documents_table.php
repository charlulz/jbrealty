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
        Schema::create('property_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('filename'); // Original filename
            $table->string('path'); // Storage path
            $table->string('url')->nullable(); // Public URL if publicly accessible
            $table->enum('type', [
                'deed', 
                'survey', 
                'plat', 
                'appraisal', 
                'environmental', 
                'lease', 
                'easement', 
                'tax_record', 
                'zoning', 
                'inspection',
                'other'
            ]);
            $table->integer('file_size')->nullable(); // in bytes
            $table->string('mime_type')->nullable();
            $table->boolean('is_public')->default(false); // Whether buyers can view without permission
            $table->boolean('requires_nda')->default(false); // Requires NDA to view
            $table->integer('sort_order')->default(0);
            $table->foreignId('uploaded_by')->nullable()->constrained('users');
            $table->timestamps();
            
            $table->index(['property_id', 'type']);
            $table->index(['property_id', 'is_public']);
            $table->index(['property_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_documents');
    }
};
