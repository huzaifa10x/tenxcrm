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
        Schema::create('social_media_projects', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['PLATINUM', 'GOLD', 'SILVER']);
            $table->string('name');
            $table->foreignId('industry_id')->constrained('industries')->onDelete('cascade');
            $table->enum('status', ['Current', 'Paused', 'WFF', 'Expired', 'Debt Collection', 'Cancelled']);
            $table->foreignId('sales_manager_id')->constrained('users');
            $table->foreignId('account_manager_id')->constrained('users');
            $table->date('contract_start_date');
            $table->integer('contract_term'); // In months
            $table->decimal('contract_value', 15, 2);
            $table->integer('free_month')->default(0);
            $table->json('channels'); // JSON to store platforms like FB, Instagram, etc.
            $table->enum('dark_posts_per_month', ['Yes', 'No']);
            $table->integer('static_posts_count')->nullable(); // If 'Yes' for Dark Posts
            $table->integer('video_reels_count')->nullable(); // If 'Yes' for Dark Posts
            $table->integer('video_shoot_per_month')->nullable(); // Monthly 1, 2, 3...
            $table->json('contract_copy')->nullable();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->enum('payment_method', ['Cash', 'Credit Card', 'Bank Transfer', 'PayPal']);
            $table->text('handover_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_media_projects');
    }
};
