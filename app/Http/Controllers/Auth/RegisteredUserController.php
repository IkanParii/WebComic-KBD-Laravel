<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ManualCaptcha;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        return view('auth.register', [
            'captchaQuestion' => ManualCaptcha::question(request(), 'register'),
        ]);
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
            'captcha_answer' => ['required', 'string', function ($attribute, $value, $fail) use ($request) {
                $answer = (string) $request->session()->get('manual_captcha.register.answer', '');

                if ($answer === '' || trim((string) $value) !== $answer) {
                    $fail('Jawaban CAPTCHA salah. Coba lagi.');
                }
            }],
        ], [
            'nama_publisher.required_if' => 'Nama publisher wajib diisi jika memilih publisher.',
            'captcha_answer.required' => 'Wajib isi CAPTCHA manual.',
        ]);

        if ($validator->fails()) {
            RateLimiter::hit($throttleKey, $decaySeconds);
            ManualCaptcha::generate($request, 'register');
            throw new ValidationException($validator);
        }

        RateLimiter::clear($throttleKey);
        ManualCaptcha::generate($request, 'register');

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
