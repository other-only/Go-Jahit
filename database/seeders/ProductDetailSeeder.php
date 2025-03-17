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
        ProductDetail::create([
            'toko_id' => 1,
            'nama_detail' => 'Polyester',
            'deskripsi' => 'Berbahan Polyester',
            'harga' => 'Rp. 100.000',
            'diskon' => null,
            'foto' => asset('assets/icons/polyester.jpg'),
        ]);

        ProductDetail::create([
            'toko_id' => 1,
            'nama_detail' => 'Sutra',
            'deskripsi' => 'Berbahan Sutra',
            'harga' => 'Rp. 200.000',
            'diskon' => null,
            'foto' => asset('assets/icons/sutra.jpg'),
        ]);
    }
}
