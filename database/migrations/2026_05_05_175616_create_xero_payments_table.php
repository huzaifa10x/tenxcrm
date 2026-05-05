<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('xero_payments', function (Blueprint $table) {
            $table->id();
            $table->string('xero_payment_id', 100)->unique()->index();
            $table->foreignId('invoice_id')
                  ->constrained('xero_invoices')
                  ->cascadeOnDelete();
            $table->string('xero_invoice_id', 100)->index();
            $table->date('payment_date')->nullable()->index();
            $table->decimal('amount', 14, 2)->default(0);
            $table->string('currency_code', 10)->default('AED');
            $table->decimal('currency_rate', 14, 8)->default(1);
            $table->string('reference', 255)->nullable();
            $table->string('payment_type', 50)->nullable();
            $table->string('status', 30)->default('AUTHORISED')->index();
            $table->timestamp('xero_updated_at')->nullable();
            $table->timestamps();

            $table->index(['payment_date', 'amount']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('xero_payments');
    }
};