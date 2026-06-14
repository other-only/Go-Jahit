<?php

namespace Tests\Feature\Authorization;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin()
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_penjahit()
    {
        $response = $this->get(route('penjahit.dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_client_chat()
    {
        $response = $this->get(route('client.chat.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_pelanggan_cannot_access_admin()
    {
        $pelanggan = User::factory()->create();
        $pelanggan->assignRole('pelanggan');

        $this->actingAs($pelanggan);

        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    public function test_pelanggan_cannot_access_penjahit()
    {
        $pelanggan = User::factory()->create();
        $pelanggan->assignRole('pelanggan');

        $this->actingAs($pelanggan);

        $response = $this->get(route('penjahit.dashboard'));
        $response->assertStatus(403);
    }

    public function test_penjahit_cannot_access_admin()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');

        $this->actingAs($penjahit);

        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    public function test_admin_cannot_access_penjahit()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin);

        $response = $this->get(route('penjahit.dashboard'));
        $response->assertStatus(403);
    }
}
