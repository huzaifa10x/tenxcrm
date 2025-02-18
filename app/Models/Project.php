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
        'created_by',
        'company_id',
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
    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function companies()
    {
        return $this->belongsTo(Company::class, 'company_id');
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

    public function salesManagers()
    {
        return $this->belongsToMany(User::class, 'project_sales_manager');
    }

    public function accountManagers()
    {
        return $this->belongsToMany(User::class, 'project_account_manager');
    }

    public function techManagers()
    {
        return $this->belongsToMany(User::class, 'project_tech_manager');
    }
}
