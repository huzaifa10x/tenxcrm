<?php

namespace App\Models\Crm;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DealStageLog extends Model
{
    protected $table = 'crm_deal_stage_logs';

    protected $fillable = ['deal_id', 'stage_id', 'user_id', 'entered_at', 'left_at'];

    protected $casts = [
        'entered_at' => 'datetime',
        'left_at'    => 'datetime',
    ];

    public function deal(): BelongsTo { return $this->belongsTo(Deal::class, 'deal_id'); }
    public function stage(): BelongsTo { return $this->belongsTo(Stage::class, 'stage_id'); }

    public function durationDays(): int
    {
        $end = $this->left_at ?? now();
        return (int) $this->entered_at->diffInDays($end);
    }
}
