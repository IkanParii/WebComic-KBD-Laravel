<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class CeritaFactory extends Factory
{
    public function definition(): array
    {
        return [
            // Kalau nggak dikasih user_id, dia bakal otomatis bikin User baru
            'user_id' => User::factory(), 
            'judul' => fake()->sentence(3),
            'deskripsi_singkat' => fake()->paragraph(),
            'tanggal_rilis' => now()->toDateString(),
            'isi_cerita' => fake()->text(500),
        ];
    }
}