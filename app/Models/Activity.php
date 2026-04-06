<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'done', 'subject', 'type',
        'deal_id', 'lead_id', 'deal_name', 'contact_person',
        'email_work', 'email_home', 'email_other',
        'phone_work', 'phone_home', 'phone_mobile', 'phone_other',
        'organization',
        'due_date', 'due_time', 'end_time', 'duration',
        'assigned_to', 'priority', 'location', 'video_call_url',
        'description', 'note', 'visible_to',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    /* ── Constants ── */

    public const TYPES = ['Call', 'Meeting', 'Task', 'Deadline', 'Email', 'Lunch'];

    public const TYPE_ICONS = [
        'Call'     => 'bi-telephone-fill',
        'Meeting'  => 'bi-people-fill',
        'Task'     => 'bi-check2-square',
        'Deadline' => 'bi-flag-fill',
        'Email'    => 'bi-envelope-fill',
        'Lunch'    => 'bi-cup-hot-fill',
    ];

    public const TYPE_COLORS = [
        'Call'     => 'primary',
        'Meeting'  => 'success',
        'Task'     => 'warning',
        'Deadline' => 'danger',
        'Email'    => 'info',
        'Lunch'    => 'secondary',
    ];

    public const DONE_OPTIONS = ['To do', 'Done'];

    /* ── Relationships ── */

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    /* ── Scopes ── */

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, fn($q, $v) =>
            $q->where(function ($q) use ($v) {
                $q->where('subject', 'like', "%{$v}%")
                  ->orWhere('contact_person', 'like', "%{$v}%")
                  ->orWhere('organization', 'like', "%{$v}%")
                  ->orWhere('deal_name', 'like', "%{$v}%");
            })
        );

        $query->when($filters['type'] ?? null, fn($q, $v) => $q->where('type', $v));
        $query->when($filters['done'] ?? null, fn($q, $v) => $q->where('done', $v));
        $query->when($filters['assigned_to'] ?? null, fn($q, $v) => $q->where('assigned_to', $v));
        $query->when($filters['organization'] ?? null, fn($q, $v) => $q->where('organization', $v));

        $query->when($filters['period'] ?? null, function ($q, $v) {
            $today = now()->toDateString();
            match ($v) {
                'overdue'   => $q->where('due_date', '<', $today)->where('done', 'To do'),
                'today'     => $q->whereDate('due_date', $today),
                'tomorrow'  => $q->whereDate('due_date', now()->addDay()->toDateString()),
                'this_week' => $q->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()]),
                'next_week' => $q->whereBetween('due_date', [now()->addWeek()->startOfWeek(), now()->addWeek()->endOfWeek()]),
                default     => null,
            };
        });

        $query->when($filters['date_from'] ?? null, fn($q, $v) => $q->whereDate('due_date', '>=', $v));
        $query->when($filters['date_to']   ?? null, fn($q, $v) => $q->whereDate('due_date', '<=', $v));

        return $query;
    }

    /* ── Accessors ── */

    public function getTypeIconAttribute(): string
    {
        return self::TYPE_ICONS[$this->type] ?? 'bi-activity';
    }

    public function getTypeColorAttribute(): string
    {
        return self::TYPE_COLORS[$this->type] ?? 'secondary';
    }

    public function getIsDoneAttribute(): bool
    {
        return $this->done === 'Done';
    }

    public function getPrimaryEmailAttribute(): ?string
    {
        return $this->email_work ?? $this->email_home ?? $this->email_other ?? null;
    }

    public function getPrimaryPhoneAttribute(): ?string
    {
        return $this->phone_work ?? $this->phone_mobile ?? $this->phone_home ?? $this->phone_other ?? null;
    }

    public function getFormattedDueDateAttribute(): string
    {
        if (!$this->due_date) return '—';
        $date = $this->due_date->format('M j, Y');
        return $this->due_time ? $date . ' ' . substr($this->due_time, 0, 5) : $date;
    }
}
