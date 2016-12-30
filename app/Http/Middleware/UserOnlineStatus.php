<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserOnlineStatus
{

    /**
     * Stores a simple Cache value when the user visits any page thus can be used to determine online users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        Cache::put('user-is-online-' . Auth::user()->id, true, Carbon::now()->addMinutes(10));
        return $next($request);
    }
}
