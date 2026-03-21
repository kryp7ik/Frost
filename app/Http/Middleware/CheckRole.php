<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * Replaces Zizaco\Entrust\Middleware\EntrustRole middleware.
 */
class CheckRole
{
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check() || !Auth::user()->hasRole($role)) {
            abort(403);
        }

        return $next($request);
    }
}
