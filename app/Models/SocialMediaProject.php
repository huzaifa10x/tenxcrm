<?php

// In app/Models/SocialMediaProject.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialMediaProject extends Model
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
        'channels',
        'dark_posts_per_month',
        'static_posts_count',
        'video_reels_count',
        'video_shoot_per_month',
        'contract_copy',
        'payment_method',
        'handover_notes'
    ];
}
