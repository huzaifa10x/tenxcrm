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
        Schema::create('seo_campaign_projects', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['PLATINUM', 'GOLD', 'SILVER']);
            $table->string('name');
            $table->foreignId('industry_id')->constrained('industries')->onDelete('cascade');
            $table->enum('status', ['Current', 'Paused', 'WFF', 'Expired', 'Debt Collection', 'Cancelled']);
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('sales_manager_id')->constrained('users');
            $table->foreignId('account_manager_id')->constrained('users');
            $table->date('contract_start_date');
            $table->integer('contract_term'); // In months
            $table->decimal('contract_value', 15, 2);
            $table->integer('free_month')->default(0);
            $table->integer('number_of_keywords');
            $table->decimal('guarantee_first_milestone', 5, 2);
            $table->date('guarantee_first_milestone_date');
            $table->decimal('guarantee_second_milestone', 5, 2);
            $table->date('guarantee_second_milestone_date');
            $table->decimal('guarantee_third_milestone', 5, 2);
            $table->date('guarantee_third_milestone_date');
            $table->json('contract_copy')->nullable();
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
        Schema::dropIfExists('seo_campaign_projects');
    }
};
