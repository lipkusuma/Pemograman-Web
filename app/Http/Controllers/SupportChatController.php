<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Message;
use App\Events\MessageSent;
use App\Events\NewSupportNotification;

class SupportChatController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // find or create a support chat for this user
        $chat = Chat::firstOrCreate([
            'user_id' => $user->id ?? null,
            'type' => 'support',
        ]);

        $messages = $chat->messages()->with('user')->orderBy('created_at')->get();

        return view('support.chat', compact('chat', 'messages'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'message' => 'required|string',
        ]);

        $user = $request->user();

        $message = Message::create([
            'chat_id' => $request->chat_id,
            'user_id' => $user->id ?? null,
            'message' => $request->message,
            'is_bot' => false,
        ]);

        $message->load('user');

        event(new MessageSent($message));
        // notify admins about incoming user message
        event(new NewSupportNotification($message));

        return response()->json([
            'ok' => true,
            'message' => [
                'id' => $message->id,
                'chat_id' => $message->chat_id,
                'user' => $message->user ? ['id' => $message->user->id, 'name' => $message->user->name] : null,
                'message' => $message->message,
                'is_bot' => false,
                'created_at' => $message->created_at->toDateTimeString(),
            ],
        ]);
    }

    public function messages(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'after_id' => 'nullable|integer',
        ]);

        $after = $request->input('after_id');

        $query = Message::where('chat_id', $request->chat_id)->with('user')->orderBy('created_at');
        if ($after) $query->where('id', '>', $after);

        $messages = $query->get()->map(function ($m) {
            return [
                'id' => $m->id,
                'chat_id' => $m->chat_id,
                'user' => $m->user ? ['id' => $m->user->id, 'name' => $m->user->name] : null,
                'message' => $m->message,
                'is_bot' => (bool) $m->is_bot,
                'created_at' => $m->created_at->toDateTimeString(),
            ];
        });

        return response()->json(['messages' => $messages]);
    }
}
