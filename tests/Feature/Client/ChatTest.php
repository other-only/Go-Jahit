<?php

namespace Tests\Feature\Client;

use App\Models\Conversation;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_pelanggan_can_view_chat_list()
    {
        $pelanggan = User::factory()->create();
        $pelanggan->assignRole('pelanggan');
        $this->actingAs($pelanggan);

        $response = $this->get(route('client.chat.index'));

        $response->assertStatus(200);
    }

    public function test_pelanggan_can_start_general_chat()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');

        $pelanggan = User::factory()->create();
        $pelanggan->assignRole('pelanggan');
        $this->actingAs($pelanggan);

        Toko::factory()->create(['penjahit_id' => $penjahit->id]);

        $response = $this->get(route('client.chat.start', $penjahit));

        $response->assertRedirect();
    }

    public function test_pelanggan_can_send_message()
    {
        $pelanggan = User::factory()->create();
        $pelanggan->assignRole('pelanggan');
        $this->actingAs($pelanggan);

        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');

        Toko::factory()->create(['penjahit_id' => $penjahit->id]);

        $conversation = Conversation::factory()->create([
            'customer_id' => $pelanggan->id,
            'penjahit_id' => $penjahit->id,
        ]);

        $response = $this->post(route('client.chat.send', $conversation), [
            'message' => 'Halo',
        ]);

        $response->assertRedirect();
    }

    public function test_guest_cannot_access_chat()
    {
        $response = $this->get(route('client.chat.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_pelanggan_cannot_access_other_conversation()
    {
        $pelanggan = User::factory()->create();
        $pelanggan->assignRole('pelanggan');

        $otherPelanggan = User::factory()->create();
        $otherPelanggan->assignRole('pelanggan');
        $this->actingAs($otherPelanggan);

        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');

        Toko::factory()->create(['penjahit_id' => $penjahit->id]);

        $conversation = Conversation::factory()->create([
            'customer_id' => $pelanggan->id,
            'penjahit_id' => $penjahit->id,
        ]);

        $response = $this->get(route('client.chat.show', $conversation));

        $response->assertStatus(403);
    }

    public function test_pelanggan_can_fetch_messages_ajax()
    {
        $pelanggan = User::factory()->create();
        $pelanggan->assignRole('pelanggan');
        $this->actingAs($pelanggan);

        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        Toko::factory()->create(['penjahit_id' => $penjahit->id]);

        $conversation = Conversation::factory()->create([
            'customer_id' => $pelanggan->id,
            'penjahit_id' => $penjahit->id,
        ]);

        $message = \App\Models\Message::factory()->create([
            'conversation_id' => $conversation->id,
            'sender_id' => $penjahit->id,
            'message' => 'Halo, ada yang bisa dibantu?',
        ]);

        $response = $this->get(route('client.chat.messages', [$conversation, 'after' => 0]));

        $response->assertStatus(200);
        $response->assertJsonStructure(['messages']);
    }

    public function test_pelanggan_can_start_order_chat()
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

        $response = $this->get(route('client.chat.order', $order));

        $response->assertRedirect();
    }
}
