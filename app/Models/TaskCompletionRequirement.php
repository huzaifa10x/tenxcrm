<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskCompletionRequirement extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'requirement_type', 'value'];

    // Relationship
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
