<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerLink extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'task_id',
        'keyword',
        'url',
        'backlink',
        'amount',
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
