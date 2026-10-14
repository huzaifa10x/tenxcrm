<?php

namespace App\Console\Commands;

use App\Mail\TaskDueReminderMail;
use App\Mail\TaskOverdueMail;
use App\Models\EmailReminderLog;
use App\Models\Task;
use App\Models\User;
use App\Support\ReminderSettings;
use App\Support\TaskMailContext;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Hourly: 48h / 24h due-date reminders, overdue notices, and escalation to the lead.
 * Each email is sent once per task per due date (tracked in email_reminder_logs).
 */
class SendTaskDueReminders extends Command
{
    protected $signature = 'tasks:send-due-reminders
                            {--task= : Only process this task ID}
                            {--dry-run : List what would be sent without sending or logging}
                            {--to= : Send every email to this address instead (test mode, nothing is logged)}';

    protected $description = 'Send 48h/24h due-date reminders, overdue notices and escalations';

    private int $sent = 0;

    public function handle(): int
    {
        $now = now();
        $lookback = (int) ReminderSettings::get('overdue_lookback_days');
        $hours = collect(ReminderSettings::get('reminder_hours'))->map(fn ($h) => (int) $h)->sortDesc()->values();

        $tasks = TaskMailContext::openTasksQuery()
            ->when($this->option('task'), fn ($q, $id) => $q->whereKey($id))
            // Window: overdue within the lookback period, up to the furthest reminder ahead.
            ->whereDate('due_date', '>=', $now->copy()->subDays($lookback + 1)->toDateString())
            ->whereDate('due_date', '<=', $now->copy()->addHours($hours->first() + 24)->toDateString())
            ->with(['assignedTo.managers', 'project.accountManagers', 'project.companies'])
            ->orderBy('due_date')
            ->get();

        foreach ($tasks as $task) {
            $assignee = $task->assignedTo;
            $dueAt = TaskMailContext::dueAt($task);

            if (!$assignee || !$assignee->canReceiveEmail() || !$dueAt) {
                continue;
            }

            if ($now->lessThan($dueAt)) {
                $this->handleUpcoming($task, $assignee, $dueAt, $hours->all());
            } else {
                $this->handleOverdue($task, $assignee, $dueAt);
            }
        }

        $this->info(($this->option('dry-run') ? '[dry run] ' : '') . "{$this->sent} email(s) " . ($this->option('dry-run') ? 'would be sent' : 'sent') . " ({$tasks->count()} task(s) checked).");

        return self::SUCCESS;
    }

    private function handleUpcoming(Task $task, User $assignee, $dueAt, array $hours): void
    {
        if (!$assignee->wantsEmail('due_reminders')) {
            return;
        }

        // Pick the narrowest window we're in (e.g. 24h beats 48h); never send both.
        $hoursLeft = now()->diffInMinutes($dueAt) / 60;
        $window = collect($hours)->sort()->first(fn ($h) => $hoursLeft <= $h);

        if ($window === null) {
            return;
        }

        // The "task assigned" email already covered a task created inside this window.
        if ($task->created_at && $task->created_at->greaterThan($dueAt->copy()->subHours($window))) {
            return;
        }

        $this->deliver("due_{$window}h", $task, [$assignee->email], [], fn () => new TaskDueReminderMail($task, $window));
    }

    private function handleOverdue(Task $task, User $assignee, $dueAt): void
    {
        $daysOverdue = $dueAt->diffInHours(now()) / 24;
        $threshold = (int) ReminderSettings::get('escalation_threshold_days');

        $amEmails = TaskMailContext::accountManagers($task)
            ->pluck('email')
            ->reject(fn ($e) => strcasecmp($e, $assignee->email) === 0)
            ->unique()->values()->all();

        if ($daysOverdue < $threshold) {
            $this->deliver('overdue', $task, [$assignee->email], $amEmails,
                fn () => new TaskOverdueMail($task, false, null, !empty($amEmails)));

            return;
        }

        // Past the threshold and still open: escalate once. If the plain overdue notice was
        // never sent (e.g. first run after deploy), this escalated email replaces it.
        $lead = $this->leadFor($assignee);
        $cc = $amEmails;
        if ($lead && strcasecmp($lead->email, $assignee->email) !== 0) {
            $cc[] = $lead->email;
        }

        if ($this->deliver('overdue_escalated', $task, [$assignee->email], array_values(array_unique($cc)),
            fn () => new TaskOverdueMail($task, true, $lead, !empty($amEmails)))) {
            if (!$this->isTestRun() && !$this->option('dry-run')) {
                EmailReminderLog::claim('overdue', 'task', $task->id, TaskMailContext::dueReference($task), $assignee->id);
            }
        }
    }

    private function leadFor(User $assignee): ?User
    {
        $lead = $assignee->managers->first(fn (User $u) => $u->canReceiveEmail() && $u->id !== $assignee->id);

        if (!$lead && ($fallback = ReminderSettings::get('escalation_fallback_email'))) {
            $lead = User::where('email', $fallback)->first();
        }

        return $lead;
    }

    /**
     * Claim, then queue the email. Returns true if it was (or would be) sent.
     */
    private function deliver(string $kind, Task $task, array $to, array $cc, callable $makeMail): bool
    {
        $reference = TaskMailContext::dueReference($task);

        if ($this->option('dry-run')) {
            if (EmailReminderLog::wasSent($kind, 'task', $task->id, $reference)) {
                return false;
            }
            $this->line(sprintf('  %-18s #%-6d %-45s → %s%s', $kind, $task->id, mb_strimwidth($task->title, 0, 45, '…'), implode(', ', $to), $cc ? ' (cc ' . implode(', ', $cc) . ')' : ''));
            $this->sent++;

            return true;
        }

        if ($this->isTestRun()) {
            Mail::to($this->option('to'))->send($makeMail());
            $this->line("  {$kind} #{$task->id} → {$this->option('to')} (test)");
            $this->sent++;

            return true;
        }

        if (!EmailReminderLog::claim($kind, 'task', $task->id, $reference, $task->assigned_to)) {
            return false;
        }

        try {
            $mail = Mail::to($to);
            if ($cc) {
                $mail->cc($cc);
            }
            $mail->send($makeMail());
            $this->line("  {$kind} #{$task->id} → " . implode(', ', $to));
            $this->sent++;

            return true;
        } catch (\Throwable $e) {
            EmailReminderLog::release($kind, 'task', $task->id, $reference);
            Log::error('Task reminder email failed', ['kind' => $kind, 'task' => $task->id, 'error' => $e->getMessage()]);
            $this->error("  {$kind} #{$task->id} failed: {$e->getMessage()}");

            return false;
        }
    }

    private function isTestRun(): bool
    {
        return (bool) $this->option('to');
    }
}
