<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'department',
        'created_by',
        'type',
        'assigned_to',
        'department_id',
        'brief',
        'status',
        'due_date',
        'start_date',
        'completed_date',
        'estimated_time',
        'labels',
        'priority',
        'project_id',
        'repeating_task'
    ];

    // Relationships
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

    public function completionRequirements()
    {
        return $this->hasMany(TaskCompletionRequirement::class);
    }

    public function completionLogs()
    {
        return $this->hasMany(TaskCompletionLog::class);
    }

    // Scopes or Additional Functions
    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'In Progress');
    }
}
