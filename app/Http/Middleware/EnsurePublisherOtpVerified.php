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
        if (Auth::check() && Auth::user()->role === 'publisher' && ! $request->session()->get('publisher_otp_verified', false)) {
            return redirect()->route('publisher.otp.form')->withErrors([
                'otp' => 'Verifikasi OTP diperlukan untuk akses Publisher.',
            ]);
        }

        return $next($request);
    }
}
