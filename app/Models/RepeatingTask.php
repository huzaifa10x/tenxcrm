<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepeatingTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'repeat_every',
        'next_run_at',
        'repeat_count',
        'completed_repeats'
    ];

    /**
     * Relationship with the Task model.
     */
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
}
