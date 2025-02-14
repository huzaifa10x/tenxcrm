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
        Schema::create('completion_kw_url', function (Blueprint $table) {
            $table->id();
            $table->foreignId('starting_keyword_id')->constrained('starting_kw_url')->onDelete('cascade');
            $table->string('url');
            $table->string('domain');
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('offsite_task_id')->constrained('offsite_tasks')->onDelete('cascade');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('completion_kw_url');
    }
};
