<?php

namespace Tests\Feature\Client;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\Rating;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

    public function test_order_generates_booking_code_starting_with_bk()
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

    public function test_transfer_order_requires_payment_proof()
    {
        $pelanggan = User::factory()->create();
        $pelanggan->assignRole('pelanggan');
        $this->actingAs($pelanggan);

        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);
        $product = Product::factory()->create(['toko_id' => $toko->id]);
        $detail = ProductDetail::factory()->create(['toko_id' => $toko->id]);

        $response = $this->postJson(route('client.order.post', $toko), [
            'productType' => $product->id,
            'fabricType' => $detail->id,
            'clothing_quantity' => 1,
            'fabric_quantity' => 1,
            'name' => 'John Doe',
            'address' => 'Jl. Merdeka No. 1',
            'phone' => '08123456789',
            'paymentMethod' => 'transfer',
            'total_price' => 50000,
            'size' => 'M',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['bukti_transfer']);
    }

    public function test_transfer_order_uploads_payment_proof()
    {
        Storage::fake('public');

        $pelanggan = User::factory()->create();
        $pelanggan->assignRole('pelanggan');
        $this->actingAs($pelanggan);

        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);
        $product = Product::factory()->create(['toko_id' => $toko->id]);
        $detail = ProductDetail::factory()->create(['toko_id' => $toko->id]);

        $response = $this->postJson(route('client.order.post', $toko), [
            'productType' => $product->id,
            'fabricType' => $detail->id,
            'clothing_quantity' => 1,
            'fabric_quantity' => 1,
            'name' => 'John Doe',
            'address' => 'Jl. Merdeka No. 1',
            'phone' => '08123456789',
            'paymentMethod' => 'transfer',
            'bukti_transfer' => UploadedFile::fake()->image('bukti.jpg'),
            'total_price' => 50000,
            'size' => 'M',
        ]);

        $response->assertJson(['status' => true]);
        $order = Order::first();
        $this->assertNotNull($order->bukti_pembayaran);
        Storage::disk('public')->assertExists('bukti_pembayaran/'.$order->bukti_pembayaran);
    }

    public function test_guest_order_post_returns_login_message()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);

        $response = $this->postJson(route('client.order.post', $toko), []);

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Silakan login terlebih dahulu untuk membuat pesanan.']);
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

    public function test_customer_can_rate_own_completed_order()
    {
        $pelanggan = User::factory()->create();
        $pelanggan->assignRole('pelanggan');
        $this->actingAs($pelanggan);

        $penjahit = User::factory()->create();
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);
        $product = Product::factory()->create(['toko_id' => $toko->id]);
        $detail = ProductDetail::factory()->create(['toko_id' => $toko->id]);
        $order = Order::factory()->create([
            'pelanggan_id' => $pelanggan->id,
            'toko_id' => $toko->id,
            'product_id' => $product->id,
            'product_detail_id' => $detail->id,
            'status' => 'selesai',
        ]);

        $response = $this->post(route('client.order.rating.store', $order), [
            'rating' => 5,
            'ulasan' => 'Jahitannya rapi dan sesuai pesanan.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('ratings', [
            'order_id' => $order->id,
            'toko_id' => $toko->id,
            'pelanggan_id' => $pelanggan->id,
            'rating' => 5,
        ]);
    }

    public function test_customer_cannot_rate_order_that_is_not_completed()
    {
        $pelanggan = User::factory()->create();
        $pelanggan->assignRole('pelanggan');
        $this->actingAs($pelanggan);
        $order = Order::factory()->create(['pelanggan_id' => $pelanggan->id, 'status' => 'dalam-proses']);

        $this->post(route('client.order.rating.store', $order), ['rating' => 5])
            ->assertForbidden();

        $this->assertDatabaseCount('ratings', 0);
    }

    public function test_customer_cannot_rate_another_customers_order()
    {
        $pelanggan = User::factory()->create();
        $pelanggan->assignRole('pelanggan');
        $this->actingAs($pelanggan);
        $order = Order::factory()->create(['status' => 'selesai']);

        $this->post(route('client.order.rating.store', $order), ['rating' => 5])
            ->assertForbidden();

        $this->assertDatabaseCount('ratings', 0);
    }

    public function test_customer_cannot_rate_an_order_twice()
    {
        $pelanggan = User::factory()->create();
        $pelanggan->assignRole('pelanggan');
        $this->actingAs($pelanggan);
        $order = Order::factory()->create(['pelanggan_id' => $pelanggan->id, 'status' => 'selesai']);
        Rating::create([
            'order_id' => $order->id,
            'toko_id' => $order->toko_id,
            'pelanggan_id' => $pelanggan->id,
            'rating' => 4,
        ]);

        $this->from(route('client.history.order'))
            ->post(route('client.order.rating.store', $order), ['rating' => 5])
            ->assertRedirect(route('client.history.order'))
            ->assertSessionHasErrors('rating');

        $this->assertDatabaseCount('ratings', 1);
    }

    public function test_shop_list_displays_average_rating_and_number_of_reviews()
    {
        $toko = Toko::factory()->create();
        Product::factory()->create(['toko_id' => $toko->id]);
        ProductDetail::factory()->create(['toko_id' => $toko->id]);
        $firstOrder = Order::factory()->create(['toko_id' => $toko->id]);
        $secondOrder = Order::factory()->create(['toko_id' => $toko->id]);
        Rating::create([
            'order_id' => $firstOrder->id,
            'toko_id' => $toko->id,
            'pelanggan_id' => $firstOrder->pelanggan_id,
            'rating' => 4,
        ]);
        Rating::create([
            'order_id' => $secondOrder->id,
            'toko_id' => $toko->id,
            'pelanggan_id' => $secondOrder->pelanggan_id,
            'rating' => 5,
        ]);

        $this->get(route('client.belanja'))
            ->assertOk()
            ->assertSee('4.5')
            ->assertSee('(2 ulasan)');
    }
}
