<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectNote extends Model
{
    protected $fillable = ['project_id', 'user_id', 'note'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
