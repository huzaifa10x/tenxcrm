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
        if (!Schema::hasTable('companies')) {
            Schema::create('companies', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('website');
                $table->string('city');
                $table->string('logo')->nullable();
                $table->string('country');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('companies')) {
            Schema::dropIfExists('companies');
        }
    }
};
