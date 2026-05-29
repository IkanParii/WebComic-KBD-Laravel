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
    public function register(): void {}

    public function boot(): void
    {
        // Password policy global: min 12 karakter, huruf besar+kecil, simbol (NIST SP 800-63B)
        Password::defaults(function () {
            return Password::min(12)
                ->letters()
                ->mixedCase()
                ->symbols();
        });

        // Custom template email reset password
        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->subject('Reset Password Akun AuVerse')
                ->view('emails.reset-password', [
                    'url' => $url,
                    'user' => $notifiable,
                ]);
        });

        // Catat setiap login berhasil ke activity log
        Event::listen(Login::class, function (Login $event) {
            ActivityLogger::log(
                'login',
                sprintf('%s berhasil login sebagai %s.', $event->user->name, $event->user->role),
                $event->user,
                request()
            );
        });

        // Catat setiap registrasi baru ke activity log
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
