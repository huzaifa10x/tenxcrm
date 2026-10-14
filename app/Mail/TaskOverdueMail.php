<?php

namespace App\Mail;

use App\Models\Task;
use App\Models\User;
use App\Support\ReminderSettings;
use App\Support\TaskMailContext;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Sent to the assignee when the due date passes (account manager copied).
 * Re-sent once with $escalated = true, adding the assignee's lead, when the task
 * is still open after the escalation threshold.
 */
class TaskOverdueMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    // protected: public properties are auto-injected into the view and override `with`
    public function __construct(
        protected Task $task,
        protected bool $escalated = false,
        protected ?User $escalatedTo = null,
        protected bool $accountManagerCopied = true,
    ) {
    }

    public function envelope(): Envelope
    {
        $dueAt = TaskMailContext::dueAt($this->task);

        return new Envelope(
            subject: ($this->escalated ? '[Overdue · Escalated] ' : '[Overdue] ')
                . $this->task->title . ' — was due ' . ($dueAt ? $dueAt->format('d M Y') : 'earlier'),
        );
    }

    public function content(): Content
    {
        $data = TaskMailContext::base($this->task);
        $dueAt = TaskMailContext::dueAt($this->task);

        $data['days_overdue'] = $dueAt ? TaskMailContext::human($dueAt, now(), 1) : 'some time';
        $data['escalated'] = $this->escalated;
        $data['escalation_threshold'] = (int) ReminderSettings::get('escalation_threshold_days');
        $data['escalated_to_name'] = $this->escalatedTo?->name;
        $data['escalated_to_email'] = $this->escalatedTo?->email;
        $data['account_manager_copied'] = $this->accountManagerCopied;

        return new Content(view: 'emails.tasks.overdue', with: $data);
    }
}
