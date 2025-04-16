<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'project_id',
        'department_id',
        'created_by',
        'assigned_to',
        'priority',
        'type',
        'status',
        'completed_date',
        'due_date',
        'start_date',
        'parent_task_id',
        'repeat_task',
        'is_automated',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function assignedTo()
    {
        return $this->belongsTo(user::class, 'assigned_to');
    }
    public function department()
    {
        return $this->belongsTo(Role::class, 'department_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function attachments()
    {
        return $this->hasMany(TaskAttachment::class);
    }

    public function completionLogs()
    {
        return $this->hasMany(TaskCompletionLog::class);
    }
    public function repeatingTask()
    {
        return $this->hasOne(RepeatingTask::class, 'task_id');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'In Progress');
    }

    public function contentTask()
    {
        return $this->hasOne(ContentTask::class);
    }

    public function onsiteTask()
    {
        return $this->hasOne(OnsiteTask::class);
    }

    public function offsiteTask()
    {
        return $this->hasOne(OffsiteTask::class);
    }

    public function designTask()
    {
        return $this->hasOne(DesignTask::class);
    }

    public function developmentTask()
    {
        return $this->hasOne(DevelopmentTask::class);
    }

    public function amTask()
    {
        return $this->hasOne(AmTask::class);
    }

    public function seoAdminTask()
    {
        return $this->hasOne(SeoAdminTask::class);
    }

    public function adminTask()
    {
        return $this->hasOne(AdminTask::class);
    }
    public function collaborators()
    {
        return $this->hasMany(TaskCollaborator::class, 'task_id');
    }

    public function salesTask()
    {
        return $this->hasOne(SalesTask::class);
    }
    public function parentTask()
    {
        return $this->belongsTo(Task::class, 'parent_task_id');
    }

    public function childTasks()
    {
        return $this->hasMany(Task::class, 'parent_task_id');
    }

    public function subTasks()
    {
        return $this->hasMany(Task::class, 'parent_task_id');
    }
    public function completions()
    {
        return $this->hasMany(TaskCompletion::class, 'task_id');
    }
    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }
    public function projectKeywords()
    {
        return $this->hasMany(ProjectKeyword::class);
    }

    public function history()
    {
        return $this->hasMany(TaskHistory::class, 'task_id');
    }
    public function scopeVisibleTasks($query)
    {
        return $query->where(function ($q) {
            $q->whereNotNull('parent_task_id')
                ->orWhere(function ($q) {
                    $q->where('is_automated', 0);
                        // ->orWhere('start_date', '<=', Carbon::now());
                });
        });
    }

    public function getSubTypeAttribute()
    {
        if ($this->type === 'CONTENT') {
            return $this->contentTask ? $this->contentTask->sub_type : null;
        }

        // Define static mappings for other types
        $mapping = [
            'ONSITE' => ['RE-ONSITE', 'CHANGES IN CONTENT', 'CREATE NEW PAGE', 'REPLACE/UPLOAD CONTENT', 'ONSITE AUDIT', 'ADHOC'],
            'OFFSITE' => ['MANUAL LINKS', 'IH LINKS', 'OH LINKS', 'ADHOC'],
            'DESIGN' => ['LP DESIGN', 'BANNERS', 'VIDEO', 'REVISIONS', 'ADHOC'],
            'DEVELOPMENT' => ['WEB DESIGN IMPLEMENTATION', 'SITE SPEED AND RENDERING', 'SEO IMPLEMENTATION', 'WEBSITE MAINTENANCE', 'DEPLOYMENT & HOSTING', 'ADHOC'],
            'AM' => ['CONTENT APPROVAL', 'REQUEST MEETING', 'PENDING PAYMENTS', 'ADHOC'],
            'SEO ADMIN' => ['ADHOC'],
            'ADMIN' => ['ADHOC'],
            'SALES' => ['ADHOC'],
        ];

        return $mapping[$this->type][0] ?? null; // Return the first subtype if available
    }
}
