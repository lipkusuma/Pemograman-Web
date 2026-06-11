@extends('layouts.app')

@section('title', 'Support Chat')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/chat.css') }}">
@endpush

@section('content')
<div class="sc-container" id="sc-container">
    {{-- Header --}}
    <div class="sc-header">
        <div class="sc-header-avatar">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H8l-5 3V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <span class="online-dot"></span>
        </div>
        <div class="sc-header-info">
            <h3>Support Team</h3>
            <span>Online — siap membantu Anda</span>
        </div>
        <div class="sc-header-actions">
            <button title="Refresh" onclick="location.reload()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
            </button>
        </div>
    </div>

    {{-- Messages --}}
    <div class="sc-messages" id="chat-window">
        @if($messages->isEmpty())
            <div class="sc-empty">
                <div class="sc-empty-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H8l-5 3V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>
                <h4>Mulai Percakapan</h4>
                <p>Kirim pesan pertama Anda dan tim support kami akan segera merespons!</p>
            </div>
        @endif

        @foreach($messages as $m)
            <div data-msg-id="{{ $m->id }}" class="sc-bubble {{ $m->is_bot ? 'bot' : (auth()->check() && $m->user && $m->user->id === auth()->id() ? 'me' : 'them') }}">
                <div class="sc-content">
                    @if($m->is_bot)
                        <span class="sc-bot-tag">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
                            Bot
                        </span>
                    @endif
                    {{ $m->message }}
                </div>
                <div class="sc-meta">
                    <span>{{ $m->is_bot ? 'Bot' : ($m->user ? $m->user->name : 'User') }}</span>
                    <span>•</span>
                    <span>{{ $m->created_at->format('H:i') }}</span>
                </div>
            </div>
        @endforeach

        {{-- Typing indicator --}}
        <div class="sc-typing" id="typing-indicator">
            <div class="sc-typing-inner">
                <div class="sc-typing-dot"></div>
                <div class="sc-typing-dot"></div>
                <div class="sc-typing-dot"></div>
            </div>
        </div>
    </div>

    {{-- Input --}}
    <form class="sc-input-area" id="chat-form" autocomplete="off">
        <input type="hidden" id="chat_id" value="{{ $chat->id }}" />
        <div class="sc-input-wrap">
            <input id="chat-input" type="text" placeholder="Ketik pesan Anda..." autofocus />
        </div>
        <button class="sc-send-btn" id="chat-send" type="submit" title="Kirim pesan">
            <svg viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    window.SUPPORT_CHAT = { chatId: {{ $chat->id }}, userId: {{ auth()->check() ? auth()->id() : 'null' }}, isAdmin: false };
</script>
<script src="{{ asset('assets/js/support-chat.js') }}"></script>
@endpush
