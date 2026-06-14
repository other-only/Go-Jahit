<?php

namespace Database\Factories;

use App\Models\Toko;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'toko_id' => Toko::factory(),
            'nama_produk' => fake()->words(2, true),
            'deskripsi' => fake()->paragraph(),
            'harga' => (string) fake()->numberBetween(50000, 500000),
            'foto' => 'produk-default.png',
        ];
    }
}
