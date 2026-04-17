<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPublisher
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user udah login dan role-nya 'publisher'
        if (Auth::check() && Auth::user()->role === 'publisher') {
            return $next($request);
        }

        // Kalau user biasa nyoba masuk, tendang balik ke dashboard biasa/home
        return redirect('/dashboard')->with('error', 'Akses ditolak! Anda bukan Publisher.');
    }
}
