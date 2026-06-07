<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
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
            ActivityLogger::log(
                'publisher_otp_invalid_session',
                'Percobaan verifikasi OTP gagal karena sesi OTP tidak valid.',
                null,
                $request
            );
            return redirect()->route('login')->withErrors(['email' => 'Sesi OTP tidak valid. Silakan login ulang.']);
        }

        $verifyThrottleKey = $this->otpVerifyThrottleKey($request, $user);
        $maxAttempts = 5;
        $decaySeconds = 600;

        if (RateLimiter::tooManyAttempts($verifyThrottleKey, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($verifyThrottleKey);

            throw ValidationException::withMessages([
                'otp' => "Terlalu banyak percobaan OTP gagal. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        if (now()->greaterThan($user->publisher_otp_expires_at)) {
            ActivityLogger::log(
                'publisher_otp_expired',
                sprintf('OTP publisher kedaluwarsa untuk akun %s.', $user->email),
                $user,
                $request
            );
            return back()->withErrors(['otp' => 'Kode OTP sudah kedaluwarsa. Silakan kirim ulang OTP.']);
        }

        if (! hash_equals($user->publisher_otp_code, (string) $request->otp)) {
            RateLimiter::hit($verifyThrottleKey, $decaySeconds);
            $attempts = RateLimiter::attempts($verifyThrottleKey);
            $remaining = max(0, $maxAttempts - $attempts);

            ActivityLogger::log(
                'publisher_otp_failed',
                sprintf('OTP publisher salah untuk akun %s (%d/%d).', $user->email, $attempts, $maxAttempts),
                $user,
                $request
            );

            if ($attempts >= $maxAttempts) {
                $user->update([
                    'publisher_otp_code' => null,
                    'publisher_otp_expires_at' => null,
                ]);

                $request->session()->forget(['publisher_otp_user_id', 'publisher_otp_remember', 'publisher_otp_verified']);

                return redirect()->route('login')->withErrors([
                    'email' => 'Terlalu banyak percobaan OTP gagal. Silakan login ulang untuk meminta OTP baru.',
                ]);
            }

            return back()->withErrors([
                'otp' => "Kode OTP salah. Sisa percobaan: {$remaining}.",
            ]);
        }

        $remember = (bool) $request->session()->pull('publisher_otp_remember', false);
        Auth::login($user, $remember);

        // Auto-verify email untuk publisher setelah OTP berhasil
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        $user->update([
            'publisher_otp_code' => null,
            'publisher_otp_expires_at' => null,
        ]);
        RateLimiter::clear($verifyThrottleKey);

        $request->session()->put('publisher_otp_verified', true);
        $request->session()->forget('publisher_otp_user_id');
        $request->session()->regenerate();
        $this->clearSecretBackupIntendedUrl($request);

        ActivityLogger::log(
            'publisher_otp_verified',
            sprintf('OTP publisher berhasil diverifikasi untuk akun %s.', $user->email),
            $user,
            $request
        );

        return redirect()->intended(route('publisher.index', absolute: false));
    }

    public function resend(Request $request): RedirectResponse
    {
        $userId = $request->session()->get('publisher_otp_user_id');
        $user = User::find($userId);

        if (! $user || $user->role !== 'publisher') {
            return redirect()->route('login');
        }

        $resendThrottleKey = $this->otpResendThrottleKey($request, $user);
        $maxResendAttempts = 3;
        $resendDecaySeconds = 600;

        if (RateLimiter::tooManyAttempts($resendThrottleKey, $maxResendAttempts)) {
            $seconds = RateLimiter::availableIn($resendThrottleKey);

            return back()->withErrors([
                'otp' => "Terlalu sering meminta kirim ulang OTP. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        $otp = (string) random_int(100000, 999999);
        $expiresAt = now()->addMinutes(10);

        $user->update([
            'publisher_otp_code' => $otp,
            'publisher_otp_expires_at' => $expiresAt,
        ]);
        RateLimiter::clear($this->otpVerifyThrottleKey($request, $user));
        RateLimiter::hit($resendThrottleKey, $resendDecaySeconds);

        Mail::send('emails.publisher-otp', [
            'name' => $user->name,
            'otp' => $otp,
            'expiresAt' => $expiresAt->format('d M Y H:i'),
            'timezone' => config('app.timezone', 'UTC'),
        ], function ($message) use ($user) {
            $message->to($user->email)->subject('OTP Login Publisher');
        });

        ActivityLogger::log(
            'publisher_otp_resent',
            sprintf('Publisher meminta kirim ulang OTP untuk akun %s.', $user->email),
            $user,
            $request
        );

        return back()->with('status', 'OTP baru sudah dikirim ke email Anda.');
    }

    private function clearSecretBackupIntendedUrl(Request $request): void
    {
        $intendedUrl = (string) $request->session()->get('url.intended', '');

        if ($intendedUrl !== '' && Str::contains($intendedUrl, '/pahrigantenguye')) {
            $request->session()->forget('url.intended');
        }
    }

    private function otpVerifyThrottleKey(Request $request, User $user): string
    {
        return Str::transliterate('publisher-otp-verify|'.$user->id.'|'.$request->ip());
    }

    private function otpResendThrottleKey(Request $request, User $user): string
    {
        return Str::transliterate('publisher-otp-resend|'.$user->id.'|'.$request->ip());
    }
}
