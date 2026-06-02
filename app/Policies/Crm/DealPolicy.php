<?php

namespace App\Policies\Crm;

use App\Models\Crm\Deal;
use App\Models\User;

class DealPolicy
{
    /** ADMIN bypasses every check. */
    public function before(User $user, string $ability): ?bool
    {
        return $user->hasRole('ADMIN') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasRole('SALES MANAGER');
    }

    public function view(User $user, Deal $deal): bool
    {
        return $deal->owner_id === $user->id || $deal->visible_to === 'everyone';
    }

    public function create(User $user): bool
    {
        return $user->hasRole('SALES MANAGER');
    }

    public function update(User $user, Deal $deal): bool
    {
        return $deal->owner_id === $user->id;
    }

    public function delete(User $user, Deal $deal): bool
    {
        return $deal->owner_id === $user->id;
    }
}
