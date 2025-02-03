<?php

// app/Models/SeoProject.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoProject extends Model
{
    protected $fillable = [
        'project_id', // Foreign key to the projects table
        'number_of_keywords', 
        'milestone_1_percentage', 
        'milestone_1_date', 
        'milestone_2_percentage', 
        'milestone_2_date',
        'milestone_3_percentage', 
        'milestone_3_date'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
