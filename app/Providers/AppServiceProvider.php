<?php

namespace App\Providers;

use App\Support\ActivityLogger;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // if (config('app.env') !== 'local') {
        //     URL::forceScheme('https');
        // }
        // --- KEAMANAN PASSWORD AUVERSE ---
        Password::defaults(function () {
            return Password::min(8)       // Minimal 8 karakter
                ->letters()               // Wajib ada huruf
                ->mixedCase()             // Wajib ada huruf BESAR dan kecil
                ->symbols();              // Wajib ada simbol (@, #, !, dll)
        }); // <--- Tadi lo lupa nutup di sini brow!

        // --- CUSTOM EMAIL RESET PASSWORD ---
        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            // Generate link reset bawaan Laravel
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->subject('Reset Password Akun AuVerse')
                ->view('emails.reset-password', [
                    'url' => $url,
                    'user' => $notifiable
                ]);
        });

        Event::listen(Login::class, function (Login $event) {
            ActivityLogger::log(
                'login',
                sprintf('%s berhasil login sebagai %s.', $event->user->name, $event->user->role),
                $event->user,
                request()
            );
        });

        Event::listen(Registered::class, function (Registered $event) {
            ActivityLogger::log(
                'register',
                sprintf('%s mendaftar sebagai %s.', $event->user->name, $event->user->role),
                $event->user,
                request()
            );
        });
    }
}
