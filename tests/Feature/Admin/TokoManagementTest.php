<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Factories\TokoFactory;
use Tests\TestCase;

class TokoManagementTest extends TestCase
{
    public function test_admin_can_list_tokos()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        TokoFactory::new()->create();

        $response = $this->get('/admin/seting/toko');

        $response->assertStatus(200);
    }

    public function test_admin_can_view_edit_toko_form()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        $toko = TokoFactory::new()->create();

        $response = $this->get('/admin/seting/toko/edit/' . $toko->id);

        $response->assertStatus(200);
    }

    public function test_admin_can_update_toko()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        $toko = TokoFactory::new()->create();

        $response = $this->post('/admin/seting/toko/update/' . $toko->id, [
            'nama_toko' => 'Updated Toko',
            'deskripsi' => 'Updated deskripsi',
            'alamat' => 'Updated alamat',
            'no_wa' => '081234567890',
            'bank' => 'bca',
            'no_rekening' => '1234567890',
            'atas_nama' => 'Updated Name',
        ]);

        $response->assertRedirect(route('admin.toko.index'));
        $this->assertDatabaseHas('tokos', [
            'id' => $toko->id,
            'nama_toko' => 'Updated Toko',
        ]);
    }

    public function test_admin_cannot_update_toko_with_invalid_bank()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        $toko = TokoFactory::new()->create();

        $response = $this->post('/admin/seting/toko/update/' . $toko->id, [
            'nama_toko' => 'Updated Toko',
            'deskripsi' => 'Updated deskripsi',
            'alamat' => 'Updated alamat',
            'no_wa' => '081234567890',
            'bank' => 'invalid',
            'no_rekening' => '1234567890',
            'atas_nama' => 'Updated Name',
        ]);

        $response->assertSessionHasErrors('bank');
    }

    public function test_admin_cannot_update_toko_without_required_fields()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        $toko = TokoFactory::new()->create();

        $response = $this->post('/admin/seting/toko/update/' . $toko->id, [
            'deskripsi' => 'Updated deskripsi',
            'alamat' => 'Updated alamat',
            'no_wa' => '081234567890',
            'bank' => 'bca',
            'no_rekening' => '1234567890',
            'atas_nama' => 'Updated Name',
        ]);

        $response->assertSessionHasErrors('nama_toko');
    }
}
