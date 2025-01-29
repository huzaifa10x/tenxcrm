<?php

// In app/Models/WebDevelopmentProject.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebDevelopmentProject extends Model
{
    protected $fillable = [
        'category',
        'name',
        'industry',
        'status',
        'sales_manager_id',
        'account_manager_id',
        'contract_start_date',
        'contract_term',
        'contract_value',
        'free_month',
        'payment_type',
        'remaining_payment',
        'project_type',
        'contract_copy',
        'payment_method',
        'handover_notes'
    ];
}
