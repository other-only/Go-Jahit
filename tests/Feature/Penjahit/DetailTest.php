<?php

namespace Tests\Feature\Penjahit;

use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class DetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_penjahit_can_list_own_details()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);

        $this->actingAs($penjahit);

        $response = $this->get(route('penjahit.detail.index'));
        $response->assertStatus(200);
    }

    public function test_penjahit_can_view_create_detail_form()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        Toko::factory()->create(['penjahit_id' => $penjahit->id]);

        $this->actingAs($penjahit);

        $response = $this->get(route('penjahit.detail.create'));
        $response->assertStatus(200);
    }

    public function test_penjahit_cannot_edit_other_penjahit_detail()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);

        Product::factory()->create(['toko_id' => $toko->id]);

        $otherPenjahit = User::factory()->create();
        $otherPenjahit->assignRole('penjahit');
        $otherToko = Toko::factory()->create(['penjahit_id' => $otherPenjahit->id]);
        $otherProduct = Product::factory()->create(['toko_id' => $otherToko->id]);
        $otherDetail = ProductDetail::factory()->create(['toko_id' => $otherProduct->id]);

        $this->actingAs($penjahit);

        $response = $this->get(route('penjahit.detail.edit', $otherDetail));
        $response->assertStatus(403);
    }

    public function test_penjahit_cannot_delete_other_penjahit_detail()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);

        Product::factory()->create(['toko_id' => $toko->id]);

        $otherPenjahit = User::factory()->create();
        $otherPenjahit->assignRole('penjahit');
        $otherToko = Toko::factory()->create(['penjahit_id' => $otherPenjahit->id]);
        $otherProduct = Product::factory()->create(['toko_id' => $otherToko->id]);
        $otherDetail = ProductDetail::factory()->create(['toko_id' => $otherProduct->id]);

        $this->actingAs($penjahit);

        $response = $this->delete(route('penjahit.detail.delete', $otherDetail));
        $response->assertStatus(403);
    }

    public function test_penjahit_can_store_detail()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);
        // Need a product first because product_details.toko_id FK references products.id
        Product::factory()->create(['toko_id' => $toko->id]);
        $this->actingAs($penjahit);

        $response = $this->post(route('penjahit.detail.store'), [
            'nama_detail' => 'Bahan Katun',
            'deskripsi' => 'Bahan katun premium',
            'harga' => 75000,
            'foto' => UploadedFile::fake()->image('detail.jpg'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('product_details', ['nama_detail' => 'Bahan Katun']);
    }

    public function test_penjahit_can_update_own_detail()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);
        $product = Product::factory()->create(['toko_id' => $toko->id]);
        $detail = ProductDetail::factory()->create(['toko_id' => $product->id]);
        $this->actingAs($penjahit);

        $response = $this->post(route('penjahit.detail.update', $detail), [
            'nama_detail' => 'Katun Updated',
            'deskripsi' => 'Deskripsi updated',
            'harga' => 80000,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('product_details', [
            'id' => $detail->id,
            'nama_detail' => 'Katun Updated',
        ]);
    }
}
