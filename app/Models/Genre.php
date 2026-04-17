<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_genre'
    ];

    // --- RELASI DATABASE ---

    // Genre ini dimiliki oleh banyak Cerita (Many-to-Many)
    public function ceritas()
    {
        return $this->belongsToMany(Cerita::class);
    }
}
