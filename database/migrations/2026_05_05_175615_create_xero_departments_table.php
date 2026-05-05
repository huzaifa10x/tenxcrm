<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('xero_departments', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('slug', 100)->unique();
            $table->string('color', 20)->default('#4c6ef5');
            $table->timestamps();
        });

        DB::table('xero_departments')->insert([
            ['name' => 'Marketing',     'slug' => 'marketing',     'color' => '#c2255c', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Engineering',   'slug' => 'engineering',   'color' => '#1971c2', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Design',        'slug' => 'design',        'color' => '#7048e8', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Operations',    'slug' => 'operations',    'color' => '#087f5b', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Uncategorized', 'slug' => 'uncategorized', 'color' => '#868e96', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('xero_departments');
    }
};