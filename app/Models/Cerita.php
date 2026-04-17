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
        'tanggal_rilis'
    ];

    // --- RELASI DATABASE ---

    // Cerita ini milik 1 User (Publisher)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Cerita ini punya banyak Genre (Many-to-Many)
    public function genres()
    {
        // Nggak perlu sebutin nama tabel pivot 'cerita_genre' karena udah sesuai standar Laravel
        return $this->belongsToMany(Genre::class);
    }

    // Cerita ini difavoritkan oleh banyak User (Many-to-Many)
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }
}