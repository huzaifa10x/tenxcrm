<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompletionKwUrl extends Model
{
    use HasFactory;

    protected $table = 'completion_kw_url'; 

    protected $fillable = ['starting_keyword_id', 'url', 'domain', 'task_id', 'project_id', 'offsite_task_id'];

    public function startingKwUrl()
    {
        return $this->belongsTo(StartingKwUrl::class, 'starting_keyword_id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function offsiteTask()
    {
        return $this->belongsTo(OffsiteTask::class);
    }
}
