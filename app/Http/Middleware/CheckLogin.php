<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if ($guard == "karyawan") {
            if (Auth::guard('karyawan')->check()) {
                // Redirect to dashboard if already logged in
                return redirect('/mcu-user');
            }
            return redirect('/');
        } else {
            if (Auth::check()) {
                // Redirect to dashboard if already logged in
                return redirect('/panel-admin/dashboard');
            }
            return redirect('/panel-admin');
        }


        // Redirect to login if not logged in
    }
}
