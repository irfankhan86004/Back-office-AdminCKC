<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotAdmin
{
    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @param  string|null  $guard
    * @return mixed
    */
    public function handle($request, Closure $next, $guard = 'admin')
    {
        if (!Auth::guard($guard)->check()) {
            return redirect('/'.config('app.admin_url'))->withNotification(['type' => 'warning', 'text' => 'Vous n\'êtes pas connecté']);
        }

        return $next($request);
    }
}
