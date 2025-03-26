<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'office_open_time',
        'office_close_time',
        'holidays',
        'late_threshold',
        'half_day_threshold',
        'break_end_time',
        'early_out_threshold',
        'late_deduction_threshold',
        'half_day_deduction_threshold',
    ];

    protected $casts = [
        'holidays' => 'array',
    ];
}
