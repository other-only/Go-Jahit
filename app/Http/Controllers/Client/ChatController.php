<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $conversations = Conversation::where('customer_id', Auth::id())
            ->with(['penjahit.toko', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        $unreadCount = auth()->user()->customerConversations()
            ->withCount(['messages' => function ($q) {
                $q->whereNull('read_at')->where('sender_id', '!=', Auth::id());
            }])->get()->sum('messages_count');

        return view('client.chat.index', compact('conversations', 'unreadCount'));
    }

    public function show(Conversation $conversation)
    {
        if ($conversation->customer_id !== Auth::id()) {
            abort(403);
        }

        // Mark messages from penjahit as read
        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', $conversation->penjahit_id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $conversation->load('messages.sender', 'penjahit.toko', 'order');
        $conversations = Conversation::where('customer_id', Auth::id())
            ->with(['penjahit.toko', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        return view('client.chat.show', compact('conversation', 'conversations'));
    }

    public function fetchMessages(Conversation $conversation, Request $request)
    {
        if ($conversation->customer_id !== Auth::id()) {
            abort(403);
        }

        $after = $request->get('after', 0);
        $messages = $conversation->messages()
            ->with('sender')
            ->where('id', '>', $after)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json(['messages' => $messages]);
    }

    public function send(Request $request, Conversation $conversation)
    {
        if ($conversation->customer_id !== Auth::id()) {
            abort(403);
        }

        $request->validate(['message' => 'required|string|max:5000']);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'message' => $request->message,
        ]);

        $conversation->update(['last_message_at' => now()]);

        return redirect()->back();
    }

    public function startGeneral(User $penjahit)
    {
        $conversation = Conversation::where('type', 'general')
            ->where('customer_id', Auth::id())
            ->where('penjahit_id', $penjahit->id)
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'type' => 'general',
                'customer_id' => Auth::id(),
                'penjahit_id' => $penjahit->id,
            ]);
        }

        return redirect()->route('client.chat.show', $conversation);
    }

    public function startOrder(Order $order)
    {
        if ($order->pelanggan_id !== Auth::id()) {
            abort(403);
        }

        $penjahitId = $order->toko->penjahit_id;

        $conversation = Conversation::where('type', 'order')
            ->where('order_id', $order->id)
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'type' => 'order',
                'order_id' => $order->id,
                'customer_id' => Auth::id(),
                'penjahit_id' => $penjahitId,
            ]);
        }

        return redirect()->route('client.chat.show', $conversation);
    }
}
