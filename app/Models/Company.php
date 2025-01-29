<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'website', 'city', 'country'];

    public function customers()
    {
        return $this->hasMany(User::class, 'company_id');
    }
    
}
