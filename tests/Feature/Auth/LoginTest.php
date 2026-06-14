<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_guest_can_view_login_page(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_pelanggan_can_login_and_redirect_to_belanja(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);
        $user->assignRole('pelanggan');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('client.belanja'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_penjahit_can_login_and_redirect_to_dashboard(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);
        $user->assignRole('penjahit');
        $user->toko()->create([
            'nama_toko' => 'Test Toko',
            'deskripsi' => 'Test description',
            'alamat' => 'Jl. Test No. 1',
            'latitude' => -6.2,
            'longitude' => 106.8,
            'logo' => 'logo-default.png',
            'bank' => 'bca',
            'no_rekening' => '1234567890',
            'atas_nama' => 'Test',
            'no_wa' => '08123456789',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('penjahit.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_can_login_and_redirect_to_admin_dashboard(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);
        $user->assignRole('admin');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_wrong_password(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_user_cannot_login_with_nonexistent_email(): void
    {
        $response = $this->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_login_requires_email(): void
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_login_requires_password(): void
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password']);
    }
}
