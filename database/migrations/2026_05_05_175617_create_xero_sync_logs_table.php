<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('xero_sync_logs', function (Blueprint $table) {
            $table->id();
            $table->string('entity', 50)->index();
            $table->string('sync_type', 20)->default('incremental');
            $table->string('status', 20)->default('running');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('last_modified_since')->nullable();
            $table->integer('records_fetched')->default(0);
            $table->integer('records_created')->default(0);
            $table->integer('records_updated')->default(0);
            $table->integer('records_failed')->default(0);
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['entity', 'status', 'completed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('xero_sync_logs');
    }
};