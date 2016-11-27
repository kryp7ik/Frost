<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Manager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Auth::check()) {
            return redirect('users/login');
        } else {
            $user = Auth::user();
            if($user->hasRole('manager')) {
                if($user->store) {
                    return $next($request);
                } else {
                    flash('You are not scheduled to work today!  Please talk to an administrator and have them create a shift for you', 'danger');
                    return redirect('/warning');
                }
            } else {
                return redirect('/')->with('warning', 'You do not have permission to visit that page!');
            }
        }
    }
}
