<?php

namespace Database\Seeders;

use App\Models\Toko;
use Illuminate\Database\Seeder;

class TokoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Toko::updateOrCreate(['id' => 1], [
            'penjahit_id' => 2,
            'nama_toko' => 'Toko 1',
            'deskripsi' => 'Deskripsi Toko 1',
            'alamat' => 'Alamat Toko 1',
            'latitude' => 'Latitude Toko 1',
            'longitude' => 'Longitude Toko 1',
            'logo' => 'toko.jpg',
            'bank' => 'BCA',
            'no_rekening' => '1234567890',
            'atas_nama' => 'John Doe',
        ]);
    }
}
