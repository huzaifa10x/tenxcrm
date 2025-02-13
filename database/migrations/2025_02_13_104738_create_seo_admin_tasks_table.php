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
        Schema::create('seo_admin_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->enum('sub_type', ['ADHOC']);
            $table->timestamps();
        });

        Schema::create('admin_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->enum('sub_type', ['ADHOC']);
            $table->timestamps();
        });

        Schema::create('sales_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->enum('sub_type', ['ADHOC']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('seo_admin_tasks');
        Schema::dropIfExists('admin_tasks');
        Schema::dropIfExists('sales_tasks');
    }
};
