<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $throttleKey = $this->throttleKey($request);
        $maxAttempts = 3;
        $decaySeconds = 300;

        if (RateLimiter::tooManyAttempts($throttleKey, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan gagal. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'in:user,publisher'],
            'nama_publisher' => ['required_if:role,publisher', 'nullable', 'string', 'max:255'],
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
        ], [
            'nama_publisher.required_if' => 'Nama publisher wajib diisi jika memilih publisher.',
            'g-recaptcha-response.required' => 'Wajib menyelesaikan verifikasi reCAPTCHA.',
        ]);

        if ($validator->fails()) {
            RateLimiter::hit($throttleKey, $decaySeconds);
            throw new ValidationException($validator);
        }

        RateLimiter::clear($throttleKey);

        $user = User::create([
            'name' => strip_tags($request->name),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'nama_publisher' => $request->role === 'publisher' ? strip_tags((string) $request->nama_publisher) : null,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('home', absolute: false));
    }

    private function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower((string) $request->input('email')).'|'.$request->ip().'|register');
    }
}
