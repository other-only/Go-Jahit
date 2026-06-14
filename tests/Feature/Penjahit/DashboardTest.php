<?php

namespace Tests\Feature\Penjahit;

use App\Models\Toko;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_penjahit_can_view_dashboard()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        Toko::factory()->create(['penjahit_id' => $penjahit->id]);

        $this->actingAs($penjahit);

        $response = $this->get(route('penjahit.dashboard'));
        $response->assertStatus(200);
    }

    public function test_penjahit_without_toko_sees_zero_counts()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');

        $this->actingAs($penjahit);

        $response = $this->get(route('penjahit.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('0');
    }
}
