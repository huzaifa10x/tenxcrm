<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('xero_clients', function (Blueprint $table) {
            $table->id();
            $table->string('xero_contact_id', 100)->unique()->index();
            $table->string('name', 255)->index();
            $table->string('email', 255)->nullable()->index();
            $table->string('phone', 50)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('currency_code', 10)->default('AED');
            $table->boolean('is_customer')->default(true);
            $table->boolean('is_supplier')->default(false);
            $table->string('status', 20)->default('ACTIVE')->index();
            $table->timestamp('xero_updated_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('xero_clients');
    }
};