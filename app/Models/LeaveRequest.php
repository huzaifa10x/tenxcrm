<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $fillable = ['user_id', 'leave_type_id', 'start_date', 'end_date', 'days', 'year_days', 'reason', 'status', 'reviewed_by', 'reviewed_at', 'review_comment'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'days' => 'float',
        'year_days' => 'array',
        'reviewed_at' => 'datetime',
    ];

    public const STATUSES = ['pending', 'approved', 'rejected', 'cancelled'];

    /** Statuses that hold days (count against the balance and block overlaps). */
    public const ACTIVE = ['pending', 'approved'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function events()
    {
        return $this->hasMany(LeaveRequestEvent::class)->orderBy('created_at')->orderBy('id');
    }

    /** Days that fall in the given leave year. */
    public function daysInYear(int $year): float
    {
        return (float) ($this->year_days[(string) $year] ?? 0);
    }

    public function scopeActive($q)
    {
        return $q->whereIn('status', self::ACTIVE);
    }

    public function scopeOverlapping($q, string $from, string $to)
    {
        return $q->whereDate('start_date', '<=', $to)->whereDate('end_date', '>=', $from);
    }
}
