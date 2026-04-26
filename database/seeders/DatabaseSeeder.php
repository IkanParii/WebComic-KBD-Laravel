<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Bawaan dari Laravel (Boleh dihapus, boleh dibiarin)
        // User::factory(10)->create();


        $this->call([
            GenreSeeder::class,
        ]);
    }
}
