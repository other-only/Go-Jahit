<?php

namespace Tests\Feature\Penjahit;

use App\Models\Toko;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class TokoTest extends TestCase
{
    use RefreshDatabase;

    public function test_penjahit_can_view_own_toko()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        Toko::factory()->create(['penjahit_id' => $penjahit->id]);

        $this->actingAs($penjahit);

        $response = $this->get(route('penjahit.toko.index'));
        $response->assertStatus(200);
    }

    public function test_penjahit_can_edit_own_toko()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        Toko::factory()->create(['penjahit_id' => $penjahit->id]);

        $this->actingAs($penjahit);

        $response = $this->get(route('penjahit.toko.edit'));
        $response->assertStatus(200);
    }

    public function test_penjahit_can_update_own_toko()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        Toko::factory()->create(['penjahit_id' => $penjahit->id]);

        $this->actingAs($penjahit);

        $response = $this->post(route('penjahit.toko.update'), [
            'nama_toko' => 'Toko Baru',
            'deskripsi' => 'Deskripsi toko baru',
            'alamat' => 'Alamat baru',
            'no_wa' => '08123456789',
            'bank' => 'bca',
            'no_rekening' => '1234567890',
            'atas_nama' => 'John Doe',
            'logo' => UploadedFile::fake()->image('logo.jpg'),
        ]);

        $response->assertRedirect(route('penjahit.toko.index'));

        $this->assertEquals('Toko Baru', $penjahit->toko->fresh()->nama_toko);
    }
}
