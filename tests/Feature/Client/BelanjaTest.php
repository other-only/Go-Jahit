<?php

namespace Tests\Feature\Client;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BelanjaTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_browse_shops()
    {
        $response = $this->get(route('client.belanja'));

        $response->assertStatus(200);
    }

    public function test_guest_can_view_order_form()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);
        $product = Product::factory()->create(['toko_id' => $toko->id]);
        ProductDetail::factory()->create(['toko_id' => $product->id]);

        $response = $this->get(route('client.order', $toko));

        $response->assertStatus(200);
    }

    public function test_guest_can_submit_order_with_valid_data()
    {
        $pelanggan = User::factory()->create();
        $pelanggan->assignRole('pelanggan');
        $this->actingAs($pelanggan);

        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);
        $product = Product::factory()->create(['toko_id' => $toko->id]);
        $detail = ProductDetail::factory()->create(['toko_id' => $product->id]);

        $response = $this->post(route('client.order.post', $toko), [
            'productType' => $product->id,
            'fabricType' => $detail->id,
            'clothing_quantity' => 1,
            'fabric_quantity' => 1,
            'name' => 'John Doe',
            'address' => 'Jl. Merdeka No. 1',
            'phone' => '08123456789',
            'paymentMethod' => 'cod',
            'total_price' => 50000,
            'size' => 'M',
        ]);

        $response->assertJson(['status' => true]);
        $response->assertJsonStructure(['url']);

        $this->get($response->json('url'))->assertStatus(200);
    }

    public function test_order_generates_booking_code_starting_with_BK()
    {
        $pelanggan = User::factory()->create();
        $pelanggan->assignRole('pelanggan');
        $this->actingAs($pelanggan);

        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);
        $product = Product::factory()->create(['toko_id' => $toko->id]);
        $detail = ProductDetail::factory()->create(['toko_id' => $product->id]);

        $this->post(route('client.order.post', $toko), [
            'productType' => $product->id,
            'fabricType' => $detail->id,
            'clothing_quantity' => 1,
            'fabric_quantity' => 1,
            'name' => 'John Doe',
            'address' => 'Jl. Merdeka No. 1',
            'phone' => '08123456789',
            'paymentMethod' => 'cod',
            'total_price' => 50000,
            'size' => 'M',
        ]);

        $order = Order::first();
        $this->assertStringStartsWith('BK-', $order->kode_order);
    }

    public function test_order_default_status_is_menunggu_konfirmasi()
    {
        $pelanggan = User::factory()->create();
        $pelanggan->assignRole('pelanggan');
        $this->actingAs($pelanggan);

        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);
        $product = Product::factory()->create(['toko_id' => $toko->id]);
        $detail = ProductDetail::factory()->create(['toko_id' => $product->id]);

        $this->post(route('client.order.post', $toko), [
            'productType' => $product->id,
            'fabricType' => $detail->id,
            'clothing_quantity' => 1,
            'fabric_quantity' => 1,
            'name' => 'John Doe',
            'address' => 'Jl. Merdeka No. 1',
            'phone' => '08123456789',
            'paymentMethod' => 'cod',
            'total_price' => 50000,
            'size' => 'M',
        ]);

        $this->assertDatabaseHas('orders', ['status' => 'menunggu-konfirmasi']);
    }

    public function test_authenticated_user_can_view_order_history()
    {
        $pelanggan = User::factory()->create();
        $pelanggan->assignRole('pelanggan');
        $this->actingAs($pelanggan);

        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);
        $product = Product::factory()->create(['toko_id' => $toko->id]);
        $detail = ProductDetail::factory()->create(['toko_id' => $product->id]);

        Order::factory()->create([
            'pelanggan_id' => $pelanggan->id,
            'toko_id' => $toko->id,
            'product_id' => $product->id,
            'product_detail_id' => $detail->id,
        ]);

        $response = $this->get(route('client.history.order'));

        $response->assertStatus(200);
    }

    public function test_guest_can_view_track_order_form()
    {
        $response = $this->get(route('client.track.order'));

        $response->assertStatus(200);
    }

    public function test_guest_can_submit_invalid_booking_code()
    {
        $response = $this->post(route('client.track.order.post'), [
            'kode_order' => 'INVALID',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_user_can_cancel_order()
    {
        $pelanggan = User::factory()->create();
        $pelanggan->assignRole('pelanggan');
        $this->actingAs($pelanggan);

        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);
        $product = Product::factory()->create(['toko_id' => $toko->id]);
        $detail = ProductDetail::factory()->create(['toko_id' => $product->id]);

        $order = Order::factory()->create([
            'pelanggan_id' => $pelanggan->id,
            'toko_id' => $toko->id,
            'product_id' => $product->id,
            'product_detail_id' => $detail->id,
        ]);

        $this->post(route('client.cancel.order'), [
            'kode_order' => $order->kode_order,
        ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'batal',
        ]);
    }

    public function test_order_post_requires_all_fields()
    {
        $pelanggan = User::factory()->create();
        $pelanggan->assignRole('pelanggan');
        $this->actingAs($pelanggan);

        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);

        $response = $this->post(route('client.order.post', $toko), []);

        $response->assertSessionHasErrors(['productType', 'fabricType', 'name', 'address', 'phone', 'paymentMethod', 'total_price']);
    }

    public function test_guest_can_search_shops()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create([
            'penjahit_id' => $penjahit->id,
            'nama_toko' => 'Toko Spesial',
        ]);
        // Controller requires toko has at least 1 product and 1 detail
        $product = Product::factory()->create(['toko_id' => $toko->id]);
        ProductDetail::factory()->create(['toko_id' => $product->id]);

        $response = $this->get(route('client.belanja', ['search' => 'Spesial']));

        $response->assertStatus(200);
        $response->assertSee('Toko Spesial');
    }

    public function test_guest_can_track_order_with_valid_booking_code()
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

        $response = $this->post(route('client.track.order.post'), [
            'kode_order' => $order->kode_order,
        ]);

        $response->assertStatus(200);
        $response->assertSee($order->kode_order);
    }

    public function test_user_cannot_cancel_completed_order()
    {
        $pelanggan = User::factory()->create();
        $pelanggan->assignRole('pelanggan');
        $this->actingAs($pelanggan);

        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);
        $product = Product::factory()->create(['toko_id' => $toko->id]);
        $detail = ProductDetail::factory()->create(['toko_id' => $product->id]);

        $order = Order::factory()->create([
            'pelanggan_id' => $pelanggan->id,
            'toko_id' => $toko->id,
            'product_id' => $product->id,
            'product_detail_id' => $detail->id,
            'status' => 'selesai',
        ]);

        $this->post(route('client.cancel.order'), [
            'kode_order' => $order->kode_order,
        ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'selesai',
        ]);
    }
}
