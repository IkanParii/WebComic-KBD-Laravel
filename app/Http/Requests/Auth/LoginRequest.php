<?php

namespace App\Http\Requests\Auth;

use App\Support\ActivityLogger;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'g-recaptcha-response' => ['required', function ($attribute, $value, $fail) {
                $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => config('services.recaptcha.secret_key'),
                    'response' => $value,
                    'remoteip' => request()->ip(),
                ]);

                if (! $response->json('success')) {
                    $fail('Verifikasi reCAPTCHA gagal. Silakan muat ulang halaman dan coba lagi.');
                }
            }],
        ];
    }

    /**
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * @throws ValidationException
     */
    public function getAuthenticatedUser(): User
    {
        $this->ensureIsNotRateLimited();

        $user = User::where('email', $this->string('email'))->first();

        if (! $user || ! Hash::check($this->string('password'), $user->password)) {
            RateLimiter::hit($this->throttleKey(), 300);
            $attempts = RateLimiter::attempts($this->throttleKey());
            $email = (string) $this->string('email');

            ActivityLogger::log(
                'login_failed',
                sprintf('Percobaan login gagal untuk email %s (%d/3).', $email, $attempts),
                null,
                $this,
                $email !== '' ? $email : 'Guest',
                'guest'
            );

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        return $user;
    }

    /**
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 3)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());
        $lockoutLogKey = $this->throttleKey().'|lockout-log';

        if (! RateLimiter::tooManyAttempts($lockoutLogKey, 1)) {
            $email = (string) $this->string('email');

            ActivityLogger::log(
                'login_lockout',
                sprintf('Akun/email %s terkunci sementara selama %d detik setelah 3 kali gagal login.', $email, $seconds),
                null,
                $this,
                $email !== '' ? $email : 'Guest',
                'guest'
            );

            RateLimiter::hit($lockoutLogKey, $seconds);
        }

        throw ValidationException::withMessages([
            'email' => "Terlalu banyak percobaan gagal. Coba lagi dalam {$seconds} detik.",
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
