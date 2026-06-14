<?php

namespace Tests\Feature\Penjahit;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PesananTest extends TestCase
{
    use RefreshDatabase;

    public function test_penjahit_can_list_own_orders()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);
        $product = Product::factory()->create(['toko_id' => $toko->id]);
        $detail = ProductDetail::factory()->create(['toko_id' => $product->id]);
        Order::factory()->create([
            'toko_id' => $toko->id,
            'product_id' => $product->id,
            'product_detail_id' => $detail->id,
        ]);

        $this->actingAs($penjahit);

        $response = $this->get(route('penjahit.pesanan.index'));
        $response->assertStatus(200);
    }

    public function test_penjahit_can_view_order_detail()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);
        $product = Product::factory()->create(['toko_id' => $toko->id]);
        $detail = ProductDetail::factory()->create(['toko_id' => $product->id]);
        $order = Order::factory()->create([
            'toko_id' => $toko->id,
            'product_id' => $product->id,
            'product_detail_id' => $detail->id,
        ]);

        $this->actingAs($penjahit);

        $response = $this->get(route('penjahit.pesanan.detail', $order));
        $response->assertStatus(200);
    }

    public function test_penjahit_can_update_order_status()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);
        $product = Product::factory()->create(['toko_id' => $toko->id]);
        $detail = ProductDetail::factory()->create(['toko_id' => $product->id]);
        $order = Order::factory()->create([
            'toko_id' => $toko->id,
            'product_id' => $product->id,
            'product_detail_id' => $detail->id,
            'status' => 'menunggu-konfirmasi',
        ]);

        $this->actingAs($penjahit);

        $response = $this->post(route('penjahit.pesanan.status', $order), [
            'status' => 'dalam-proses',
        ]);
        $response->assertStatus(302);
        $this->assertEquals('dalam-proses', $order->fresh()->status);
    }

    public function test_penjahit_can_confirm_order()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);
        $product = Product::factory()->create(['toko_id' => $toko->id]);
        $detail = ProductDetail::factory()->create(['toko_id' => $product->id]);
        $order = Order::factory()->create([
            'toko_id' => $toko->id,
            'product_id' => $product->id,
            'product_detail_id' => $detail->id,
        ]);

        $this->actingAs($penjahit);

        $response = $this->post(route('penjahit.pesanan.confirm', $order));
        $response->assertStatus(302);
        $this->assertEquals('selesai', $order->fresh()->status);
    }

    public function test_penjahit_cannot_access_other_toko_order()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);

        $otherPenjahit = User::factory()->create();
        $otherPenjahit->assignRole('penjahit');
        $otherToko = Toko::factory()->create(['penjahit_id' => $otherPenjahit->id]);
        $otherProduct = Product::factory()->create(['toko_id' => $otherToko->id]);
        $otherDetail = ProductDetail::factory()->create(['toko_id' => $otherProduct->id]);
        $otherOrder = Order::factory()->create([
            'toko_id' => $otherToko->id,
            'product_id' => $otherProduct->id,
            'product_detail_id' => $otherDetail->id,
        ]);

        $this->actingAs($penjahit);

        $response = $this->get(route('penjahit.pesanan.detail', $otherOrder));
        $response->assertStatus(403);
    }
}
