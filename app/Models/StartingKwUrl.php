<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StartingKwUrl extends Model
{
    use HasFactory;
    
    protected $table = 'starting_kw_url'; 
    protected $fillable = ['keyword', 'url', 'domain', 'task_id', 'project_id', 'offsite_task_id'];

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

    public function completionKwUrls()
    {
        return $this->hasMany(CompletionKwUrl::class, 'starting_keyword_id');
    }
}
