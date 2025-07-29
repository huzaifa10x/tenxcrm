<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectRenewalUserAssignment extends Model
{
    protected $fillable = ['project_renewal_id', 'user_id', 'role_type'];

    public function renewal()
    {
        return $this->belongsTo(ProjectRenewal::class, 'project_renewal_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
