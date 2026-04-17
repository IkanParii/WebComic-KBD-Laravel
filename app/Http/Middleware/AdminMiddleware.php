<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user udah login DAN role-nya adalah admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request); // Kalo bener admin, bolehin masuk
        }

        // Kalo bukan admin, tendang balik ke halaman home
        return redirect('/home')->with('error', 'Akses ditolak! Area ini khusus Admin.');
    }
}