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
        Schema::create('am_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->enum('sub_type', ['CONTENT APPROVAL', 'REQUEST MEETING', 'PENDING PAYMENTS', 'ADHOC']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('am_tasks');
    }
};
