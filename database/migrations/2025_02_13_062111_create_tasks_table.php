<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('department_id')->nullable()->constrained('roles')->onDelete('set null');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('priority', ['Low', 'Medium', 'High', 'Urgent'])->default('Medium');
            $table->enum('type', ['CONTENT', 'ONSITE', 'TECH MANAGER', 'OFFSITE', 'DESIGN', 'DEVELOPMENT', 'AM', 'SEO ADMIN', 'ADMIN', 'SALES MANAGER', 'SALES', 'ADHOC', 'SOCIAL MEDIA MANAGER'])->default('ADHOC');
            $table->enum('status', ['Todo', 'In Progress', 'Review', 'Completed', 'Blocked'])->default('Todo');
            $table->date('due_date')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
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
    }
};
