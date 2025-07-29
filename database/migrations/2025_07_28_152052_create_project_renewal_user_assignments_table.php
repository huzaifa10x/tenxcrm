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
        Schema::create('project_renewal_user_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_renewal_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role_type', ['SALES MANAGER', 'AM', 'TECH MANAGER', 'SEO ADMIN', 'CONTENT WRITE']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_renewal_user_assignments');
    }
};
