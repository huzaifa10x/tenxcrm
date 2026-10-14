<?php

namespace App\Support;

use App\Mail\TaskAssignedMail;
use App\Mail\TaskCompletedMail;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

/**
 * Shared data for scheduled task emails (reminder, overdue, digest).
 */
class TaskMailContext
{
    /** The moment a task is due: its due_date at the configured due time, in Dubai time. */
    public static function dueAt(Task $task): ?Carbon
    {
        if (!$task->due_date) {
            return null;
        }

        [$h, $m] = array_map('intval', explode(':', (string) ReminderSettings::get('due_time')) + [1 => 0]);

        return Carbon::parse($task->due_date, ReminderSettings::TIMEZONE)->setTime($h, $m);
    }

    /** Stable key for the reminder log: changing the due date re-arms every reminder. */
    public static function dueReference(Task $task): string
    {
        return Carbon::parse($task->due_date)->toDateString();
    }

    public static function human(CarbonInterface $from, CarbonInterface $to, int $parts = 2): string
    {
        return $from->diffForHumans($to, CarbonInterface::DIFF_ABSOLUTE, false, $parts);
    }

    /** Project account managers who can receive email. */
    public static function accountManagers(Task $task): Collection
    {
        return optional($task->project)->accountManagers?->filter(fn (User $u) => $u->canReceiveEmail())->values()
            ?? collect();
    }

    /** One-row representation used by the digest lists. */
    public static function row(Task $task): array
    {
        $dueAt = self::dueAt($task);

        return [
            'task_name'      => $task->title,
            'task_id'        => TaskAssignedMail::taskCode($task),
            'client'         => TaskCompletedMail::clientName($task),
            'due_date'       => $dueAt ? $dueAt->format('d M Y') : 'No due date',
            'priority'       => $task->priority ?: 'Medium',
            'priority_color' => TaskAssignedMail::priorityColor($task->priority),
            'status'         => $task->status ?: 'Todo',
            'task_link'      => url("tasks/{$task->id}"),
        ];
    }

    /** Fields shared by the reminder and overdue emails. */
    public static function base(Task $task): array
    {
        $task->loadMissing(['project.accountManagers', 'project.companies', 'assignedTo']);
        $dueAt = self::dueAt($task);
        $ams = self::accountManagers($task);

        return [
            'b'                => MailBrand::get(),
            'task'             => $task,
            'assignee_name'    => $task->assignedTo->name ?? 'there',
            'task_name'        => $task->title,
            'task_id'          => TaskAssignedMail::taskCode($task),
            'client'           => TaskCompletedMail::clientName($task),
            'project'          => optional($task->project)->title ?? 'General',
            'due_date'         => $dueAt ? $dueAt->format('d M Y') : 'Not set',
            'due_time'         => $dueAt ? $dueAt->format('h:i A') : '',
            'timezone'         => ReminderSettings::TIMEZONE_LABEL,
            'priority'         => $task->priority ?: 'Medium',
            'priority_color'   => TaskAssignedMail::priorityColor($task->priority),
            'status'           => $task->status ?: 'Todo',
            'type'             => $task->type ?: 'ADHOC',
            'account_manager'  => $ams->pluck('name')->implode(', ') ?: 'your account manager',
            'task_link'        => url("tasks/{$task->id}"),
            'prefs_link'       => route('profile.edit') . '#email-notifications',
            'dashboardUrl'     => url('tasks'),
        ];
    }

    /** Open, visible tasks on active projects, with a due date and an assignee. */
    public static function openTasksQuery()
    {
        return Task::query()
            ->visibleTasks()
            ->whereIn('status', ReminderSettings::OPEN_STATUSES)
            ->whereNotNull('due_date')
            ->whereNotNull('assigned_to')
            ->whereHas('project', fn ($q) => $q->whereNotIn('status', ReminderSettings::INACTIVE_PROJECT_STATUSES));
    }
}
