<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Genre; // <-- Jangan lupa import Model Genre

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // List genre
        $genres = [
            'Action', 'Romance', 'Comedy', 'Slice of Life', 
            'Fantasy', 'Horror', 'Drama', 'Mystery', 
            'Thriller'
        ];

        foreach ($genres as $genre) {
            // firstOrCreate biar kalau di-run 2x nggak ada data yang dobel
            Genre::firstOrCreate([
                'nama_genre' => $genre
            ]);
        }
    }
}
