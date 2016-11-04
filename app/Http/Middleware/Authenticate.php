<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            }

            return redirect()->guest('users/login');
        }
        return $next($request);
        if(Auth::user()->store) {
            return $next($request);
        } else {
            flash('You are not scheduled to work today!  Please talk to a manager and have them create a shift for you', 'danger');
            return redirect('/warning');
        }


    }
}
