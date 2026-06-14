<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();
        $user->assignRole('pelanggan');

        $this->actingAs($user);
        $response = $this->get('/logout');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_guest_logout_does_nothing(): void
    {
        $response = $this->get('/logout');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
