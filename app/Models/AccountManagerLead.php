<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountManagerLead extends Model
{
    use HasFactory;

    protected $fillable = [
        'am_id',
        'manager_id',
    ];

    public function accountManager()
    {
        return $this->belongsTo(User::class, 'am_id');
    }

    
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
