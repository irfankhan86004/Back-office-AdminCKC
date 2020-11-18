<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        if (! Auth::guard('admin')->user()->hasPermission($permission)) {
            session()->flash('notification', ['type' => 'error', 'text' => 'Vous n\'avez pas les droits pour accéder à cette section.']);
            return redirect()->route('admin_dashboard');
        }

        return $next($request);
    }
}
