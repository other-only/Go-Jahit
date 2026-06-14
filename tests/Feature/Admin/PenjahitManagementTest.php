<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Factories\TokoFactory;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PenjahitManagementTest extends TestCase
{
    public function test_admin_can_list_penjahits()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        TokoFactory::new()->create(['penjahit_id' => $penjahit->id]);

        $response = $this->get('/admin/penjahit');

        $response->assertStatus(200);
    }

    public function test_admin_can_view_create_penjahit_form()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        $response = $this->get('/admin/penjahit/add');

        $response->assertStatus(200);
    }

    public function test_admin_can_store_penjahit_with_valid_data()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        $response = $this->post('/admin/penjahit/store', [
            'name' => 'Penjahit Baru',
            'email' => 'penjahitbaru@test.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'nama_toko' => 'Toko Baru',
            'alamat_toko' => 'Jl. Baru No. 1',
            'deskripsi_toko' => 'Toko jahit terpercaya',
            'no_wa' => '08123456789',
            'bank' => 'bri',
            'no_rekening' => '1234567890',
            'atas_nama' => 'Penjahit Baru',
            'foto_toko' => UploadedFile::fake()->image('toko.jpg'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['email' => 'penjahitbaru@test.com']);
        $this->assertDatabaseHas('tokos', ['nama_toko' => 'Toko Baru']);
    }

    public function test_admin_cannot_store_penjahit_with_duplicate_email()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        User::factory()->create(['email' => 'existing@test.com']);

        $response = $this->post('/admin/penjahit/store', [
            'name' => 'Penjahit',
            'email' => 'existing@test.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'nama_toko' => 'Toko',
            'alamat_toko' => 'Alamat',
            'deskripsi_toko' => 'Deskripsi',
            'no_wa' => '08123456789',
            'bank' => 'bri',
            'no_rekening' => '1234567890',
            'atas_nama' => 'Penjahit',
            'foto_toko' => UploadedFile::fake()->image('toko.jpg'),
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_admin_cannot_store_penjahit_without_foto_toko()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        $response = $this->post('/admin/penjahit/store', [
            'name' => 'Penjahit',
            'email' => 'penjahit@test.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'nama_toko' => 'Toko',
            'alamat_toko' => 'Alamat',
            'deskripsi_toko' => 'Deskripsi',
            'no_wa' => '08123456789',
            'bank' => 'bri',
            'no_rekening' => '1234567890',
            'atas_nama' => 'Penjahit',
        ]);

        $response->assertSessionHasErrors(['foto_toko']);
    }
}
