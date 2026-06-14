<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Factories\OrderFactory;
use Database\Factories\ProductDetailFactory;
use Database\Factories\ProductFactory;
use Database\Factories\TokoFactory;
use Tests\TestCase;

class OrderManagementTest extends TestCase
{
    public function test_admin_can_list_orders()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $pelanggan = User::factory()->create();
        $pelanggan->assignRole('pelanggan');
        $toko = TokoFactory::new()->create(['penjahit_id' => $penjahit->id]);
        $product = ProductFactory::new()->create(['toko_id' => $toko->id]);
        $detail = ProductDetailFactory::new()->create(['toko_id' => $product->id]);
        OrderFactory::new()->create([
            'toko_id' => $toko->id,
            'product_id' => $product->id,
            'product_detail_id' => $detail->id,
            'pelanggan_id' => $pelanggan->id,
        ]);

        $response = $this->get('/admin/order');

        $response->assertStatus(200);
    }

    public function test_admin_can_view_order_detail()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $pelanggan = User::factory()->create();
        $pelanggan->assignRole('pelanggan');
        $toko = TokoFactory::new()->create(['penjahit_id' => $penjahit->id]);
        $product = ProductFactory::new()->create(['toko_id' => $toko->id]);
        $detail = ProductDetailFactory::new()->create(['toko_id' => $product->id]);
        $order = OrderFactory::new()->create([
            'toko_id' => $toko->id,
            'product_id' => $product->id,
            'product_detail_id' => $detail->id,
            'pelanggan_id' => $pelanggan->id,
        ]);

        $response = $this->get('/admin/order/detail/' . $order->id);

        $response->assertStatus(200);
    }

    public function test_admin_can_update_order_status()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $pelanggan = User::factory()->create();
        $pelanggan->assignRole('pelanggan');
        $toko = TokoFactory::new()->create(['penjahit_id' => $penjahit->id]);
        $product = ProductFactory::new()->create(['toko_id' => $toko->id]);
        $detail = ProductDetailFactory::new()->create(['toko_id' => $product->id]);
        $order = OrderFactory::new()->create([
            'toko_id' => $toko->id,
            'product_id' => $product->id,
            'product_detail_id' => $detail->id,
            'pelanggan_id' => $pelanggan->id,
            'status' => 'dalam-proses',
        ]);

        $response = $this->post('/admin/order/update/' . $order->id . '/status', [
            'status' => 'selesai',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'selesai',
        ]);
    }

    public function test_admin_can_confirm_order()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $pelanggan = User::factory()->create();
        $pelanggan->assignRole('pelanggan');
        $toko = TokoFactory::new()->create(['penjahit_id' => $penjahit->id]);
        $product = ProductFactory::new()->create(['toko_id' => $toko->id]);
        $detail = ProductDetailFactory::new()->create(['toko_id' => $product->id]);
        $order = OrderFactory::new()->create([
            'toko_id' => $toko->id,
            'product_id' => $product->id,
            'product_detail_id' => $detail->id,
            'pelanggan_id' => $pelanggan->id,
            'status' => 'menunggu-konfirmasi',
        ]);

        $response = $this->post('/admin/order/update/' . $order->id . '/confirm');

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'selesai',
        ]);
    }
}
