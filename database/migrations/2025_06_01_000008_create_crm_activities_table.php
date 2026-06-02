<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_activities', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['call', 'meeting', 'task', 'deadline', 'email', 'lunch'])->default('task');
            $table->string('subject');
            $table->text('note')->nullable();

            $table->foreignId('deal_id')->nullable()->constrained('crm_deals')->cascadeOnDelete();
            $table->foreignId('person_id')->nullable()->constrained('crm_persons')->nullOnDelete();
            $table->foreignId('organization_id')->nullable()->constrained('crm_organizations')->nullOnDelete();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();   // assigned to
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->date('due_date')->nullable();
            $table->time('due_time')->nullable();
            $table->string('location')->nullable();

            $table->boolean('done')->default(false);
            $table->timestamp('done_at')->nullable();

            $table->timestamps();
            $table->index(['deal_id', 'done']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_activities');
    }
};
