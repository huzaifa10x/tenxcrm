<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskCompletion extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'field_name', 'field_value'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
