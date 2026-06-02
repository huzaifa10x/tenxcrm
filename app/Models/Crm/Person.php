<?php

namespace App\Models\Crm;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use SoftDeletes;

    protected $table = 'crm_persons';

    protected $fillable = [
        'name', 'first_name', 'last_name', 'email', 'email_type',
        'phone', 'phone_type', 'label', 'organization_id', 'owner_id',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class, 'person_id');
    }

    public function scopeVisibleTo($query, User $user)
    {
        if ($user->hasRole('ADMIN')) return $query;
        return $query->where('owner_id', $user->id);
    }
}
