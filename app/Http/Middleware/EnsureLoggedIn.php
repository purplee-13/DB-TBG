<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureLoggedIn
{
    public function handle($request, Closure $next)
    {
        // Jika belum login (tidak ada Auth & tidak ada session user_id)
        if (!Auth::check() && !$request->session()->has('user_id')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Jika sudah login, lanjutkan
        return $next($request);
    }
}
