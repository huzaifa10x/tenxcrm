<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_deals', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->decimal('value', 15, 2)->default(0);
            $table->string('currency', 8)->default('AED');

            $table->foreignId('pipeline_id')->constrained('crm_pipelines')->cascadeOnDelete();
            $table->foreignId('stage_id')->constrained('crm_stages')->cascadeOnDelete();
            $table->foreignId('person_id')->nullable()->constrained('crm_persons')->nullOnDelete();
            $table->foreignId('organization_id')->nullable()->constrained('crm_organizations')->nullOnDelete();

            // owner = the sales rep who owns the deal (used for role scoping)
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->enum('status', ['open', 'won', 'lost'])->default('open');
            $table->string('label')->nullable();

            $table->date('expected_close_date')->nullable();
            $table->timestamp('won_date')->nullable();
            $table->timestamp('lost_date')->nullable();
            $table->string('lost_reason')->nullable();

            $table->string('source_origin')->nullable();
            $table->string('source_channel')->nullable();
            $table->string('source_channel_id')->nullable();

            $table->string('visible_to', 20)->default('owner'); // owner | everyone

            $table->unsignedInteger('position')->default(0);     // order within a stage column
            $table->timestamp('stage_changed_at')->nullable();
            $table->timestamp('last_activity_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['stage_id', 'status']);
            $table->index('owner_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_deals');
    }
};
