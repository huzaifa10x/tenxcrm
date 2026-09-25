<?php

namespace App\Mail;

use App\Models\CancellationReason;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Support\MailBrand;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProjectCancellationRequestedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** Monthly value (AED) at or above which the account is flagged as high-value. */
    public const HIGH_VALUE_MRR = 5000;

    public const CURRENCY = 'AED';

    // protected: public properties are auto-injected into the view and override `with`
    public function __construct(
        protected Project $project,
        protected CancellationReason $cancellation,
        protected User $requester,
        protected User $admin,
        protected ?string $effectiveDate = null,
        protected ?string $additionalNotes = null,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Cancellation request: ' . $this->project->title . ' — needs review | 10X One',
        );
    }

    public function content(): Content
    {
        $tz = 'Asia/Dubai';
        $project = $this->project->loadMissing(['accountManagers', 'companies']);
        $requester = $this->requester->loadMissing('roles');

        // Current contract (latest renewal if any, else the project itself)
        $contract = $project->renewals()->where('is_current', true)->latest()->first() ?? $project;
        $term = (int) ($contract->contract_term ?? 0);
        $value = (float) ($contract->contract_value ?? 0);
        $mrr = $term > 0 ? $value / $term : $value;

        $contractStart = $contract->contract_start_date ? Carbon::parse($contract->contract_start_date) : null;
        $contractEnd = ($contractStart && $term > 0) ? $contractStart->copy()->addMonths($term) : null;

        $clientSince = $project->contract_start_date ? Carbon::parse($project->contract_start_date) : null;
        $submittedAt = Carbon::parse($this->cancellation->created_at ?? now())->timezone($tz);

        $openTasks = Task::where('project_id', $project->id)
            ->whereIn('status', ['Todo', 'In Progress', 'Review'])
            ->count();

        $accountManagers = $project->accountManagers->pluck('name')->filter()->implode(', ');

        return new Content(
            view: 'emails.projects.cancellation-requested',
            with: [
                'b'                 => MailBrand::get(),
                'adminName'         => $this->admin->name ?? 'Admin',
                'accountName'       => $project->title,
                'clientName'        => optional($project->companies)->name,
                'accountId'         => 'PRJ-' . str_pad((string) $project->id, 5, '0', STR_PAD_LEFT),
                'requesterName'     => $requester->name,
                'requesterEmail'    => $requester->email,
                'requesterRole'     => $requester->roles->pluck('name')->implode(', ') ?: 'N/A',
                'requestDate'       => $submittedAt->format('d M Y'),
                'requestTime'       => $submittedAt->format('h:i A'),
                'timezone'          => 'GST',
                'planName'          => trim(($project->project_type ?? '') . ($project->category ? ' · ' . $project->category : '')) ?: 'N/A',
                'projectStatus'     => $project->status ?? 'N/A',
                'accountMrr'        => self::CURRENCY . ' ' . number_format($mrr, 2),
                'contractValue'     => self::CURRENCY . ' ' . number_format($value, 2) . ($term > 0 ? " / {$term} months" : ''),
                'isHighValue'       => $mrr >= self::HIGH_VALUE_MRR,
                'startDate'         => $clientSince ? $clientSince->format('d M Y') : 'N/A',
                'tenure'            => $clientSince ? $clientSince->diffForHumans(now(), CarbonInterface::DIFF_ABSOLUTE, false, 2) : null,
                'contractEnd'       => $contractEnd ? $contractEnd->format('d M Y') : 'N/A',
                'accountManager'    => $accountManagers ?: 'Not assigned',
                'effectiveDate'     => $this->effectiveDate ? Carbon::parse($this->effectiveDate)->format('d M Y') : 'Not specified',
                'reason'            => $this->cancellation->reason,
                'additionalNotes'   => $this->additionalNotes ?: null,
                'openTasks'         => $openTasks,
                // 'url'               => route('projects.show', $project->id),
                'url'               => route('dashboard'),
                // 'dashboardUrl'      => route('projects.index'),
                'dashboardUrl'      => route('dashboard'),
            ],
        );
    }
}