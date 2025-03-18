<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Admin',
                'email' => 'admin@email.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Penjahit',
                'email' => 'penjahit@email.com',
                'password' => bcrypt('password'),
                'role' => 'penjahit',
            ],
            [
                'name' => 'Pelanggan',
                'email' => 'pelanggan@email.com',
                'password' => bcrypt('password'),
                'role' => 'pelanggan',
                'alamat' => 'Jl. Kebon Jeruk',
                'no_hp' => '08123456789',
                'latitude' => '-6.200000',
                'longitude' => '106.816667',
            ],
        ];

        foreach ($data as $userData) {
            $user = User::updateOrCreate(['email' => $userData['email']], [
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => $userData['password'],
                'alamat' => $userData['alamat'] ?? null,
                'no_hp' => $userData['no_hp'] ?? null,
                'latitude' => $userData['latitude'] ?? null,
                'longitude' => $userData['longitude'] ?? null,
            ]);
            $user->syncRoles($userData['role']);
        }
    }
}
