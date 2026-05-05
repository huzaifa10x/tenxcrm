<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('xero_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('xero_invoice_id', 100)->unique()->index();
            $table->string('invoice_number', 50)->nullable()->index();
            $table->string('reference', 255)->nullable();
            $table->string('type', 20)->default('ACCREC');  // ACCREC = sales, ACCPAY = bills

            // Client FK
            $table->foreignId('xero_client_id')
                  ->constrained('xero_clients')
                  ->cascadeOnUpdate();
            $table->string('xero_contact_id', 100)->index(); // denormalized for fast lookup

            // Dates
            $table->date('invoice_date')->nullable()->index();
            $table->date('due_date')->nullable();
            $table->date('fully_paid_on_date')->nullable();

            // Financial
            $table->string('currency_code', 10)->default('AED');
            $table->decimal('currency_rate', 14, 8)->default(1);
            $table->decimal('sub_total', 14, 2)->default(0);
            $table->decimal('total_tax', 14, 2)->default(0);
            $table->decimal('total', 14, 2)->default(0)->index();
            $table->decimal('amount_due', 14, 2)->default(0);
            $table->decimal('amount_paid', 14, 2)->default(0);
            $table->decimal('amount_credited', 14, 2)->default(0);

            // Status
            $table->string('status', 30)->default('DRAFT')->index();
            // DRAFT, SUBMITTED, AUTHORISED, PAID, VOIDED, DELETED

            $table->string('line_amount_types', 30)->default('EXCLUSIVE');
            $table->boolean('sent_to_contact')->default(false);
            $table->boolean('is_discounted')->default(false);

            $table->timestamp('xero_updated_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            // Composite indexes for analytics
            $table->index(['invoice_date', 'status']);
            $table->index(['xero_client_id', 'invoice_date']);
            $table->index(['status', 'invoice_date', 'total']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('xero_invoices');
    }
};