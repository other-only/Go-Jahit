<?php

namespace Tests\Feature\Penjahit;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_penjahit_can_view_chat_list()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);

        $customer = User::factory()->create();
        $customer->assignRole('pelanggan');

        Conversation::factory()->create([
            'penjahit_id' => $penjahit->id,
            'customer_id' => $customer->id,
        ]);

        $this->actingAs($penjahit);

        $response = $this->get(route('penjahit.chat.index'));
        $response->assertStatus(200);
    }

    public function test_penjahit_can_view_conversation()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);

        $customer = User::factory()->create();
        $customer->assignRole('pelanggan');

        $conversation = Conversation::factory()->create([
            'penjahit_id' => $penjahit->id,
            'customer_id' => $customer->id,
        ]);

        $this->actingAs($penjahit);

        $response = $this->get(route('penjahit.chat.show', $conversation));
        $response->assertStatus(200);
    }

    public function test_penjahit_can_send_message()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);

        $customer = User::factory()->create();
        $customer->assignRole('pelanggan');

        $conversation = Conversation::factory()->create([
            'penjahit_id' => $penjahit->id,
            'customer_id' => $customer->id,
        ]);

        $this->actingAs($penjahit);

        $response = $this->post(route('penjahit.chat.send', $conversation), [
            'message' => 'Halo, ada yang bisa dibantu?',
        ]);
        $response->assertStatus(302);
    }

    public function test_penjahit_cannot_access_other_conversation()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);

        $otherPenjahit = User::factory()->create();
        $otherPenjahit->assignRole('penjahit');
        $otherToko = Toko::factory()->create(['penjahit_id' => $otherPenjahit->id]);

        $customer = User::factory()->create();
        $customer->assignRole('pelanggan');

        $otherConversation = Conversation::factory()->create([
            'penjahit_id' => $otherPenjahit->id,
            'customer_id' => $customer->id,
        ]);

        $this->actingAs($penjahit);

        $response = $this->get(route('penjahit.chat.show', $otherConversation));
        $response->assertStatus(403);
    }

    public function test_penjahit_can_fetch_messages_ajax()
    {
        $penjahit = User::factory()->create();
        $penjahit->assignRole('penjahit');
        $toko = Toko::factory()->create(['penjahit_id' => $penjahit->id]);

        $customer = User::factory()->create();
        $customer->assignRole('pelanggan');

        $conversation = Conversation::factory()->create([
            'penjahit_id' => $penjahit->id,
            'customer_id' => $customer->id,
        ]);

        $message = Message::factory()->create([
            'conversation_id' => $conversation->id,
            'sender_id' => $customer->id,
            'message' => 'Halo, saya mau order',
        ]);

        $this->actingAs($penjahit);

        $response = $this->get(route('penjahit.chat.messages', [$conversation, 'after' => 0]));

        $response->assertStatus(200);
        $response->assertJsonStructure(['messages']);
    }
}
