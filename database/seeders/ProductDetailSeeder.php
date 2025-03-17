<?php

namespace Database\Seeders;

use App\Models\ProductDetail;
use Illuminate\Database\Seeder;

class ProductDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductDetail::updateOrCreate(['id' => 1], [
            'toko_id' => 1,
            'nama_detail' => 'Polyester',
            'deskripsi' => 'Berbahan Polyester',
            'harga' => 100000,
            'diskon' => null,
            'foto' => 'polyester.jpg',
        ]);

        ProductDetail::updateOrCreate(['id' => 2], [
            'toko_id' => 1,
            'nama_detail' => 'Sutra',
            'deskripsi' => 'Berbahan Sutra',
            'harga' => 200000,
            'diskon' => null,
            'foto' => 'sutra.jpg',
        ]);
    }
}
