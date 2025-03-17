<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::updateOrCreate(['id' => 1], [
            'toko_id' => 1,
            'nama_produk' => 'Kebaya Elegant',
            'deskripsi' => 'Deskripsi Detail 1',
            'harga' => 30000,
            'diskon' => null,
            'foto' => 'kebaya_elegan.jpeg',
        ]);

        Product::updateOrCreate(['id' => 2], [
            'toko_id' => 1,
            'nama_produk' => 'Kebaya Modern',
            'deskripsi' => 'Deskripsi Detail 2',
            'harga' => 40000,
            'diskon' => null,
            'foto' => 'kebaya_modern.jpg',
        ]);

        Product::updateOrCreate(['id' => 3], [
            'toko_id' => 1,
            'nama_produk' => 'Kemeja Formal',
            'deskripsi' => 'Deskripsi Detail 3',
            'harga' => 50000,
            'diskon' => null,
            'foto' => 'kemeja_formal.jpg',
        ]);
    }
}
