<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company_name',
        'website',
        'lead_date',
        'comments',
        'lead_status_id',
        'converted_project_id'
    ];

    protected $casts = [
        'lead_date' => 'date',
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class, 'lead_service');
    }

    public function status()
    {
        return $this->belongsTo(LeadStatus::class, 'lead_status_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'converted_project_id');
    }
}
