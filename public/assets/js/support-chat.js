// Support Chat (vanilla JS) — handles both user & admin chat views
(function () {
    'use strict';

    function getCsrf() {
        const m = document.querySelector('meta[name="csrf-token"]');
        return m ? m.content : '';
    }

    function postJson(url, data) {
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrf(),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        }).then(r => r.json());
    }

    function getJson(url, params) {
        const qs = params ? ('?' + new URLSearchParams(params)) : '';
        return fetch(url + qs, { headers: { 'X-Requested-With': 'XMLHttpRequest' } }).then(r => r.json());
    }

    function formatTime(dateStr) {
        try {
            const d = new Date(dateStr);
            return d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        } catch (e) {
            return dateStr;
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const chatWindow = document.getElementById('chat-window');
        const chatForm = document.getElementById('chat-form');
        const chatInput = document.getElementById('chat-input');
        const typingIndicator = document.getElementById('typing-indicator');
        const cfg = window.SUPPORT_CHAT || {};
        const chatId = cfg.chatId || null;
        const userId = cfg.userId || null;
        const isAdmin = cfg.isAdmin || false;
        const replyUrl = cfg.replyUrl || null;

        if (!chatWindow || !chatId) return;

        // Track known message IDs to prevent duplicates
        const knownIds = new Set();
        chatWindow.querySelectorAll('[data-msg-id]').forEach(function (el) {
            const id = parseInt(el.getAttribute('data-msg-id'));
            if (id) knownIds.add(id);
        });

        // Scroll to bottom on load
        function scrollToBottom(smooth) {
            if (smooth) {
                chatWindow.scrollTo({ top: chatWindow.scrollHeight, behavior: 'smooth' });
            } else {
                chatWindow.scrollTop = chatWindow.scrollHeight;
            }
        }
        scrollToBottom(false);

        // Remove empty state if present
        function removeEmptyState() {
            const empty = chatWindow.querySelector('.sc-empty');
            if (empty) empty.remove();
        }

        // Create a bubble element from message data
        function createBubble(data) {
            const div = document.createElement('div');
            const isMine = data.user && userId && data.user.id === userId;
            const cls = data.is_bot ? 'bot' : (isMine ? 'me' : 'them');
            div.className = 'sc-bubble ' + cls;
            div.setAttribute('data-msg-id', data.id || '0');

            let botTag = '';
            if (data.is_bot) {
                botTag = '<span class="sc-bot-tag"><svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg> Bot</span> ';
            }

            const senderName = data.is_bot ? 'Bot' : (data.user ? data.user.name : 'User');
            const time = formatTime(data.created_at);

            div.innerHTML =
                '<div class="sc-content">' + botTag + escapeHtml(data.message) + '</div>' +
                '<div class="sc-meta"><span>' + escapeHtml(senderName) + '</span><span>•</span><span>' + time + '</span></div>';

            return div;
        }

        function escapeHtml(str) {
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }

        // Append a message, skipping duplicates
        function appendMessage(data) {
            const msgId = data.id;
            if (msgId && knownIds.has(msgId)) return; // skip duplicate
            if (msgId) knownIds.add(msgId);

            removeEmptyState();
            const bubble = createBubble(data);

            // Insert before typing indicator
            if (typingIndicator) {
                chatWindow.insertBefore(bubble, typingIndicator);
            } else {
                chatWindow.appendChild(bubble);
            }
            scrollToBottom(true);
        }

        // Show/hide typing indicator
        function showTyping() {
            if (typingIndicator) {
                typingIndicator.classList.add('active');
                scrollToBottom(true);
            }
        }

        function hideTyping() {
            if (typingIndicator) {
                typingIndicator.classList.remove('active');
            }
        }

        // Determine the last message ID for polling
        let lastId = 0;
        const nodes = chatWindow.querySelectorAll('[data-msg-id]');
        if (nodes.length) lastId = parseInt(nodes[nodes.length - 1].getAttribute('data-msg-id')) || 0;

        // ── Polling fallback (always used since Echo/Pusher might not be configured) ──
        if (window.Echo && chatId) {
            window.Echo.private('chat.' + chatId).listen('MessageSent', function (e) {
                appendMessage(e);
                lastId = e.id > lastId ? e.id : lastId;
                hideTyping();
            });
        } else {
            var poll = function () {
                getJson('/support-chat/messages', { chat_id: chatId, after_id: lastId }).then(function (res) {
                    var msgs = res.messages || [];
                    msgs.forEach(function (m) {
                        appendMessage(m);
                        lastId = m.id > lastId ? m.id : lastId;
                    });
                    // Hide typing if we got a bot message
                    if (msgs.some(function (m) { return m.is_bot; })) {
                        hideTyping();
                    }
                }).catch(function (err) { console.error(err); });
            };
            setInterval(poll, 3000);
        }

        // ── Send message ──
        if (chatForm) {
            chatForm.addEventListener('submit', function (ev) {
                ev.preventDefault();
                var msg = chatInput.value.trim();
                if (!msg) return;

                // Determine the URL to post to
                var url = isAdmin && replyUrl ? replyUrl : '/support-chat/message';
                var body = isAdmin && replyUrl ? { message: msg } : { chat_id: chatId, message: msg };

                chatInput.value = '';
                chatInput.focus();

                postJson(url, body).then(function (res) {
                    if (res.ok && res.message) {
                        // Optimistic UI: show sent message immediately
                        appendMessage(res.message);
                        lastId = res.message.id > lastId ? res.message.id : lastId;

                        // Show typing indicator for user chat (bot will reply)
                        if (!isAdmin) {
                            showTyping();
                        }
                    }
                }).catch(function (err) {
                    console.error(err);
                    // Restore message on failure
                    chatInput.value = msg;
                });
            });
        }
    });
})();
