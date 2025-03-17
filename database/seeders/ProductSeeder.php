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
        Product::create([
            'toko_id' => 1,
            'nama_produk' => 'Kebaya Elegant',
            'deskripsi' => 'Deskripsi Detail 1',
            'harga' => 30000,
            'diskon' => null,
            'foto' => asset('assets/icons/kebaya_elegan.jpeg'),
        ]);

        Product::create([
            'toko_id' => 1,
            'nama_produk' => 'Kebaya Modern',
            'deskripsi' => 'Deskripsi Detail 2',
            'harga' => 40000,
            'diskon' => null,
            'foto' => asset('assets/icons/kebaya_modern.jpg'),
        ]);

        Product::create([
            'toko_id' => 1,
            'nama_produk' => 'Kemeja Formal',
            'deskripsi' => 'Deskripsi Detail 3',
            'harga' => 50000,
            'diskon' => null,
            'foto' => asset('assets/icons/kemeja_formal.jpg'),
        ]);
    }
}
