<?php

namespace App\Models\Crm;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    protected $table = 'pipedrive_notes';

    protected $fillable = ['content', 'deal_id', 'person_id', 'organization_id', 'user_id', 'pinned'];

    protected $casts = ['pinned' => 'boolean'];

    public function deal(): BelongsTo { return $this->belongsTo(Deal::class, 'deal_id'); }
    public function person(): BelongsTo { return $this->belongsTo(Person::class, 'person_id'); }
    public function organization(): BelongsTo { return $this->belongsTo(Organization::class, 'organization_id'); }
    public function user(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); }
}
