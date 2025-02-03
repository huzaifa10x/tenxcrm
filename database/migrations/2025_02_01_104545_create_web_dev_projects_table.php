<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('web_dev_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->enum('payment_type', ['Partial', 'Full']);
            $table->float('remaining_payment');
            $table->enum('site_condition', ['New Project', 'Maintenance Project']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('web_dev_projects');
    }
};
