<?php

namespace Tests\Feature\Admin;

use App\Models\Product;
use App\Models\Toko;
use App\Models\User;
use Database\Factories\ProductFactory;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProdukManagementTest extends TestCase
{
    public function test_admin_can_list_products()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        ProductFactory::new()->create();

        $response = $this->get('/admin/seting/produk');

        $response->assertStatus(200);
    }

    public function test_admin_can_view_create_product_form()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        $response = $this->get('/admin/seting/produk/add');

        $response->assertStatus(200);
    }

    public function test_admin_can_view_edit_product_form()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        $product = ProductFactory::new()->create();

        $response = $this->get('/admin/seting/produk/edit/' . $product->id);

        $response->assertStatus(200);
    }

    public function test_admin_can_store_product_with_valid_data()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        // Admin needs a toko because store uses auth()->user()->toko->id
        Toko::factory()->create(['penjahit_id' => $admin->id]);
        $this->actingAs($admin);

        $response = $this->post('/admin/seting/produk/store', [
            'nama_produk' => 'Kemeja Formal',
            'deskripsi' => 'Kemeja formal bahan katun',
            'harga' => 150000,
            'foto' => UploadedFile::fake()->image('produk.jpg'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', ['nama_produk' => 'Kemeja Formal']);
    }

    public function test_admin_cannot_store_product_without_foto()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        $response = $this->post('/admin/seting/produk/store', [
            'nama_produk' => 'Kemeja',
            'deskripsi' => 'Deskripsi',
            'harga' => 100000,
        ]);

        $response->assertSessionHasErrors(['foto']);
    }

    public function test_admin_cannot_store_product_with_non_numeric_harga()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        $response = $this->post('/admin/seting/produk/store', [
            'nama_produk' => 'Kemeja',
            'deskripsi' => 'Deskripsi',
            'harga' => 'abc',
            'foto' => UploadedFile::fake()->image('produk.jpg'),
        ]);

        $response->assertSessionHasErrors(['harga']);
    }

    public function test_admin_can_update_product()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $toko = Toko::factory()->create(['penjahit_id' => $admin->id]);
        $product = Product::factory()->create(['toko_id' => $toko->id]);
        $this->actingAs($admin);

        $response = $this->post('/admin/seting/produk/update/' . $product->id, [
            'nama_produk' => 'Kemeja Updated',
            'deskripsi' => 'Deskripsi updated',
            'harga' => 200000,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'nama_produk' => 'Kemeja Updated',
        ]);
    }
}
