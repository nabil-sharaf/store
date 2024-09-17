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
    public function handle(Request $request, Closure $next, $role)
    {
        if(!auth('admin')->check()){
            return redirect()->route('admin.login');
        }else{

        if (!auth('admin')->user()->hasRole($role)) {
            return redirect()->route('admin.dashboard');
        }
        }

        return $next($request);
    }
}
