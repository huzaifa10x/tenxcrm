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
        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'created_by')) {
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('leads', 'source_id')) {
                $table->foreignId('source_id')->nullable()->constrained('lead_sources')->nullOnDelete();
            }
            if (!Schema::hasColumn('leads', 'priority')) {
                $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            }
            if (!Schema::hasColumn('leads', 'location')) {
                $table->string('location')->nullable();
            }
            if (!Schema::hasColumn('leads', 'industry_id')) {
                $table->foreignId('industry_id')->nullable()->constrained('industries')->nullOnDelete();
            }
            if (!Schema::hasColumn('leads', 'is_qualified')) {
                $table->boolean('is_qualified')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
