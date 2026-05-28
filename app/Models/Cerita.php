<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cerita extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'judul',
        'deskripsi_singkat',
        'tanggal_rilis',
        'isi_cerita',
    ];

    // Cerita dimiliki oleh satu User (Publisher)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Cerita bisa punya banyak Genre (Many-to-Many via tabel cerita_genre)
    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    // Cerita bisa difavoritkan oleh banyak User (Many-to-Many via tabel favorites)
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }
}
