<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectRenewalFile extends Model
{
    protected $fillable = ['project_renewal_id', 'file_path', 'file_name', 'file_type'];

    public function renewal()
    {
        return $this->belongsTo(ProjectRenewal::class, 'project_renewal_id');
    }
}
