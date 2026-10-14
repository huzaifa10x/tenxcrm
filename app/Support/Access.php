<?php

namespace App\Support;

use App\Models\User;

/**
 * Who may do what in attendance and leave. Enforced in controllers (backend),
 * and also used by views to show or hide actions.
 */
class Access
{
    /** See everyone's attendance and correct records. */
    public static function manageAttendance(?User $user): bool
    {
        return $user && ($user->hasRole('ADMIN') || $user->hasRole('Accountant') || $user->hasPermission('can manage attendance'));
    }

    /** Approve, reject or revoke leave requests. */
    public static function approveLeaves(?User $user): bool
    {
        return $user && ($user->hasRole('ADMIN') || $user->hasPermission('can approve leaves'));
    }

    /** Change leave types, allowances and balances. */
    public static function manageLeavePolicies(?User $user): bool
    {
        return $user && ($user->hasRole('ADMIN') || $user->hasPermission('can manage leave policies'));
    }

    /** May $viewer see $subject's attendance? */
    public static function viewAttendanceOf(?User $viewer, int $subjectId): bool
    {
        return $viewer && ((int) $viewer->id === $subjectId || self::manageAttendance($viewer));
    }
}
