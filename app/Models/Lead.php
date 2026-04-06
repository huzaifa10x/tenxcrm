<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'label',
        'source_origin',
        'owner',
        'next_activity_at',
        'lead_created_at',
        'notes',
    ];

    protected $casts = [
        'next_activity_at' => 'datetime',
        'lead_created_at'  => 'datetime',
    ];

    public const LABELS = ['Cold', 'Warm', 'Hot'];

    // Polymorphic notes
    public function crmNotes()
    {
        return $this->morphMany(CrmNote::class, 'notable')->latest();
    }

    // Scopes
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, fn($q, $v) =>
            $q->where('title', 'like', "%{$v}%")
              ->orWhere('owner', 'like', "%{$v}%")
        );

        $query->when($filters['label'] ?? null, fn($q, $v) =>
            $q->where('label', $v)
        );

        $query->when($filters['owner'] ?? null, fn($q, $v) =>
            $q->where('owner', $v)
        );

        $query->when($filters['source_origin'] ?? null, fn($q, $v) =>
            $q->where('source_origin', $v)
        );

        $query->when($filters['date_from'] ?? null, fn($q, $v) =>
            $q->whereDate('lead_created_at', '>=', $v)
        );

        $query->when($filters['date_to'] ?? null, fn($q, $v) =>
            $q->whereDate('lead_created_at', '<=', $v)
        );

        return $query;
    }

    public function getLabelBadgeClassAttribute(): string
    {
        return match ($this->label) {
            'Hot'  => 'danger',
            'Warm' => 'warning',
            'Cold' => 'info',
            default => 'secondary',
        };
    }
}
