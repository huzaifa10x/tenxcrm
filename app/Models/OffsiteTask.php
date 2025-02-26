<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OffsiteTask extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'sub_type'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
    public function startingKwUrls()
    {
        return $this->hasMany(ProjectKeyword::class, 'task_id');
    }
}
