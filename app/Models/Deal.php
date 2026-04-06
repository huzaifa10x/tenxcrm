<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Deal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pipedrive_id', 'title', 'value', 'currency', 'organization',
        'contact_person', 'owner', 'pipeline', 'stage', 'status', 'label',
        'probability', 'weighted_value', 'source_origin', 'source_channel',
        'lost_reason', 'visible_to', 'total_activities', 'done_activities',
        'activities_to_do', 'product_name', 'product_amount', 'product_quantity',
        'archive_status', 'creator', 'notes',
        'expected_close_date', 'next_activity_date', 'last_activity_date',
        'last_stage_change', 'won_time', 'lost_time', 'deal_closed_on',
        'deal_created_at', 'deal_updated_at',
    ];

    protected $casts = [
        'expected_close_date'  => 'datetime',
        'next_activity_date'   => 'datetime',
        'last_activity_date'   => 'datetime',
        'last_stage_change'    => 'datetime',
        'won_time'             => 'datetime',
        'lost_time'            => 'datetime',
        'deal_closed_on'       => 'datetime',
        'deal_created_at'      => 'datetime',
        'deal_updated_at'      => 'datetime',
        'value'                => 'decimal:2',
        'weighted_value'       => 'decimal:2',
        'product_amount'       => 'decimal:2',
    ];

    public const STATUSES = ['Open', 'Won', 'Lost'];

    public const STAGES = [
        'New Leads', 'Engaged', 'Meeting Booked', 'Negotiation',
        'Closed WON', 'Lost', 'Reactivated', 'Not Answering', 'SPAM',
    ];

    // Polymorphic notes
    public function crmNotes()
    {
        return $this->morphMany(CrmNote::class, 'notable')->latest();
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, fn($q, $v) =>
            $q->where(function ($q) use ($v) {
                $q->where('title', 'like', "%{$v}%")
                  ->orWhere('organization', 'like', "%{$v}%")
                  ->orWhere('contact_person', 'like', "%{$v}%")
                  ->orWhere('owner', 'like', "%{$v}%");
            })
        );

        $query->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v));
        $query->when($filters['stage'] ?? null, fn($q, $v) => $q->where('stage', $v));
        $query->when($filters['owner'] ?? null, fn($q, $v) => $q->where('owner', $v));
        $query->when($filters['pipeline'] ?? null, fn($q, $v) => $q->where('pipeline', $v));
        $query->when($filters['source_origin'] ?? null, fn($q, $v) => $q->where('source_origin', $v));

        $query->when($filters['value_min'] ?? null, fn($q, $v) => $q->where('value', '>=', $v));
        $query->when($filters['value_max'] ?? null, fn($q, $v) => $q->where('value', '<=', $v));

        $query->when($filters['date_from'] ?? null, fn($q, $v) =>
            $q->whereDate('deal_created_at', '>=', $v)
        );
        $query->when($filters['date_to'] ?? null, fn($q, $v) =>
            $q->whereDate('deal_created_at', '<=', $v)
        );

        return $query;
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'Won'  => 'success',
            'Lost' => 'danger',
            'Open' => 'primary',
            default => 'secondary',
        };
    }

    public function getStageBadgeClassAttribute(): string
    {
        return match ($this->stage) {
            'Closed WON'     => 'success',
            'Lost', 'SPAM'   => 'danger',
            'Negotiation'    => 'warning',
            'Meeting Booked' => 'info',
            'Reactivated'    => 'primary',
            default          => 'secondary',
        };
    }

    public function getFormattedValueAttribute(): string
    {
        return number_format((float) $this->value, 2) . ' ' . ($this->currency ?? 'AED');
    }
}
