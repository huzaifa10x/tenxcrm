<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    protected $fillable = ['user_id', 'leave_type_id', 'year', 'allowance', 'carried_forward', 'adjustment'];

    protected $casts = [
        'allowance' => 'float',
        'carried_forward' => 'float',
        'adjustment' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

    public function adjustments()
    {
        return $this->hasMany(LeaveBalanceAdjustment::class)->latest('created_at');
    }

    /** Total days available this year (null = unlimited). */
    public function entitlement(): ?float
    {
        return $this->allowance === null ? null : $this->allowance + $this->carried_forward + $this->adjustment;
    }
}
