<?php

namespace App\Console\Commands;

use App\Mail\RenewalReminderMail;
use App\Models\EmailReminderLog;
use App\Models\Project;
use App\Models\User;
use App\Support\ReminderSettings;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Daily: renewal reminders to the account manager(s) 60 and 30 days before
 * the current contract ends. Each stage is sent once per renewal date.
 */
class SendRenewalReminders extends Command
{
    protected $signature = 'projects:send-renewal-reminders
                            {--project= : Only process this project ID}
                            {--stage= : Force a stage (60 or 30) — only with --to}
                            {--dry-run : List what would be sent without sending or logging}
                            {--to= : Send to this address instead (test mode, nothing is logged)}';

    protected $description = 'Send 60-day and 30-day renewal reminders to account managers';

    public function handle(): int
    {
        $windows = collect(ReminderSettings::get('renewal_windows'));
        $forced = $this->option('to') && $this->option('stage') ? (int) $this->option('stage') : null;
        $today = now()->startOfDay();
        $sent = 0;

        $projects = Project::whereNotIn('status', ReminderSettings::INACTIVE_PROJECT_STATUSES)
            ->when($this->option('project'), fn ($q, $id) => $q->whereKey($id))
            ->with('accountManagers')
            ->get();

        foreach ($projects as $project) {
            $renewalDate = RenewalReminderMail::contract($project)['renewal_date'];
            if (!$renewalDate) {
                continue;
            }

            $daysLeft = (int) $today->diffInDays($renewalDate, false);
            $stage = $forced ?? $windows->search(fn ($w) => $daysLeft >= (int) $w[0] && $daysLeft <= (int) $w[1]);

            if ($stage === false || $stage === null) {
                continue;
            }
            $stage = (int) $stage;

            $ams = $project->accountManagers->filter(fn (User $u) => $u->canReceiveEmail());
            if ($ams->isEmpty()) {
                $this->warn("  #{$project->id} {$project->title}: renewal in {$daysLeft} days but no account manager with an email — skipped.");
                continue;
            }

            if ($this->option('dry-run')) {
                if (EmailReminderLog::wasSent("renewal_{$stage}", 'project', $project->id, $renewalDate->toDateString())) {
                    continue;
                }
                $this->line(sprintf('  renewal_%-3d #%-5d %-40s %3d days → %s', $stage, $project->id, mb_strimwidth($project->title, 0, 40, '…'), $daysLeft, $ams->pluck('email')->implode(', ')));
                $sent++;
                continue;
            }

            if ($this->option('to')) {
                Mail::to($this->option('to'))->send(new RenewalReminderMail($project, $stage, $ams->first()));
                $this->line("  renewal_{$stage} #{$project->id} → {$this->option('to')} (test)");
                $sent++;
                continue;
            }

            if (!EmailReminderLog::claim("renewal_{$stage}", 'project', $project->id, $renewalDate->toDateString())) {
                continue;
            }

            try {
                // One personalised email per account manager ("Hi {name}").
                foreach ($ams as $am) {
                    Mail::to($am->email)->send(new RenewalReminderMail($project, $stage, $am));
                    $sent++;
                }
                $this->line("  renewal_{$stage} #{$project->id} {$project->title} → " . $ams->pluck('email')->implode(', '));
            } catch (\Throwable $e) {
                EmailReminderLog::release("renewal_{$stage}", 'project', $project->id, $renewalDate->toDateString());
                Log::error('Renewal reminder email failed', ['project' => $project->id, 'error' => $e->getMessage()]);
                $this->error("  renewal #{$project->id} failed: {$e->getMessage()}");
            }
        }

        $this->info(($this->option('dry-run') ? '[dry run] ' : '') . "{$sent} renewal email(s) " . ($this->option('dry-run') ? 'would be sent.' : 'sent.'));

        return self::SUCCESS;
    }
}
