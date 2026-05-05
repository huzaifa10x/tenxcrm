<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('xero_service_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('xero_item_id', 100)->nullable()->index();
            $table->string('item_code', 50)->nullable()->index();
            $table->string('item_name', 255)->index();
            $table->string('normalized_name', 100)->index();
            $table->foreignId('department_id')
                  ->constrained('xero_departments')
                  ->cascadeOnUpdate();
            $table->timestamps();

            $table->unique(['item_code', 'item_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('xero_service_mappings');
    }
};