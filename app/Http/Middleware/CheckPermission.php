<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = Auth::user();

        // Get all permissions for the user
        $permissions = $user->roles->flatMap->permissions->pluck('name')->unique();

        // Check if the user has the required permission
        if ($permissions->contains($permission)) {
            return $next($request);
        }

        // If not, redirect or abort
        return abort(403, 'Unauthorized action.');
    }
}
