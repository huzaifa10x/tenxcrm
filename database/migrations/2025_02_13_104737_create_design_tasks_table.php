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
        Schema::create('design_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->enum('sub_type', ['LP DESIGN', 'BANNERS', 'VIDEO', 'REVISIONS', 'ADHOC']);
            $table->integer('input_number')->nullable();
            $table->text('closing_input')->nullable(); // Attachment
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('design_tasks');
    }
};
