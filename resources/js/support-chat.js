import axios from 'axios';

document.addEventListener('DOMContentLoaded', function () {
    const chatWindow = document.getElementById('chat-window');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const chatId = window.SUPPORT_CHAT ? window.SUPPORT_CHAT.chatId : null;

    function appendMessage(data) {
        const div = document.createElement('div');
        div.className = 'message ' + (data.is_bot ? 'bot' : (data.user && window.SUPPORT_CHAT.userId === data.user.id ? 'me' : 'them'));
        div.innerHTML = `<strong>${data.is_bot ? 'Bot' : (data.user ? data.user.name : 'User')}:</strong><div>${data.message}</div><small>${data.created_at}</small><hr />`;
        chatWindow.appendChild(div);
        chatWindow.scrollTop = chatWindow.scrollHeight;
    }

    let lastId = 0;
    // initialize lastId from existing messages
    const existing = chatWindow.querySelectorAll('.message');
    if (existing.length) {
        const last = existing[existing.length - 1];
        const small = last.querySelector('small');
        // not ideal to get id from DOM; backend will send new messages with id
    }

    if (window.Echo && chatId) {
        window.Echo.private('chat.' + chatId).listen('MessageSent', (e) => {
            appendMessage(e);
            lastId = e.id || lastId;
        });
    } else {
        // Polling fallback every 3 seconds
        const poll = () => {
            axios.get('/support-chat/messages', { params: { chat_id: chatId, after_id: lastId }})
                .then(res => {
                    const msgs = res.data.messages || [];
                    msgs.forEach(m => {
                        appendMessage(m);
                        lastId = m.id > lastId ? m.id : lastId;
                    });
                }).catch(err => {
                    console.error('Polling error', err);
                });
        };

        // set initial lastId by checking rendered messages' data-id attribute if present
        const nodes = chatWindow.querySelectorAll('[data-msg-id]');
        if (nodes.length) {
            lastId = parseInt(nodes[nodes.length - 1].getAttribute('data-msg-id')) || 0;
        }

        setInterval(poll, 3000);
    }

    if (chatForm) {
        chatForm.addEventListener('submit', function (ev) {
            ev.preventDefault();
            const msg = chatInput.value.trim();
            if (!msg) return;

            axios.post('/support-chat/message', {
                chat_id: chatId,
                message: msg
            }).then(res => {
                chatInput.value = '';
            }).catch(err => {
                console.error(err);
                alert('Gagal mengirim pesan');
            });
        });
    }
});
