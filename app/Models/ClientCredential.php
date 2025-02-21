<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientCredential extends Model
{
    use HasFactory;

    use HasFactory;
    protected $fillable = ['project_id', 'url', 'username', 'password'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
