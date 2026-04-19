<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nama_publisher', 
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- RELASI DATABASE ---

    // 1 User (Publisher) punya banyak Cerita
    public function ceritas()
    {
        return $this->hasMany(Cerita::class);
    }

    // 1 User bisa memfavoritkan banyak Cerita
    public function favorites()
    {
        // WAJIB tambahin ->withTimestamps() biar tanggal simpannya kebaca!
        return $this->belongsToMany(Cerita::class, 'favorites', 'user_id', 'cerita_id')->withTimestamps();
    }
}
