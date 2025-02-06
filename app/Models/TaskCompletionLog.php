<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskCompletionLog extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'completed_at', 'status', 'comments'];

    // Relationship
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
