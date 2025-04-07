<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Automation extends Model
{
    protected $fillable = [
        'key',
        'last_run_date',
    ];

    protected $casts = [
        'last_run_date' => 'date',
    ];
}
