<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        if(!auth('admin')->check()){
            return redirect()->route('admin.login');
        }else{
            $rolesArray = is_array($roles) ? $roles : explode('&', $roles);
            if (!auth('admin')->user()->hasAnyRole($rolesArray)) {
            return redirect()->route('admin.dashboard');
        }
        }

        return $next($request);
    }
}
