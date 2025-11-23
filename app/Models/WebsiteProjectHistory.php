<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteProjectHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_project_id',
        'user_id',
        'action',
        'details',
    ];

    public function websiteProject()
    {
        return $this->belongsTo(WebsiteProject::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
