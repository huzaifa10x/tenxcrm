<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('content_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->enum('sub_type', ['SEO CONTENT', 'BLOG CONTENT', 'REVISION', 'ADHOC']);
            $table->integer('number_of_pages')->nullable();
            $table->integer('no_of_words')->nullable();
            $table->text('notes')->nullable();
            $table->text('closing_input')->nullable(); // Attachment or keyword list
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('content_tasks');
    }
};
