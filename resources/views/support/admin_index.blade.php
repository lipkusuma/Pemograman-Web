@extends('layouts.app')

@section('title', 'Admin Support')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/chat.css') }}">
@endpush

@section('content')
<div class="sc-admin-layout">
    {{-- Chat List Sidebar --}}
    <div class="sc-chat-list">
        <div class="sc-chat-list-header">
            <h3>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:6px;"><path d="M21 15a2 2 0 0 1-2 2H8l-5 3V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                Support Chats
            </h3>
            <button class="sc-live-badge" id="start-sse">
                <span class="dot"></span>
                <span class="label">Enable Live</span>
            </button>
        </div>
        <div class="sc-chat-items">
            @forelse($chats as $c)
                <a href="{{ route('admin.support.chat', $c['id']) }}" class="sc-chat-item">
                    <div class="sc-chat-item-avatar" style="background:linear-gradient(135deg,{{ ['#6366f1,#8b5cf6','#f97316,#ef4444','#2563eb,#38bdf8','#16a34a,#4ade80','#ec4899,#f43f5e'][($c['id'] - 1) % 5] }});">
                        {{ strtoupper(substr($c['user_name'], 0, 1)) }}
                    </div>
                    <div class="sc-chat-item-body">
                        <div class="sc-chat-item-name">
                            <span>{{ $c['user_name'] }}</span>
                            @if($c['last_at'])
                                <span class="time">{{ \Carbon\Carbon::parse($c['last_at'])->diffForHumans(null, true, true) }}</span>
                            @endif
                        </div>
                        <div class="sc-chat-item-preview">
                            <span>{{ $c['last_message'] ? \Illuminate\Support\Str::limit($c['last_message'], 40) : 'Belum ada pesan' }}</span>
                            @if($c['message_count'] > 0)
                                <span class="msg-count">{{ $c['message_count'] }}</span>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="sc-empty" style="padding:40px 24px;">
                    <div class="sc-empty-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H8l-5 3V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    </div>
                    <h4>Belum Ada Chat</h4>
                    <p>Belum ada percakapan support masuk.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Main Panel --}}
    <div class="sc-admin-main">
        <div class="sc-empty">
            <div class="sc-empty-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H8l-5 3V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            </div>
            <h4>Pilih Chat</h4>
            <p>Pilih percakapan dari daftar untuk melihat dan membalas pesan.</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/admin-support.js') }}"></script>
@endpush
