<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Http;

class ProcessBotReply implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function handle()
    {
        $text = strtolower($this->message->message);

        $reply = null;

        // If OpenAI key is configured, call API
        $key = env('OPENAI_API_KEY');
        if ($key) {
            try {
                $res = Http::withToken($key)->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a helpful e-commerce support assistant. Keep answers short.'],
                        ['role' => 'user', 'content' => $this->message->message],
                    ],
                    'max_tokens' => 200,
                ]);

                if ($res->ok()) {
                    $json = $res->json();
                    $reply = $json['choices'][0]['message']['content'] ?? null;
                }
            } catch (\Exception $e) {
                // ignore and fallback
            }
        }

        // Fallback rule-based replies
        if (! $reply) {
            if (str_contains($text, 'halo') || str_contains($text, 'hai') || str_contains($text, 'halo')) {
                $reply = 'Halo! Ada yang bisa kami bantu? (Ketik pertanyaan atau "produk" untuk rekomendasi)';
            } elseif (str_contains($text, 'produk')) {
                $reply = 'Silakan sebutkan kategori atau nama produk, saya bantu rekomendasi.';
            } elseif (str_contains($text, 'harga') || str_contains($text, 'berapa')) {
                $reply = 'Untuk harga, mohon sebutkan produk yang dimaksud atau cek halaman produk terkait.';
            } else {
                $reply = 'Terima kasih pesan Anda. Tim support akan membalas secepatnya.';
            }
        }

        // Create bot message
        $botMessage = Message::create([
            'chat_id' => $this->message->chat_id,
            'user_id' => null,
            'message' => $reply,
            'is_bot' => true,
        ]);

        event(new MessageSent($botMessage));
    }
}
