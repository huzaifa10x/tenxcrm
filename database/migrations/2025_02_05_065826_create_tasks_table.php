<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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
            $table->enum('type', ['CONTENT', 'ONSITE', 'OFFSITE', 'DESIGN', 'DEVELOPMENT', 'ACCOUNT MANAGER', 'SEO ADMIN', 'ADMIN', 'SALES', 'ADHOC'])->default('ADHOC');
            $table->enum('status', ['Todo', 'In Progress', 'Review', 'Completed', 'Blocked'])->default('Todo');
            $table->date('due_date')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
