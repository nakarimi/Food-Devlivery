<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()){
            $role=Auth::user()->role->name;
            $role=strtolower($role);
            if ($role=="admin") {
                return $next($request);
            }
            abort(403);
        }else{
            return redirect()->route('login');
        }
    }
}
