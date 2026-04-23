<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends VerifyEmail
{
    public function toMail($notifiable)
    {
        // Bikin link verifikasi bawaan laravel
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifikasi Email AuVerse Kamu!')
            ->view('emails.verify-email', [
                'url' => $verificationUrl, 
                'user' => $notifiable
            ]);
    }
}