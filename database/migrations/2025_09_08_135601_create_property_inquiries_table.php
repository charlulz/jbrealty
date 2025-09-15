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
        Schema::create('property_inquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->enum('inquiry_type', ['general', 'showing', 'offer', 'financing', 'information'])->default('general');
            $table->text('message');
            $table->boolean('is_qualified_buyer')->default(false);
            $table->decimal('max_budget', 15, 2)->nullable();
            $table->boolean('needs_financing')->nullable();
            $table->text('additional_info')->nullable();
            $table->enum('status', ['new', 'contacted', 'qualified', 'converted', 'closed'])->default('new');
            $table->timestamp('contacted_at')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users'); // Agent assigned to follow up
            $table->text('agent_notes')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('referral_source')->nullable();
            $table->timestamps();
            
            $table->index(['property_id', 'status']);
            $table->index(['assigned_to', 'status']);
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_inquiries');
    }
};
