<?php

namespace Tests\Feature\Admin;

use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class DetailManagementTest extends TestCase
{
    public function test_admin_can_list_details()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        $response = $this->get('/admin/seting/detail');

        $response->assertStatus(200);
    }

    public function test_admin_can_view_create_detail_form()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        $response = $this->get('/admin/seting/detail/add');

        $response->assertStatus(200);
    }

    public function test_admin_can_store_detail_with_valid_data()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        // Admin needs a toko because store uses auth()->user()->toko->id
        $toko = Toko::factory()->create(['penjahit_id' => $admin->id]);
        // Note: product_details.toko_id FK references products.id
        $product = Product::factory()->create(['toko_id' => $toko->id]);
        $this->actingAs($admin);

        $response = $this->post('/admin/seting/detail/store', [
            'nama_detail' => 'Polyester',
            'deskripsi' => 'Bahan Polyester Premium',
            'harga' => 50000,
            'foto' => UploadedFile::fake()->image('detail.jpg'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('product_details', ['nama_detail' => 'Polyester']);
    }

    public function test_admin_can_update_detail()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $toko = Toko::factory()->create(['penjahit_id' => $admin->id]);
        $product = Product::factory()->create(['toko_id' => $toko->id]);
        $detail = ProductDetail::factory()->create(['toko_id' => $product->id]);
        $this->actingAs($admin);

        $response = $this->post('/admin/seting/detail/update/' . $detail->id, [
            'nama_detail' => 'Polyester Updated',
            'deskripsi' => 'Deskripsi updated',
            'harga' => 75000,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('product_details', [
            'id' => $detail->id,
            'nama_detail' => 'Polyester Updated',
        ]);
    }
}
