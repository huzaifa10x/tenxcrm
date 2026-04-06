<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pipedrive_id')->nullable()->unique();
            $table->string('title');
            $table->decimal('value', 15, 2)->nullable();
            $table->string('currency', 10)->nullable()->default('AED');
            $table->string('organization')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('owner')->nullable();
            $table->string('pipeline')->nullable();
            $table->string('stage')->nullable();
            $table->enum('status', ['Open', 'Won', 'Lost'])->default('Open');
            $table->string('label')->nullable();
            $table->decimal('probability', 5, 2)->nullable();
            $table->decimal('weighted_value', 15, 2)->nullable();
            $table->string('source_origin')->nullable();
            $table->string('source_channel')->nullable();
            $table->string('lost_reason')->nullable();
            $table->string('visible_to')->nullable();
            $table->integer('total_activities')->default(0);
            $table->integer('done_activities')->default(0);
            $table->integer('activities_to_do')->default(0);
            $table->string('product_name')->nullable();
            $table->decimal('product_amount', 15, 2)->nullable();
            $table->integer('product_quantity')->nullable();
            $table->string('archive_status')->nullable()->default('Not archived');
            $table->string('creator')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('expected_close_date')->nullable();
            $table->timestamp('next_activity_date')->nullable();
            $table->timestamp('last_activity_date')->nullable();
            $table->timestamp('last_stage_change')->nullable();
            $table->timestamp('won_time')->nullable();
            $table->timestamp('lost_time')->nullable();
            $table->timestamp('deal_closed_on')->nullable();
            $table->timestamp('deal_created_at')->nullable();
            $table->timestamp('deal_updated_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
