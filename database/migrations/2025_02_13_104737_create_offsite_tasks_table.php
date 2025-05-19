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
        Schema::create('offsite_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->enum('sub_type', ['MANUAL LINKS', 'IH LINKS', 'OH LINKS', 'PARTNER LINKS', 'ADHOC']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('offsite_tasks');
    }
};
