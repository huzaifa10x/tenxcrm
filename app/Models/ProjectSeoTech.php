<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectSeoTech extends Model {
    protected $table = 'project_seo_tech';
    protected $fillable = ['project_id', 'user_id'];

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
