<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TokoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'penjahit_id' => User::factory(),
            'nama_toko' => fake()->company(),
            'deskripsi' => fake()->paragraph(),
            'alamat' => fake()->address(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'logo' => 'logo-default.png',
            'bank' => fake()->randomElement(['bca', 'bni', 'bri', 'mandiri']),
            'no_rekening' => fake()->bankAccountNumber(),
            'atas_nama' => fake()->name(),
            'no_wa' => fake()->phoneNumber(),
        ];
    }
}
