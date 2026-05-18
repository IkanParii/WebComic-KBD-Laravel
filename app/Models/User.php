<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nama_publisher',
        'publisher_otp_code',
        'publisher_otp_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'publisher_otp_code',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'publisher_otp_expires_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function ceritas()
    {
        return $this->hasMany(Cerita::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(Cerita::class, 'favorites', 'user_id', 'cerita_id')->withTimestamps();
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\CustomVerifyEmail);
    }
}
