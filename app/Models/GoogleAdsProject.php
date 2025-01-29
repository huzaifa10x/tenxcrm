<?php

// In app/Models/GoogleAdsProject.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleAdsProject extends Model
{
    protected $fillable = [
        'contract_copy',
        'payment_method',
        'handover_notes',
        'category',
        'name',
        'industry',
        'status',
        'sales_manager_id',
        'account_manager_id',
        'contract_start_date',
        'contract_term',
        'contract_value',
        'free_month'
    ];
}
