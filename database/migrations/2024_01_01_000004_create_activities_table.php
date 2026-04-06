<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();

            // Core Pipedrive fields
            $table->enum('done', ['To do', 'Done'])->default('To do');
            $table->string('subject');
            $table->enum('type', ['Call', 'Meeting', 'Task', 'Deadline', 'Email', 'Lunch'])->default('Call');

            // Linked records (free-text from Pipedrive, FK optional)
            $table->unsignedBigInteger('deal_id')->nullable();
            $table->unsignedBigInteger('lead_id')->nullable();
            $table->string('deal_name')->nullable();       // parsed from Pipedrive "Activity - Deal" blob
            $table->string('contact_person')->nullable();

            // Contact details
            $table->string('email_work')->nullable();
            $table->string('email_home')->nullable();
            $table->string('email_other')->nullable();
            $table->string('phone_work')->nullable();
            $table->string('phone_home')->nullable();
            $table->string('phone_mobile')->nullable();
            $table->string('phone_other')->nullable();

            // Organisation
            $table->string('organization')->nullable();

            // Scheduling
            $table->date('due_date')->nullable();
            $table->time('due_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('duration')->nullable();      // e.g. "01:00"

            // Meta
            $table->string('assigned_to')->nullable();
            $table->enum('priority', ['Normal', 'High'])->default('Normal');
            $table->string('location')->nullable();
            $table->string('video_call_url')->nullable();
            $table->text('description')->nullable();
            $table->text('note')->nullable();
            $table->string('visible_to')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['due_date', 'done']);
            $table->index('assigned_to');
            $table->index('deal_id');
            $table->index('lead_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
