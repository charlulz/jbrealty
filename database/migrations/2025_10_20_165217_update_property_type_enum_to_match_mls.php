<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, change the column type to allow MLS property types
        Schema::table('properties', function (Blueprint $table) {
            $table->string('property_type')->change();
        });

        // Then update existing data to match MLS property types
        DB::statement("
            UPDATE properties 
            SET property_type = CASE 
                WHEN property_type = 'residential' THEN 'Single Family Residence'
                WHEN property_type = 'farms' THEN 'Farm'
                WHEN property_type = 'hunting' THEN 'Unimproved Land'
                WHEN property_type = 'commercial' THEN 'Mixed Use'
                WHEN property_type = 'ranches' THEN 'Farm'
                WHEN property_type = 'waterfront' THEN 'Single Family Residence'
                WHEN property_type = 'timber' THEN 'Farm'
                WHEN property_type = 'development' THEN 'Unimproved Land'
                WHEN property_type = 'investment' THEN 'Mixed Use'
                ELSE property_type
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("
            UPDATE properties 
            SET property_type = CASE 
                WHEN property_type = 'Single Family Residence' THEN 'residential'
                WHEN property_type = 'Farm' THEN 'farms'
                WHEN property_type = 'Unimproved Land' THEN 'hunting'
                WHEN property_type = 'Mixed Use' THEN 'commercial'
                WHEN property_type = 'Agriculture' THEN 'farms'
                ELSE 'residential'
            END
        ");

        Schema::table('properties', function (Blueprint $table) {
            $table->enum('property_type', ['hunting', 'farms', 'ranches', 'residential', 'commercial', 'waterfront', 'timber', 'development', 'investment'])->change();
        });
    }
};