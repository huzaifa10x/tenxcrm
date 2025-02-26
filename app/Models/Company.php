<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'website', 'city', 'country', 'logo'];

    public function details()
    {
        return $this->hasMany(CompanyDetail::class, 'company_id');
    }
}
