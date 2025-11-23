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
        Schema::create('website_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');

            // Mandatory CRM Fields
            $table->enum('platform', ['WordPress', 'Next.js'])->default('WordPress');
            $table->foreignId('developer_assigned')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('lead_developer')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('has_current_seo')->default(false);
            $table->boolean('has_current_google_ads')->default(false);
            $table->date('target_go_live_date')->nullable();

            // Design Phase
            $table->enum('design_status', ['Not Started', 'In Progress', 'Completed', 'Approved'])->default('Not Started');
            $table->text('figma_link')->nullable();
            $table->string('design_approval_screenshot')->nullable();
            $table->datetime('design_completed_at')->nullable();
            $table->datetime('design_approved_at')->nullable();

            // Development Phase
            $table->enum('development_status', [
                'Not Started',
                'In Progress',
                'Internal QA',
                'Client Review',
                'Completed',
                'Delivered'
            ])->default('Not Started');
            $table->text('staging_link')->nullable();
            $table->string('completion_agreement')->nullable();
            $table->datetime('development_completed_at')->nullable();
            $table->datetime('development_approved_at')->nullable();

            // Pre-Go-Live Checklist
            $table->boolean('lead_dev_qa_approved')->default(false);
            $table->datetime('lead_dev_qa_approved_at')->nullable();
            $table->foreignId('lead_dev_qa_approved_by')->nullable()->constrained('users');

            $table->boolean('am_validation_approved')->default(false);
            $table->datetime('am_validation_approved_at')->nullable();
            $table->foreignId('am_validation_approved_by')->nullable()->constrained('users');

            // SEO/Ads Approval
            $table->boolean('seo_tech_approved')->default(false);
            $table->datetime('seo_tech_approved_at')->nullable();
            $table->foreignId('seo_tech_approved_by')->nullable()->constrained('users');

            $table->boolean('ads_am_approved')->default(false);
            $table->datetime('ads_am_approved_at')->nullable();
            $table->foreignId('ads_am_approved_by')->nullable()->constrained('users');

            // Go-Live
            $table->boolean('approved_for_go_live')->default(false);
            $table->datetime('go_live_date')->nullable();

            // Notes
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_projects');
    }
};
