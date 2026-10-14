<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'date', 'punch_in', 'punch_out', 'total_hours', 'location', 'status', 'auto_closed', 'edited_by', 'edited_at'];

    protected $casts = [
        'auto_closed' => 'boolean',
        'edited_at' => 'datetime',
    ];

    public const STATUSES = ['present', 'late', 'half_day', 'early_out', 'absent', 'leave', 'holiday'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notes()
    {
        return $this->hasMany(AttendanceNote::class);
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'edited_by');
    }

    /** The date as Y-m-d regardless of how the driver returns it. */
    public function dateString(): string
    {
        return substr((string) $this->date, 0, 10);
    }
}
