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
        Schema::create('repeating_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->string('repeat_every'); // 'week' or 'month'
            $table->integer('count'); // Number of repeats
            $table->date('start_from'); // Date from when to start repeating
            $table->timestamp('end_date')->nullable(); // Optional end date for repeating task
            $table->integer('completed_repeats')->default(0); // Tracks how many times it has repeated
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repeating_tasks');
    }
};
