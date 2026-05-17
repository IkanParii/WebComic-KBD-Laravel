<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PublisherOtpController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('publisher_otp_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.publisher-otp');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $userId = $request->session()->get('publisher_otp_user_id');
        $user = User::find($userId);

        if (! $user || ! $user->publisher_otp_code || ! $user->publisher_otp_expires_at) {
            return redirect()->route('login')->withErrors(['email' => 'Sesi OTP tidak valid. Silakan login ulang.']);
        }

        if (now()->greaterThan($user->publisher_otp_expires_at)) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kedaluwarsa. Silakan kirim ulang OTP.']);
        }

        if (! hash_equals($user->publisher_otp_code, (string) $request->otp)) {
            return back()->withErrors(['otp' => 'Kode OTP salah.']);
        }

        $remember = (bool) $request->session()->pull('publisher_otp_remember', false);
        Auth::login($user, $remember);

        $user->update([
            'publisher_otp_code' => null,
            'publisher_otp_expires_at' => null,
        ]);

        $request->session()->put('publisher_otp_verified', true);
        $request->session()->forget('publisher_otp_user_id');
        $request->session()->regenerate();

        return redirect()->intended(route('home', absolute: false));
    }

    public function resend(Request $request): RedirectResponse
    {
        $userId = $request->session()->get('publisher_otp_user_id');
        $user = User::find($userId);

        if (! $user || $user->role !== 'publisher') {
            return redirect()->route('login');
        }

        $otp = (string) random_int(100000, 999999);
        $expiresAt = now()->addMinutes(10);

        $user->update([
            'publisher_otp_code' => $otp,
            'publisher_otp_expires_at' => $expiresAt,
        ]);

        Mail::send('emails.publisher-otp', [
            'name' => $user->name,
            'otp' => $otp,
            'expiresAt' => $expiresAt->format('d M Y H:i'),
            'timezone' => config('app.timezone', 'UTC'),
        ], function ($message) use ($user) {
            $message->to($user->email)->subject('OTP Login Publisher');
        });

        return back()->with('status', 'OTP baru sudah dikirim ke email Anda.');
    }
}
