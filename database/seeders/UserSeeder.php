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
        ];

        foreach ($data as $userData) {
            $user = User::updateOrCreate(['email' => $userData['email']], [
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => $userData['password'],
            ]);
            $user->syncRoles($userData['role']);
        }
    }
}
