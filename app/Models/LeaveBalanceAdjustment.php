<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveBalanceAdjustment extends Model
{
    public $timestamps = false;

    protected $fillable = ['leave_balance_id', 'user_id', 'days', 'reason', 'created_at'];

    protected $casts = ['days' => 'float', 'created_at' => 'datetime'];

    public function balance()
    {
        return $this->belongsTo(LeaveBalance::class, 'leave_balance_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
