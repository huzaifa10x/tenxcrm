<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory;

    protected $fillable = ['office_open_time', 'office_close_time', 'holidays'];

    protected $casts = [
        'holidays' => 'array',
    ];
}
