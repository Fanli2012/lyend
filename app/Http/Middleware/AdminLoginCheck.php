<?php

namespace App\Http\Middleware;

use Closure;

class AdminLoginCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!session()->has('admin_info')) {
            redirect()->route('admin_login');
        } else {

        }

        return $next($request);
    }
}
