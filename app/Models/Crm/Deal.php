<?php

namespace App\Models\Crm;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deal extends Model
{
    use SoftDeletes;

    protected $table = 'crm_deals';

    protected $fillable = [
        'title', 'value', 'currency', 'pipeline_id', 'stage_id', 'person_id',
        'organization_id', 'owner_id', 'created_by', 'status', 'label',
        'expected_close_date', 'won_date', 'lost_date', 'lost_reason',
        'source_origin', 'source_channel', 'source_channel_id', 'visible_to',
        'position', 'stage_changed_at', 'last_activity_at',
    ];

    protected $casts = [
        'value'               => 'decimal:2',
        'expected_close_date' => 'date',
        'won_date'            => 'datetime',
        'lost_date'           => 'datetime',
        'stage_changed_at'    => 'datetime',
        'last_activity_at'    => 'datetime',
    ];

    // ── Relationships ───────────────────────────────────────────
    public function pipeline(): BelongsTo { return $this->belongsTo(Pipeline::class, 'pipeline_id'); }
    public function stage(): BelongsTo { return $this->belongsTo(Stage::class, 'stage_id'); }
    public function person(): BelongsTo { return $this->belongsTo(Person::class, 'person_id'); }
    public function organization(): BelongsTo { return $this->belongsTo(Organization::class, 'organization_id'); }
    public function owner(): BelongsTo { return $this->belongsTo(User::class, 'owner_id'); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'crm_deal_participants', 'deal_id', 'person_id')->withTimestamps();
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'crm_deal_followers', 'deal_id', 'user_id')->withTimestamps();
    }

    public function activities(): HasMany { return $this->hasMany(Activity::class, 'deal_id')->latest(); }
    public function notes(): HasMany { return $this->hasMany(Note::class, 'deal_id')->orderByDesc('pinned')->latest(); }
    public function stageLogs(): HasMany { return $this->hasMany(DealStageLog::class, 'deal_id')->latest(); }

    // ── Scopes ──────────────────────────────────────────────────
    /** Role-based visibility: ADMIN sees all, others see only deals they own. */
    public function scopeVisibleTo($query, User $user)
    {
        if ($user->hasRole('ADMIN')) {
            return $query;
        }
        return $query->where(function ($q) use ($user) {
            $q->where('owner_id', $user->id)
              ->orWhere('visible_to', 'everyone');
        });
    }

    public function scopeOpen($query) { return $query->where('status', 'open'); }
    public function scopeWon($query) { return $query->where('status', 'won'); }
    public function scopeLost($query) { return $query->where('status', 'lost'); }

    // ── Helpers ─────────────────────────────────────────────────
    public function getDealAgeAttribute(): int
    {
        return (int) $this->created_at?->diffInDays(now());
    }

    public function getInactiveDaysAttribute(): int
    {
        $ref = $this->last_activity_at ?? $this->updated_at ?? $this->created_at;
        return (int) $ref?->diffInDays(now());
    }

    /** Is the deal idle past its stage's rotting threshold? */
    public function getIsRottingAttribute(): bool
    {
        $rot = $this->stage?->rotting_days;
        if (! $rot || $this->status !== 'open') return false;
        $ref = $this->last_activity_at ?? $this->stage_changed_at ?? $this->updated_at;
        return $ref ? $ref->diffInDays(now()) >= $rot : false;
    }

    public function daysInCurrentStage(): int
    {
        $ref = $this->stage_changed_at ?? $this->created_at;
        return (int) $ref?->diffInDays(now());
    }

    public function touchActivity(): void
    {
        $this->forceFill(['last_activity_at' => now()])->saveQuietly();
    }
}
