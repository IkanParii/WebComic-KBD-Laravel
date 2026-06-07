<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PublisherVerificationMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Jika bukan publisher, lanjut saja
        if (!$user || $user->role !== 'publisher') {
            return $next($request);
        }

        // Untuk publisher, cek OTP verification (skip email verification)
        if (!$request->session()->get('publisher_otp_verified', false)) {
            return redirect()->route('publisher.otp.form')->withErrors([
                'otp' => 'Verifikasi OTP diperlukan untuk akses Publisher.',
            ]);
        }

        return $next($request);
    }
}