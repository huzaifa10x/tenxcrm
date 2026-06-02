<?php

namespace App\Models\Crm;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use SoftDeletes;

    protected $table = 'crm_organizations';

    protected $fillable = ['name', 'email', 'phone', 'website', 'address', 'owner_id'];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function persons(): HasMany
    {
        return $this->hasMany(Person::class, 'organization_id');
    }

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class, 'organization_id');
    }

    public function scopeVisibleTo($query, User $user)
    {
        if ($user->hasRole('ADMIN')) return $query;
        return $query->where('owner_id', $user->id);
    }
}
