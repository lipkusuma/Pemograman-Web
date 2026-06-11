<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Message;
use App\Events\MessageSent;

class AdminSupportController extends Controller
{
    public function index()
    {
        // list chats with last message and user info
        $chats = Chat::with(['user', 'messages' => function ($q) { $q->latest()->limit(1); }])->get()->map(function ($c) {
            $last = $c->messages()->with('user')->orderBy('created_at', 'desc')->first();
            return [
                'id' => $c->id,
                'user_id' => $c->user_id,
                'user_name' => $c->user ? $c->user->name : 'Guest #' . $c->id,
                'last_message' => $last ? $last->message : null,
                'last_at' => $last ? $last->created_at->toDateTimeString() : null,
                'message_count' => $c->messages()->count(),
            ];
        });

        return view('support.admin_index', compact('chats'));
    }

    public function notifications(Request $request)
    {
        $afterId = $request->query('after_id');

        $query = Message::where('is_bot', false)->whereNotNull('user_id');
        if ($afterId) $query->where('id', '>', $afterId);

        $messages = $query->with('chat')->orderBy('created_at')->get()->map(function ($m) {
            return [
                'id' => $m->id,
                'chat_id' => $m->chat_id,
                'user_id' => $m->user_id,
                'message' => $m->message,
                'created_at' => $m->created_at->toDateTimeString(),
            ];
        });

        return response()->json(['messages' => $messages]);
    }

    public function sseNotifications(Request $request)
    {
        // SSE stream for admin notifications (simple DB polling)
        $lastId = intval($request->query('last_id', 0));
        // allow long-running
        @set_time_limit(0);
        @ignore_user_abort(true);

        $response = response()->stream(function () use (&$lastId) {
            // send initial comment and flush to encourage client to open
            echo ": connected\n\n";
            @ob_flush();
            @flush();

            $heartbeat = 0;

            // run until client disconnects
            while (! connection_aborted() && connection_status() === CONNECTION_NORMAL) {
                $msgs = \App\Models\Message::where('is_bot', false)->whereNotNull('user_id')->where('id', '>', $lastId)->orderBy('id')->get();
                if ($msgs->isNotEmpty()) {
                    foreach ($msgs as $m) {
                        $lastId = $m->id;
                        $data = json_encode([
                            'id' => $m->id,
                            'chat_id' => $m->chat_id,
                            'user_id' => $m->user_id,
                            'message' => $m->message,
                            'created_at' => $m->created_at->toDateTimeString(),
                        ]);
                        echo "data: {$data}\n\n";
                        @ob_flush();
                        @flush();
                    }
                }

                // heartbeat every 10s to keep connection alive
                $heartbeat++;
                if ($heartbeat % 10 === 0) {
                    echo ": keepalive\n\n";
                    @ob_flush();
                    @flush();
                }

                // small sleep to avoid busy loop
                sleep(1);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);

        return $response;
    }

    public function showChat($id)
    {
        $chat = Chat::with('user')->findOrFail($id);
        $messages = $chat->messages()->with('user')->orderBy('created_at')->get();
        return view('support.admin_chat', compact('chat', 'messages'));
    }

    public function sendReply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $chat = Chat::findOrFail($id);
        $user = $request->user();

        $message = Message::create([
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'message' => $request->message,
            'is_bot' => false,
        ]);

        $message->load('user');

        event(new MessageSent($message));

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
}
