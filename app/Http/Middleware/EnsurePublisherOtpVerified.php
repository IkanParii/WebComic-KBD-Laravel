<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePublisherOtpVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan user sudah login dan merupakan publisher
        if (Auth::check() && Auth::user()->role === 'publisher') {
            // Cek apakah OTP sudah diverifikasi
            if (!$request->session()->get('publisher_otp_verified', false)) {
                return redirect()->route('publisher.otp.form')->withErrors([
                    'otp' => 'Verifikasi OTP diperlukan untuk akses Publisher.',
                ]);
            }
        }

        return $next($request);
    }
}
