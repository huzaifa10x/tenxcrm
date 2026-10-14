<?php

namespace App\Console\Commands;

use App\Mail\DailyTaskDigestMail;
use App\Models\EmailReminderLog;
use App\Models\User;
use App\Support\ReminderSettings;
use App\Support\TaskMailContext;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Daily "My Tasks" digest — one email per team member who has overdue,
 * due-today or upcoming open tasks.
 */
class SendDailyTaskDigest extends Command
{
    protected $signature = 'tasks:send-daily-digest
                            {--user= : Only send for this user ID}
                            {--dry-run : List what would be sent without sending or logging}
                            {--to= : Send to this address instead (test mode, nothing is logged)}';

    protected $description = 'Send the daily "My Tasks" digest to each team member';

    public function handle(): int
    {
        $today = now()->startOfDay();
        $todayStr = $today->toDateString();
        $upcomingUntil = $today->copy()->addDays((int) ReminderSettings::get('digest_upcoming_days'))->toDateString();
        $max = (int) ReminderSettings::get('digest_max_items');
        $digestDate = $today->format('l, d M Y');

        $tasksByUser = TaskMailContext::openTasksQuery()
            ->when($this->option('user'), fn ($q, $id) => $q->where('assigned_to', $id))
            ->whereDate('due_date', '<=', $upcomingUntil)
            ->with(['project.companies'])
            ->orderByRaw("CASE priority WHEN 'Urgent' THEN 1 WHEN 'High' THEN 2 WHEN 'Medium' THEN 3 ELSE 4 END")
            ->orderBy('due_date')
            ->get()
            ->groupBy('assigned_to');

        $users = User::whereIn('id', $tasksByUser->keys())->get();
        $sent = 0;

        foreach ($users as $user) {
            if (!$user->canReceiveEmail() || (!$this->option('to') && !$user->wantsEmail('daily_digest'))) {
                continue;
            }

            $tasks = $tasksByUser[$user->id];
            // due_date may come back as "Y-m-d" or "Y-m-d H:i:s" depending on the driver.
            $day = fn ($t) => substr((string) $t->due_date, 0, 10);
            $groups = [
                'overdue'   => $tasks->filter(fn ($t) => $day($t) < $todayStr)->sortBy($day),
                'due_today' => $tasks->filter(fn ($t) => $day($t) === $todayStr),
                'upcoming'  => $tasks->filter(fn ($t) => $day($t) > $todayStr)->sortBy($day),
            ];

            $sections = [];
            foreach ($groups as $key => $group) {
                $sections[$key] = $group->take($max)->map(fn ($t) => TaskMailContext::row($t))->values()->all();
                $sections[$key . '_count'] = $group->count();
            }

            if ($this->option('dry-run')) {
                $this->line(sprintf('  %-28s %-32s overdue %d · today %d · upcoming %d', $user->name, $user->email, $sections['overdue_count'], $sections['due_today_count'], $sections['upcoming_count']));
                $sent++;
                continue;
            }

            if ($this->option('to')) {
                Mail::to($this->option('to'))->send(new DailyTaskDigestMail($user, $sections, $digestDate));
                $this->line("  digest for {$user->name} → {$this->option('to')} (test)");
                $sent++;
                continue;
            }

            if (!EmailReminderLog::claim('digest', 'user', $user->id, $todayStr, $user->id)) {
                continue;
            }

            try {
                Mail::to($user->email)->send(new DailyTaskDigestMail($user, $sections, $digestDate));
                $sent++;
            } catch (\Throwable $e) {
                EmailReminderLog::release('digest', 'user', $user->id, $todayStr);
                Log::error('Daily digest email failed', ['user' => $user->id, 'error' => $e->getMessage()]);
                $this->error("  digest for {$user->email} failed: {$e->getMessage()}");
            }
        }

        $this->info(($this->option('dry-run') ? '[dry run] ' : '') . "{$sent} digest(s) " . ($this->option('dry-run') ? 'would be sent.' : 'sent.'));

        return self::SUCCESS;
    }
}
