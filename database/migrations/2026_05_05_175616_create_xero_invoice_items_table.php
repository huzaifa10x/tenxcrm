<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('xero_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')
                  ->constrained('xero_invoices')
                  ->cascadeOnDelete();
            $table->string('xero_item_id', 100)->nullable()->index();
            $table->string('item_code', 50)->nullable()->index();
            $table->string('description', 500)->nullable();
            $table->string('account_code', 50)->nullable();
            $table->decimal('quantity', 12, 4)->default(1);
            $table->decimal('unit_price', 14, 2)->default(0);
            $table->decimal('discount_rate', 6, 2)->default(0);
            $table->decimal('line_amount', 14, 2)->default(0)->index();
            $table->decimal('tax_amount', 14, 2)->default(0);
            $table->string('tax_type', 50)->nullable();
            $table->string('line_item_id', 100)->nullable();
            $table->foreignId('department_id')
                  ->nullable()
                  ->constrained('xero_departments')
                  ->nullOnDelete();
            $table->string('normalized_service', 100)->nullable()->index();
            $table->timestamps();

            $table->index(['department_id', 'invoice_id']);
            $table->index(['normalized_service', 'line_amount']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('xero_invoice_items');
    }
};