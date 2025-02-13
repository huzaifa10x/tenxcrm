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
        Schema::create('development_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->enum('sub_type', ['WEB DESIGN IMPLEMENTATION', 'SITE SPEED AND RENDERING', 'SEO IMPLEMENTATION', 'WEBSITE MAINTENANCE', 'DEPLOYMENT & HOSTING', 'ADHOC']);
            $table->text('closing_input')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('development_tasks');
    }
};
