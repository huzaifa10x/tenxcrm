<?php

namespace App\Mail;

use App\Models\Task;
use App\Support\TaskMailContext;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Sent to the assignee 48h and 24h before a task's due date.
 */
class TaskDueReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    // protected: public properties are auto-injected into the view and override `with`
    public function __construct(protected Task $task, protected int $hoursBefore = 24)
    {
    }

    public function envelope(): Envelope
    {
        $task = $this->task->loadMissing('project');
        $dueAt = TaskMailContext::dueAt($task);
        $project = optional($task->project)->title ?? 'General';

        return new Envelope(
            subject: 'Due ' . ($dueAt ? $dueAt->format('d M Y') : 'soon') . ": {$task->title} — {$project}",
        );
    }

    public function content(): Content
    {
        $data = TaskMailContext::base($this->task);
        $dueAt = TaskMailContext::dueAt($this->task);
        $now = now();

        $data['time_remaining'] = $dueAt && $dueAt->greaterThan($now)
            ? TaskMailContext::human($now, $dueAt)
            : 'no time';
        $data['hours_before'] = $this->hoursBefore;
        $data['is_final'] = $this->hoursBefore <= 24;

        return new Content(view: 'emails.tasks.due-reminder', with: $data);
    }
}
