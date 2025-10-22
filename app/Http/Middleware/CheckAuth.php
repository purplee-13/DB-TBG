<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login (ada session user_id)
        if (!session()->has('user_id')) {
            return redirect()->route('login')
                ->withErrors(['login' => 'Silakan login terlebih dahulu untuk mengakses halaman ini!']);
        }

        return $next($request);
    }
}