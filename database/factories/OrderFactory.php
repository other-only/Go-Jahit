<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $toko = Toko::factory()->create();
        $product = Product::factory()->create(['toko_id' => $toko->id]);
        $detail = ProductDetail::factory()->create(['toko_id' => $product->id]);

        return [
            'kode_order' => 'BK-' . fake()->unique()->numerify('########'),
            'toko_id' => $toko->id,
            'product_id' => $product->id,
            'product_detail_id' => $detail->id,
            'pelanggan_id' => User::factory(),
            'total_harga' => (string) fake()->numberBetween(100000, 1000000),
            'bayar' => fake()->randomElement(['cod', 'transfer']),
            'status' => 'menunggu-konfirmasi',
            'jumlah_baju' => (string) fake()->numberBetween(1, 5),
            'jumlah_kain' => (string) fake()->numberBetween(1, 3),
            'ukuran_baju' => fake()->randomElement(['S', 'M', 'L', 'XL']),
            'nama_penerima' => fake()->name(),
            'alamat_penerima' => fake()->address(),
            'no_hp_penerima' => fake()->phoneNumber(),
        ];
    }
}
