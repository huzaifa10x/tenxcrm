<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectContractFile extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'file_path', 'file_name', 'file_type'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
