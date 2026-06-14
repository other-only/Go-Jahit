<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function test_guest_can_view_register_page(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_user_can_register_with_valid_data(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'alamat' => 'Jl. Test No. 1',
            'no_hp' => '08123456789',
            'terms' => '1',
        ]);

        $response->assertRedirect(route('client.belanja'));
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
        $this->assertAuthenticated();
    }

    public function test_registration_requires_unique_email(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'existing@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'alamat' => 'Jl. Test No. 1',
            'no_hp' => '08123456789',
            'terms' => '1',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_registration_requires_min_8_chars_password(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
            'alamat' => 'Jl. Test No. 1',
            'no_hp' => '08123456789',
            'terms' => '1',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_registration_requires_password_confirmation(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'different',
            'alamat' => 'Jl. Test No. 1',
            'no_hp' => '08123456789',
            'terms' => '1',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_registration_requires_terms(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'alamat' => 'Jl. Test No. 1',
            'no_hp' => '08123456789',
        ]);

        $response->assertSessionHasErrors(['terms']);
    }
}
