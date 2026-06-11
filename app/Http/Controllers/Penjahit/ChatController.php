<?php

namespace App\Http\Controllers\Penjahit;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $conversations = Conversation::where('penjahit_id', Auth::id())
            ->with(['customer', 'latestMessage', 'order'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        $unreadCount = auth()->user()->penjahitConversations()
            ->withCount(['messages' => function ($q) {
                $q->whereNull('read_at')->where('sender_id', '!=', Auth::id());
            }])->get()->sum('messages_count');

        return view('penjahit.chat.index', compact('conversations', 'unreadCount'));
    }

    public function show(Conversation $conversation)
    {
        if ($conversation->penjahit_id !== Auth::id()) {
            abort(403);
        }

        // Mark messages from customer as read
        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', $conversation->customer_id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $conversation->load('messages.sender', 'customer', 'order');
        $conversations = Conversation::where('penjahit_id', Auth::id())
            ->with(['customer', 'latestMessage', 'order'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        return view('penjahit.chat.show', compact('conversation', 'conversations'));
    }

    public function fetchMessages(Conversation $conversation, Request $request)
    {
        if ($conversation->penjahit_id !== Auth::id()) {
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
        if ($conversation->penjahit_id !== Auth::id()) {
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
}
