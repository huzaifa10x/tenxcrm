<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    use HasFactory;

    protected $fillable = ['industry'];

    public function seoCampaignProjects()
    {
        return $this->hasMany(SeoCampaignProject::class);
    }
    public function googleAdsProject()
    {
        return $this->hasMany(GoogleAdsProject::class);
    }
    public function hostingProject()
    {
        return $this->hasMany(HostingProject::class);
    }
    public function socialMediaProject()
    {
        return $this->hasMany(SocialMediaProject::class);
    }
    public function webDevelopmentProject()
    {
        return $this->hasMany(WebDevelopmentProject::class);
    }
}
