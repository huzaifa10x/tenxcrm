<?php

// In app/Models/HostingProject.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostingProject extends Model
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
        'contract_copy',
        'payment_method',
        'handover_notes'
    ];
}
