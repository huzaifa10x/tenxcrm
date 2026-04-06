<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Schema::dropIfExists('lead_service');
        Schema::dropIfExists('lead_sources');
        Schema::dropIfExists('lead_comments');
        Schema::dropIfExists('lead_statuses');
        Schema::dropIfExists('leads');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('label')->nullable(); // Cold, Warm, Hot
            $table->string('source_origin')->nullable();
            $table->string('owner')->nullable();
            $table->timestamp('next_activity_at')->nullable();
            $table->timestamp('lead_created_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
