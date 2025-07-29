<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectRenewal extends Model
{
    protected $fillable = [
        'project_id',
        'contract_value',
        'contract_term',
        'free_months',
        'contract_start_date',
        'payment_method_id',
        'created_by',
        'handover_notes',
        'is_current',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function files()
    {
        return $this->hasMany(ProjectRenewalFile::class);
    }

    public function assignments()
    {
        return $this->hasMany(ProjectRenewalUserAssignment::class);
    }

    public function salesManagers()
    {
        return $this->assignments()->where('role_type', 'Sales');
    }

    public function accountManagers()
    {
        return $this->assignments()->where('role_type', 'Account');
    }

    public function techManagers()
    {
        return $this->assignments()->where('role_type', 'Tech');
    }
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
    public function seoAdmins()
    {
        return $this->assignments()->where('role_type', 'SEO');
    }

    public function writers()
    {
        return $this->assignments()->where('role_type', 'Writer');
    }
}
