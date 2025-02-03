<?php

// app/Models/WebDevProject.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebDevProject extends Model
{
    protected $fillable = [
        'project_id',
        'payment_type',
        'remaining_payment',
        'site_condition'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
