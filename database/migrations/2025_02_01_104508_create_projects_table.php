<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->longText('description');
            $table->enum('category', ['PLATINUM', 'GOLD', 'SILVER']);
            $table->foreignId('industry_id')->nullable()->constrained('industries')->onDelete('set null');
            $table->foreignId('company_id')->constrained('companies')->onDelete('1');
            $table->enum('status', ['Current', 'Paused', 'WFF', 'Expired', 'Debt Collection', 'Cancelled', 'Completed']);
            $table->date('contract_start_date');
            $table->integer('contract_term');
            $table->float('contract_value');
            $table->float('free_months');
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->onDelete('set null');
            $table->foreignId('created_by')->constrained('users')->onDelete('set null');
            $table->longText('handover_notes');
            $table->enum('project_type', ['SEO', 'Google Ads', 'Social Media', 'Web Development', 'Hosting', 'Other', 'Web Maintenance']);
            $table->timestamps();
        });

        Schema::create('project_sales_manager', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('project_account_manager', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('project_tech_manager', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('project_seo_tech', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('project_content_writer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('project_seo_admin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_collaborators');
        Schema::dropIfExists('task_collaborators');
        Schema::dropIfExists('project_keywords');
        Schema::dropIfExists('client_credentials');
        Schema::dropIfExists('task_histories');
        Schema::dropIfExists('task_comments');
        Schema::dropIfExists('repeating_tasks');
        Schema::dropIfExists('seo_admin_tasks');
        Schema::dropIfExists('task_histories');
        Schema::dropIfExists('task_comments');
        Schema::dropIfExists('seo_admin_tasks');
        Schema::dropIfExists('admin_tasks');
        Schema::dropIfExists('sales_tasks');
        Schema::dropIfExists('am_tasks');
        Schema::dropIfExists('offsite_tasks');
        Schema::dropIfExists('development_tasks');
        Schema::dropIfExists('design_tasks');
        Schema::dropIfExists('content_tasks');
        Schema::dropIfExists('onsite_tasks');
        Schema::dropIfExists('task_attachments');
        Schema::dropIfExists('task_completion_logs');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('project_sales_manager');
        Schema::dropIfExists('project_account_manager');
        Schema::dropIfExists('project_tech_manager');
        Schema::dropIfExists('project_sales_manager');
        Schema::dropIfExists('project_account_manager');
        Schema::dropIfExists('project_tech_manager');
        Schema::dropIfExists('project_seo_tech');
        Schema::dropIfExists('project_content_writer');
        Schema::dropIfExists('project_seo_admin');
        Schema::dropIfExists('project_seo_techs');
        Schema::dropIfExists('seo_projects');
        Schema::dropIfExists('social_media_projects');
        Schema::dropIfExists('web_dev_projects');
        Schema::dropIfExists('project_contract_files');
        Schema::dropIfExists('projects');
    }
};
