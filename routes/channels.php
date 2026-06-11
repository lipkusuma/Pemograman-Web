<?php

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Support\Facades\Broadcast;
use App\Models\Chat;

Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    $chat = Chat::find($chatId);
    if (! $chat) return false;

    // allow if user is owner of chat or has admin flag
    return ($chat->user_id !== null && $user->id === $chat->user_id) || ($user->is_admin ?? false);
});

Broadcast::channel('support', function ($user) {
    return ($user->is_admin ?? false);
});
