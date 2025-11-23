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
        Schema::create('website_project_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_project_id')->constrained('website_projects')->onDelete('cascade');
            $table->enum('type', ['am_validation', 'seo_tech', 'ads_am', 'lead_dev_qa']);

            // AM Validation Checklist
            $table->boolean('forms_working')->default(false);
            $table->boolean('content_validated')->default(false);
            $table->boolean('responsiveness_checked')->default(false);

            // SEO Tech Checklist
            $table->boolean('urls_checked')->default(false);
            $table->boolean('meta_setup_checked')->default(false);
            $table->boolean('tracking_setup')->default(false);
            $table->boolean('indexing_checked')->default(false);
            $table->boolean('page_speed_checked')->default(false);

            // Ads AM Checklist
            $table->boolean('conversions_setup')->default(false);
            $table->boolean('tracking_validated')->default(false);
            $table->boolean('forms_tracking')->default(false);
            $table->boolean('pixel_ga4_setup')->default(false);

            // Lead Dev QA Checklist
            $table->boolean('code_quality_reviewed')->default(false);
            $table->boolean('security_checked')->default(false);
            $table->boolean('performance_optimized')->default(false);
            $table->boolean('cross_browser_tested')->default(false);

            $table->text('notes')->nullable();
            $table->foreignId('checked_by')->nullable()->constrained('users');
            $table->datetime('checked_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_project_checklists');
    }
};
