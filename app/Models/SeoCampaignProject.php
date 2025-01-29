<?php

// In app/Models/SeoCampaignProject.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoCampaignProject extends Model
{

    protected $fillable = [
        'category',
        'name',
        'industry_id',
        'status',
        'sales_manager_id',
        'account_manager_id',
        'contract_start_date',
        'contract_term',
        'contract_value',
        'customer_id',
        'free_month',
        'number_of_keywords',
        'guarantee_first_milestone',
        'guarantee_first_milestone_date',
        'guarantee_second_milestone',
        'guarantee_second_milestone_date',
        'guarantee_third_milestone',
        'guarantee_third_milestone_date',
        'contract_copy',
        'payment_method',
        'handover_notes'
    ];
    public function salesManager()
    {
        return $this->belongsTo(User::class, 'sales_manager_id');
    }
    public function industry()
    {
        return $this->belongsTo(Industry::class, 'industry_id');
    }

    public function accountManager()
    {
        return $this->belongsTo(User::class, 'account_manager_id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    protected $casts = [
        'contract_copy' => 'array',
    ];
}
