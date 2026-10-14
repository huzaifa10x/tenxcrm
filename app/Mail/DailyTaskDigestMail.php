<?php

namespace App\Mail;

use App\Models\User;
use App\Support\MailBrand;
use App\Support\ReminderSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Daily "My Tasks" digest — one per team member.
 * $sections = ['due_today' => [...rows], 'overdue' => [...], 'upcoming' => [...]] plus *_count totals.
 */
class DailyTaskDigestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    // protected: public properties are auto-injected into the view and override `with`
    public function __construct(protected User $recipient, protected array $sections, protected string $digestDate)
    {
    }

    public function envelope(): Envelope
    {
        $s = $this->sections;
        $parts = array_filter([
            $s['overdue_count'] ? $s['overdue_count'] . ' overdue' : null,
            $s['due_today_count'] ? $s['due_today_count'] . ' due today' : null,
            $s['upcoming_count'] ? $s['upcoming_count'] . ' upcoming' : null,
        ]);

        return new Envelope(
            subject: 'My Tasks — ' . $this->digestDate . ': ' . implode(', ', $parts) . ' | 10X One',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.tasks.daily-digest',
            with: array_merge($this->sections, [
                'b'               => MailBrand::get(),
                'recipient_name'  => $this->recipient->name,
                'recipient_email' => $this->recipient->email,
                'digest_date'     => $this->digestDate,
                'timezone'        => ReminderSettings::TIMEZONE_LABEL,
                'board_link'      => url('tasks?type=assigned-to-me'),
                'prefs_link'      => route('profile.edit') . '#email-notifications',
                'dashboardUrl'    => url('tasks'),
            ]),
        );
    }
}
