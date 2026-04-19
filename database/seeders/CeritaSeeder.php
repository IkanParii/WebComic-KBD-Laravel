<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cerita;
use App\Models\Genre;
use App\Models\User; // Tambahin import model User
use Faker\Factory as Faker;

class CeritaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Panggil Faker versi Indonesia
        $faker = Faker::create('id_ID');

        // Ambil semua ID genre
        $genreIds = Genre::pluck('id')->toArray();

        // Jaga-jaga kalau tabel genre kosong
        if (empty($genreIds)) {
            $this->command->error('Tabel genre masih kosong brow! Jalanin php artisan db:seed --class=GenreSeeder dulu ya.');
            return;
        }

        // Ambil semua ID user buat dijadiin publisher
        $userIds = User::pluck('id')->toArray();

        // Jaga-jaga kalau tabel user belum ada datanya sama sekali
        if (empty($userIds)) {
            $this->command->error('Tabel users masih kosong brow! Bikin user/publisher dulu ya sebelum nge-seed cerita.');
            return;
        }

        $this->command->info('Mulai bikin 50 cerita random...');

        // Looping 50 kali buat bikin 50 Cerita
        for ($i = 0; $i < 50; $i++) {
            $cerita = Cerita::create([
                // Bikin judul random sekitar 2-4 kata
                'judul' => rtrim($faker->sentence(mt_rand(2, 4)), '.'),
                
                // Sesuaiin sama nama kolom di migration lo
                'deskripsi_singkat' => $faker->paragraph(2),
                
                // Bikin isi cerita random 4 paragraf
                'isi_cerita' => $faker->paragraphs(4, true),

                // Ambil random ID dari tabel users
                'user_id' => $faker->randomElement($userIds),

                // Tambahin tanggal rilis random
                'tanggal_rilis' => $faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d'),
            ]);

            // Pasangin 1 sampe 3 genre secara acak ke cerita yang baru dibikin ini
            $randomGenres = $faker->randomElements($genreIds, mt_rand(1, 3));
            $cerita->genres()->attach($randomGenres);
        }

        $this->command->info('Sukses bikin 50 cerita random!');
    }
}