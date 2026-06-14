<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    public function test_admin_can_view_dashboard()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        $response = $this->get('/admin/dashboard');

        $response->assertStatus(200);
    }
}
