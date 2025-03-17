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
        Toko::create([
            'penjahit_id' => 2,
            'nama_toko' => 'Toko 1',
            'deskripsi' => 'Deskripsi Toko 1',
            'alamat' => 'Alamat Toko 1',
            'latitude' => 'Latitude Toko 1',
            'longitude' => 'Longitude Toko 1',
            'logo' => asset('assets/icons/toko.jpg'),
        ]);
    }
}
