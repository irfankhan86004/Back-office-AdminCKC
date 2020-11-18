<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\App;

class SetAdminLocales
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
        App::setLocale(config('app.admin_locale'));
        Carbon::setLocale(config('app.admin_locale'));
        
        return $next($request);
    }
}
