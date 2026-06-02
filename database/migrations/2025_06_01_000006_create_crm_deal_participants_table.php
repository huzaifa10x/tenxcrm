<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_deal_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deal_id')->constrained('crm_deals')->cascadeOnDelete();
            $table->foreignId('person_id')->constrained('crm_persons')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['deal_id', 'person_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_deal_participants');
    }
};
