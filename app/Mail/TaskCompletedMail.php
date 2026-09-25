<?php

namespace App\Mail;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Support\MailBrand;

class TaskCompletedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    // protected: public properties are auto-injected into the view and override `with`
    public function __construct(protected Task $task, protected ?User $completedBy = null)
    {
    }

    public function envelope(): Envelope
    {
        $task = $this->task->loadMissing('project');

        return new Envelope(
            subject: "Completed: {$task->title} — " . self::clientName($task) . ' | 10X One',
        );
    }

    /**
     * Client name: project's company (companies relation), else a client_name column, else project title.
     */
    public static function clientName(Task $task): string
    {
        $project = $task->project;

        if (!$project) {
            return 'General';
        }

        return optional($project->companies)->name
            ?? $project->client_name
            ?? $project->title
            ?? 'General';
    }

    public function content(): Content
    {
        $task = $this->task->loadMissing(['project', 'createdBy', 'assignedTo'])
            ->loadCount(['attachments', 'comments']);

        $tz = 'Asia/Dubai';
        $completedAt = Carbon::parse($task->completed_date ?? now())->timezone($tz);
        $startedAt   = Carbon::parse($task->start_date ?? $task->created_at ?? now())->timezone($tz);
        $dueDate     = $task->due_date ? Carbon::parse($task->due_date)->timezone($tz) : null;

        $onTime = null;
        $lateDays = 0;
        if ($dueDate) {
            $lateDays = (int) $dueDate->copy()->startOfDay()->diffInDays($completedAt->copy()->startOfDay(), false);
            $onTime = $lateDays <= 0;
        }

        return new Content(
            view: 'emails.tasks.completed',
            with: [
                'b'                => MailBrand::get(),
                'task'             => $task,
                'taskCode'         => TaskAssignedMail::taskCode($task),
                'recipient'        => $task->createdBy->name ?? 'there',
                'completedByName'  => $this->completedBy->name ?? 'System',
                'assignee'         => $task->assignedTo->name ?? 'N/A',
                'project'          => optional($task->project)->title ?? 'N/A',
                'priority'         => $task->priority ?: 'Normal',
                'priorityColor'    => TaskAssignedMail::priorityColor($task->priority),
                'type'             => $task->type ?: 'General',
                'startDate'        => $startedAt->format('d M Y'),
                'dueDate'          => $dueDate ? $dueDate->format('d M Y') : 'Not set',
                'completedDate'    => $completedAt->format('d M Y'),
                'completedTime'    => $completedAt->format('h:i A'),
                'timeTaken'        => $startedAt->diffForHumans($completedAt, CarbonInterface::DIFF_ABSOLUTE, false, 2),
                'onTime'           => $onTime,
                'lateDays'         => max($lateDays, 0),
                'attachmentsCount' => $task->attachments_count ?? 0,
                'commentsCount'    => $task->comments_count ?? 0,
                'url'              => url("tasks/{$task->id}"),
                'dashboardUrl'     => url('tasks'),
            ],
        );
    }
}