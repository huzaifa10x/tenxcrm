<?php

// app/Models/SocialMediaProject.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialMediaProject extends Model
{
    protected $fillable = [
        'project_id',
        'channels',
        'video_shoots_per_month',
        'static_posts',
        'reels',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
