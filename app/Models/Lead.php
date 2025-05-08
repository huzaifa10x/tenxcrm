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
        'converted_project_id',
        'created_by',
        'source_id',
        'priority',
        'location',
        'industry_id',
        'is_qualified',
    ];

    protected $casts = [
        'lead_date' => 'date',
        'is_qualified' => 'boolean',
    ];

    // 🔁 Relationships

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

    public function source()
    {
        return $this->belongsTo(LeadSource::class, 'source_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class, 'industry_id');
    }
}
