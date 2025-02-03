<?php

// app/Models/Project.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category',
        'industry_id',
        'status',
        'sales_manager_id',
        'account_manager_id',
        'tech_manager_id',
        'contract_start_date',
        'contract_term',
        'contract_value',
        'free_months',
        'payment_method_id',
        'handover_notes',
        'project_type'
    ];

    public function seoProject()
    {
        return $this->hasOne(SeoProject::class);
    }

    public function socialMediaProject()
    {
        return $this->hasOne(SocialMediaProject::class);
    }

    public function webDevProject()
    {
        return $this->hasOne(WebDevProject::class);
    }

    public function contractFiles()
    {
        return $this->hasMany(ProjectContractFile::class);
    }
}
