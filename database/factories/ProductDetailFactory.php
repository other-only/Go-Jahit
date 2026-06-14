<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductDetailFactory extends Factory
{
    public function definition(): array
    {
        return [
            'toko_id' => Product::factory(),
            'nama_detail' => fake()->words(2, true),
            'deskripsi' => fake()->paragraph(),
            'harga' => (string) fake()->numberBetween(20000, 200000),
            'foto' => 'detail-default.png',
        ];
    }
}
