<?php

namespace App\Mail;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Support\MailBrand;
use App\Support\ReminderSettings;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Retainer renewal reminder to the account manager — stage 60 (plan) or 30 (action needed).
 */
class RenewalReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public const CURRENCY = 'AED';

    // protected: public properties are auto-injected into the view and override `with`
    public function __construct(protected Project $project, protected int $stage, protected User $accountManager)
    {
    }

    /**
     * Current contract terms: the current renewal if there is one, else the project itself.
     * Returns value, term, mrr and the renewal (contract end) date.
     */
    public static function contract(Project $project): array
    {
        $contract = $project->renewals()->where('is_current', true)->latest()->first() ?? $project;
        $term = (int) ($contract->contract_term ?? 0);
        $value = (float) ($contract->contract_value ?? 0);
        $start = $contract->contract_start_date ? Carbon::parse($contract->contract_start_date) : null;

        return [
            'value'        => $value,
            'term'         => $term,
            'mrr'          => $term > 0 ? $value / $term : $value,
            'renewal_date' => ($start && $term > 0) ? $start->copy()->addMonths($term)->startOfDay() : null,
        ];
    }

    public function envelope(): Envelope
    {
        $name = $this->project->title;

        return new Envelope(
            subject: $this->stage <= 30
                ? "[Action needed] Renewal in 30 days: {$name}"
                : "Renewal in 60 days: {$name} — time to plan",
        );
    }

    public function content(): Content
    {
        $project = $this->project->loadMissing(['companies']);
        $contract = self::contract($project);
        $renewalDate = $contract['renewal_date'];
        $clientSince = $project->contract_start_date ? Carbon::parse($project->contract_start_date) : null;

        return new Content(
            view: 'emails.projects.renewal-reminder',
            with: [
                'b'                  => MailBrand::get(),
                'stage'              => $this->stage,
                'account_manager'    => $this->accountManager->name,
                'account_name'       => $project->title,
                'client_name'        => optional($project->companies)->name,
                'account_id'         => 'PRJ-' . str_pad((string) $project->id, 5, '0', STR_PAD_LEFT),
                'plan_name'          => trim(($project->project_type ?? '') . ($project->category ? ' · ' . $project->category : '')) ?: 'N/A',
                'account_mrr'        => self::CURRENCY . ' ' . number_format($contract['mrr'], 2),
                'contract_value'     => self::CURRENCY . ' ' . number_format($contract['value'], 2) . ($contract['term'] > 0 ? " / {$contract['term']} months" : ''),
                'is_high_value'      => $contract['mrr'] >= (float) ReminderSettings::get('high_value_mrr'),
                'start_date'         => $clientSince ? $clientSince->format('d M Y') : 'N/A',
                'renewal_date'       => $renewalDate ? $renewalDate->format('d M Y') : 'N/A',
                'days_until_renewal' => $renewalDate ? (int) now()->startOfDay()->diffInDays($renewalDate, false) : null,
                'renewal_status'     => self::renewalStatus($project, $renewalDate),
                'project_status'     => $project->status ?? 'N/A',
                'account_link'       => route('projects.show', $project->id),
                'dashboardUrl'       => route('projects.index'),
            ],
        );
    }

    /**
     * There is no renewal-status field, so this reads the "Renewal Reminder" task that
     * tasks:contract-renewal creates a month before the contract ends.
     */
    public static function renewalStatus(Project $project, ?Carbon $renewalDate): string
    {
        $task = Task::where('project_id', $project->id)
            ->where('title', 'Renewal Reminder')
            ->when($renewalDate, fn ($q) => $q->where('created_at', '>=', $renewalDate->copy()->subDays(45)))
            ->latest()
            ->first();

        if (!$task) {
            return 'Not started';
        }

        return match ($task->status) {
            'Completed'   => 'Renewal task completed — confirm the new agreement is recorded',
            'In Progress' => 'In progress',
            'Review'      => 'In review',
            'Blocked'     => 'Blocked',
            default       => 'Renewal task open, not started',
        };
    }
}
