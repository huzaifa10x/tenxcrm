<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('seo_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->integer('number_of_keywords');
            $table->float('milestone_1_percentage');
            $table->date('milestone_1_date');
            $table->float('milestone_2_percentage');
            $table->date('milestone_2_date');
            $table->float('milestone_3_percentage');
            $table->date('milestone_3_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('seo_projects');
    }
};
