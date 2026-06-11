// Admin support notifications and badge
(function () {
    function getJson(url, params) {
        const qs = params ? ('?' + new URLSearchParams(params)) : '';
        return fetch(url + qs, { headers: { 'X-Requested-With': 'XMLHttpRequest' } }).then(r => r.json());
    }

    document.addEventListener('DOMContentLoaded', function () {
        const badge = document.getElementById('support-badge');
        const startBtn = document.getElementById('start-sse');
        let lastId = 0;
        let es = null;

        const poll = () => {
            getJson('/admin/support/notifications', { after_id: lastId }).then(res => {
                const msgs = res.messages || [];
                if (msgs.length) {
                    lastId = msgs[msgs.length - 1].id;
                    badge.textContent = msgs.length;
                    badge.style.display = 'inline-block';
                }
            }).catch(err => console.error(err));
        };

        function startSSE() {
            if (!!window.EventSource) {
                try {
                    let retries = 0;
                    const maxRetriesBeforePoll = 4;
                    const openStream = function () {
                        es = new EventSource('/admin/support/sse?last_id=' + lastId);
                        es.onopen = function () {
                            retries = 0;
                            console.log('SSE connected');
                        };
                        es.onmessage = function (ev) {
                            try {
                                // ignore comment/keepalive lines
                                if (!ev.data) return;
                                const payload = JSON.parse(ev.data);
                                lastId = payload.id > lastId ? payload.id : lastId;
                                if (badge) {
                                    badge.style.display = 'inline-block';
                                    badge.textContent = parseInt(badge.textContent || '0') + 1;
                                }
                            } catch (e) { console.error(e); }
                        };
                        es.onerror = function () {
                            retries++;
                            console.warn('SSE error, retry', retries);
                            if (es) { es.close(); es = null; }
                            if (retries >= maxRetriesBeforePoll) {
                                // fallback to polling
                                setInterval(poll, 3000);
                                poll();
                            } else {
                                // try reconnect after short delay
                                setTimeout(openStream, 3000);
                            }
                        };
                    };
                    openStream();
                } catch (e) {
                    setInterval(poll, 3000);
                    poll();
                }
            } else {
                setInterval(poll, 3000);
                poll();
            }
        }

        // If start button exists, only start SSE when clicked. Otherwise auto-start.
        if (startBtn) {
            startBtn.addEventListener('click', function () {
                startBtn.disabled = true;
                startBtn.textContent = 'Live: On';
                startSSE();
            });
        } else {
            startSSE();
        }
    });
})();
