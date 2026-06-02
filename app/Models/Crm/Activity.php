<?php

namespace App\Models\Crm;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    protected $table = 'crm_activities';

    protected $fillable = [
        'type', 'subject', 'note', 'deal_id', 'person_id', 'organization_id',
        'user_id', 'created_by', 'due_date', 'due_time', 'location', 'done', 'done_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'done'     => 'boolean',
        'done_at'  => 'datetime',
    ];

    public const TYPES = ['call', 'meeting', 'task', 'deadline', 'email', 'lunch'];

    public const TYPE_ICONS = [
        'call'     => 'bi-telephone',
        'meeting'  => 'bi-people',
        'task'     => 'bi-check2-square',
        'deadline' => 'bi-flag',
        'email'    => 'bi-envelope',
        'lunch'    => 'bi-cup-hot',
    ];

    public function deal(): BelongsTo { return $this->belongsTo(Deal::class, 'deal_id'); }
    public function person(): BelongsTo { return $this->belongsTo(Person::class, 'person_id'); }
    public function organization(): BelongsTo { return $this->belongsTo(Organization::class, 'organization_id'); }
    public function user(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); }

    public function getIconAttribute(): string
    {
        return self::TYPE_ICONS[$this->type] ?? 'bi-check2-square';
    }
}
