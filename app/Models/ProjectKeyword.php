<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectKeyword extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'keyword',
        'url',
        'task_id',
        'type',
        'destination_url',
        'link_cost',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
}
