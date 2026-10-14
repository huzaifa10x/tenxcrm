<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_reminder_logs', function (Blueprint $table) {
            $table->id();
            $table->string('kind', 40);                    // due_48h, due_24h, overdue, overdue_escalated, renewal_60, renewal_30, digest
            $table->string('subject_type', 40);            // task, project, user
            $table->unsignedBigInteger('subject_id');
            $table->string('reference', 64);               // due date / renewal date / digest date — a changed date re-arms the reminder
            $table->unsignedBigInteger('recipient_id')->nullable();
            $table->timestamp('sent_at')->useCurrent();

            $table->unique(['kind', 'subject_type', 'subject_id', 'reference'], 'email_reminder_logs_unique');
            $table->index(['subject_type', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_reminder_logs');
    }
};
