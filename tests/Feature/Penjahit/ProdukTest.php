<?php

namespace Tests\Feature\Penjahit;

use App\Models\Product;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProdukTest extends TestCase
{
    use RefreshDatabase;

    public function test_penjahit_without_toko_redirected_when_accessing_products()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');

        $this->actingAs($penjahit);

        $response = $this->get(route('penjahit.produk.index'));
        $response->assertRedirect(route('penjahit.toko.index'));
    }

    public function test_penjahit_can_list_own_products()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);

        $this->actingAs($penjahit);

        $response = $this->get(route('penjahit.produk.index'));
        $response->assertStatus(200);
    }

    public function test_penjahit_can_view_create_product_form()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        Toko::factory()->create(['penjahit_id' => $penjahit->id]);

        $this->actingAs($penjahit);

        $response = $this->get(route('penjahit.produk.create'));
        $response->assertStatus(200);
    }

    public function test_penjahit_can_view_edit_own_product()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);
        $product = Product::factory()->create(['toko_id' => $toko->id]);

        $this->actingAs($penjahit);

        $response = $this->get(route('penjahit.produk.edit', $product));
        $response->assertStatus(200);
    }

    public function test_penjahit_cannot_edit_other_penjahit_product()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);

        $otherPenjahit = User::factory()->create();
        $otherPenjahit->assignRole('penjahit');
        $otherToko = Toko::factory()->create(['penjahit_id' => $otherPenjahit->id]);
        $otherProduct = Product::factory()->create(['toko_id' => $otherToko->id]);

        $this->actingAs($penjahit);

        $response = $this->get(route('penjahit.produk.edit', $otherProduct));
        $response->assertStatus(403);
    }

    public function test_penjahit_cannot_delete_other_penjahit_product()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);

        $otherPenjahit = User::factory()->create();
        $otherPenjahit->assignRole('penjahit');
        $otherToko = Toko::factory()->create(['penjahit_id' => $otherPenjahit->id]);
        $otherProduct = Product::factory()->create(['toko_id' => $otherToko->id]);

        $this->actingAs($penjahit);

        $response = $this->delete(route('penjahit.produk.delete', $otherProduct));
        $response->assertStatus(403);
    }

    public function test_penjahit_can_store_product()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);
        $this->actingAs($penjahit);

        $response = $this->post(route('penjahit.produk.store'), [
            'nama_produk' => 'Kemeja Baru',
            'deskripsi' => 'Kemeja kualitas premium',
            'harga' => 200000,
            'foto' => UploadedFile::fake()->image('produk.jpg'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', [
            'nama_produk' => 'Kemeja Baru',
            'toko_id' => $toko->id,
        ]);
    }

    public function test_penjahit_can_delete_own_product()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);
        $product = Product::factory()->create(['toko_id' => $toko->id]);
        $this->actingAs($penjahit);

        $response = $this->delete(route('penjahit.produk.delete', $product));

        $response->assertRedirect();
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
