import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
	window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

// Initialize Laravel Echo (Pusher) for local websockets if available
try {
	// import dynamically to avoid breaking if packages not installed yet
	const Echo = (await import('laravel-echo')).default;
	const Pusher = (await import('pusher-js')).default;
	window.Pusher = Pusher;

	const pushKeyMeta = document.head.querySelector('meta[name="pusher-key"]');
	const PUSHER_KEY = pushKeyMeta ? pushKeyMeta.content : (window.PUSHER_APP_KEY || 'local');

	window.Echo = new Echo({
		broadcaster: 'pusher',
		key: PUSHER_KEY,
		wsHost: window.location.hostname,
		wsPort: 6001,
		wssPort: 6001,
		forceTLS: false,
		disableStats: true,
		enabledTransports: ['ws', 'wss'],
		auth: {
			headers: {
				'X-CSRF-TOKEN': token ? token.content : ''
			}
		}
	});
} catch (e) {
	// packages might not be installed in this environment — ignore
}
