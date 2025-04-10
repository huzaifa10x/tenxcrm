<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepeatingTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'repeat_every', // 'week' or 'month'
        'count',
        'start_from',
        'end_date',
        'completed_repeats',
    ];

    /**
     * Relationship with the Task model.
     */
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    /**
     * Check if the task should repeat based on the current date.
     */
    public function shouldRepeat()
    {
        $now = now();
        if ($this->end_date && $now->greaterThanOrEqualTo($this->end_date)) {
            return false;
        }
        $nextRunAt = Carbon::parse($this->start_from);
        if ($this->repeat_every == 'week') {
            $nextRunAt->addWeeks($this->completed_repeats * $this->count);
        } elseif ($this->repeat_every == 'month') {
            $nextRunAt->addMonths($this->completed_repeats * $this->count);
        }
        return $now->greaterThanOrEqualTo($nextRunAt);
    }
}
