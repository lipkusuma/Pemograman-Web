<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Jobs\ProcessBotReply;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['chat_id', 'user_id', 'message', 'is_bot'];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    protected static function booted()
    {
        static::created(function ($message) {
            // Dispatch bot reply job for support chats when message is from the chat owner (not admin)
            if (! $message->is_bot && $message->chat && $message->chat->type === 'support' && $message->user_id === $message->chat->user_id) {
                // If queue driver is sync or no worker running, run immediately
                try {
                    if (config('queue.default') === 'sync') {
                        (new ProcessBotReply($message))->handle();
                    } else {
                        ProcessBotReply::dispatch($message)->delay(now()->addSeconds(1));
                    }
                } catch (\Throwable $e) {
                    // fallback to dispatch to queue
                    ProcessBotReply::dispatch($message)->delay(now()->addSeconds(1));
                }
            }
        });
    }
}
