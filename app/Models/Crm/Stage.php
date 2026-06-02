<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stage extends Model
{
    protected $table = 'crm_stages';

    protected $fillable = [
        'pipeline_id', 'name', 'position', 'color', 'is_won', 'is_lost', 'rotting_days',
    ];

    protected $casts = [
        'is_won'  => 'boolean',
        'is_lost' => 'boolean',
    ];

    public function pipeline(): BelongsTo
    {
        return $this->belongsTo(Pipeline::class, 'pipeline_id');
    }

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class, 'stage_id');
    }

    /** Status implied by this stage when a deal lands on it. */
    public function impliedStatus(): string
    {
        if ($this->is_won) return 'won';
        if ($this->is_lost) return 'lost';
        return 'open';
    }
}
