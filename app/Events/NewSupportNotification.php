<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;

class NewSupportNotification implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $chat_id;
    public $message;

    public function __construct(Message $message)
    {
        $this->chat_id = $message->chat_id;
        $this->message = [
            'id' => $message->id,
            'user_id' => $message->user_id,
            'message' => $message->message,
            'created_at' => $message->created_at->toDateTimeString(),
        ];
    }

    public function broadcastOn()
    {
        return new PrivateChannel('support');
    }

    public function broadcastWith()
    {
        return ['chat_id' => $this->chat_id, 'message' => $this->message];
    }
}
