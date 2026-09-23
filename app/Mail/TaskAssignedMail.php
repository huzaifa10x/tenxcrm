<?php

namespace App\Mail;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Support\MailBrand;
use Illuminate\Support\Str;

class TaskAssignedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    // protected: public properties are auto-injected into the view and override `with`
    public function __construct(protected Task $task, protected ?User $assignedBy = null)
    {
    }

    public function envelope(): Envelope
    {
        $task = $this->task->loadMissing('project');
        $priority = $task->priority ?: 'Normal';
        $project  = optional($task->project)->title ?? 'General';

        return new Envelope(
            subject: "[{$priority}] New task: {$task->title} — {$project} | 10X One",
        );
    }

    public function content(): Content
    {
        $task = $this->task->loadMissing(['project', 'assignedTo', 'createdBy'])
            ->loadCount(['attachments', 'collaborators']);

        $tz = 'Asia/Dubai';
        $dueDate  = $task->due_date ? Carbon::parse($task->due_date)->timezone($tz) : null;
        $daysLeft = $dueDate ? (int) now($tz)->startOfDay()->diffInDays($dueDate->copy()->startOfDay(), false) : null;

        return new Content(
            view: 'emails.tasks.assigned',
            with: [
                'b'                => MailBrand::get(),
                'task'             => $task,
                'taskCode'         => self::taskCode($task),
                'recipient'        => $task->assignedTo->name ?? 'there',
                'assignedByName'   => $this->assignedBy->name ?? 'System',
                'assignedByEmail'  => $this->assignedBy->email ?? null,
                'project'          => optional($task->project)->title ?? 'N/A',
                'priority'         => $task->priority ?: 'Normal',
                'priorityColor'    => self::priorityColor($task->priority),
                'type'             => $task->type ?: 'General',
                'status'           => $task->status ?: 'Todo',
                'createdAt'        => Carbon::parse($task->created_at ?? now())->timezone($tz)->format('d M Y, h:i A'),
                'startDate'        => $task->start_date ? Carbon::parse($task->start_date)->timezone($tz)->format('d M Y') : 'Not set',
                'dueDate'          => $dueDate ? $dueDate->format('d M Y') : 'Not set',
                'daysLeft'         => $daysLeft,
                'attachmentsCount' => $task->attachments_count ?? 0,
                'collaboratorsCount' => $task->collaborators_count ?? 0,
                'isRecurring'      => (bool) $task->repeat_task,
                'description'      => $task->description ? Str::limit(trim(strip_tags($task->description)), 500) : null,
                'url'              => url("tasks/{$task->id}"),
                'dashboardUrl'     => url('tasks'),
            ],
        );
    }

    public static function taskCode(Task $task): string
    {
        return 'TSK-' . str_pad((string) $task->id, 5, '0', STR_PAD_LEFT);
    }

    public static function priorityColor(?string $priority): string
    {
        return match ($priority) {
            'Urgent' => '#E5484D',
            'High'   => '#F76B15',
            'Medium' => '#F5A524',
            'Low'    => '#6CC04A',
            default  => '#6B7A72',
        };
    }
}