<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $fillable = ['name', 'code', 'color', 'allowance_source', 'days_per_year', 'carry_forward_max', 'is_paid', 'allow_negative', 'requires_reason', 'is_active', 'sort'];

    protected $casts = [
        'days_per_year' => 'float',
        'carry_forward_max' => 'float',
        'is_paid' => 'boolean',
        'allow_negative' => 'boolean',
        'requires_reason' => 'boolean',
        'is_active' => 'boolean',
    ];

    public const SOURCES = [
        'annual' => 'Company annual allowance',
        'fixed' => 'Own allowance',
        'none' => 'No limit (tracked only)',
    ];

    public function scopeActive($q)
    {
        return $q->where('is_active', true)->orderBy('sort')->orderBy('name');
    }

    public function isLimited(): bool
    {
        return $this->allowance_source !== 'none';
    }

    /** Current allowance per year for this type (null = unlimited). */
    public function currentAllowance(): ?float
    {
        return match ($this->allowance_source) {
            'annual' => (float) (GeneralSetting::first()->annual_leave_allowance ?? 12),
            'fixed' => (float) ($this->days_per_year ?? 0),
            default => null,
        };
    }
}
