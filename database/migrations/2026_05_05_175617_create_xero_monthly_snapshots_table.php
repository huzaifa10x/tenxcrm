<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('xero_monthly_snapshots', function (Blueprint $table) {
            $table->id();
            $table->string('year_month', 7)->index();
            $table->foreignId('department_id')
                  ->nullable()
                  ->constrained('xero_departments')
                  ->nullOnDelete();
            $table->string('normalized_service', 100)->nullable();
            $table->foreignId('xero_client_id')
                  ->nullable()
                  ->constrained('xero_clients')
                  ->cascadeOnDelete();
            $table->string('snapshot_type', 30)->index();
            $table->decimal('revenue', 16, 2)->default(0);
            $table->integer('invoice_count')->default(0);
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();

            $table->unique(
                ['year_month', 'snapshot_type', 'department_id', 'normalized_service', 'xero_client_id'],
                'uniq_monthly_snapshot'
            );
            $table->index(['snapshot_type', 'year_month', 'revenue']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('xero_monthly_snapshots');
    }
};