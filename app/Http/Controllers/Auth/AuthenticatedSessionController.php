<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $user = $request->getAuthenticatedUser();

        if ($user->role === 'publisher') {
            $otp = (string) random_int(100000, 999999);

            $user->update([
                'publisher_otp_code' => $otp,
                'publisher_otp_expires_at' => now()->addMinutes(10),
            ]);

            Mail::raw("Kode OTP login Publisher Anda: {$otp}. Berlaku sampai 10 menit.", function ($message) use ($user) {
                $message->to($user->email)->subject('OTP Login Publisher');
            });

            $request->session()->put('publisher_otp_user_id', $user->id);
            $request->session()->put('publisher_otp_remember', $request->boolean('remember'));
            $request->session()->put('publisher_otp_verified', false);

            return redirect()->route('publisher.otp.form')->with('status', 'Kode OTP sudah dikirim ke email Anda.');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->put('publisher_otp_verified', true);
        $request->session()->regenerate();

        return redirect()->intended(route('home', absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->forget(['publisher_otp_user_id', 'publisher_otp_remember', 'publisher_otp_verified']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
