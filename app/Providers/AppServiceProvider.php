<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password; 
use Illuminate\Auth\Notifications\ResetPassword; 
use Illuminate\Notifications\Messages\MailMessage; 
use Illuminate\Support\Facades\URL;

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
    }
}