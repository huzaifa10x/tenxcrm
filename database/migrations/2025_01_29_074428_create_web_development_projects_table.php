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
        Schema::create('web_development_projects', function (Blueprint $table) {
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
            $table->enum('payment_type', ['Partial', 'Full']);
            $table->decimal('remaining_payment', 15, 2);
            $table->enum('project_type', ['New', 'Maintenance']);
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
        Schema::dropIfExists('web_development_projects');
    }
};
