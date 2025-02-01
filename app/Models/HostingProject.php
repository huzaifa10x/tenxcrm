<?php

// In app/Models/HostingProject.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostingProject extends Model
{
    protected $fillable = [
        'category',
        'name',
        'industry_id',
        'customer_id',
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
    protected $casts = [
        'contract_copy' => 'array',
    ];

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

    public function salesManager()
    {
        return $this->belongsTo(User::class, 'sales_manager_id');
    }

    public function accountManager()
    {
        return $this->belongsTo(User::class, 'account_manager_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
