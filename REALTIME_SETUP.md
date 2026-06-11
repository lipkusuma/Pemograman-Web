Real-time Websockets Setup (laravel-websockets)

What I added:
- `resources/js/bootstrap.js` now attempts to initialize Laravel Echo + Pusher for local websockets.
- `config/broadcasting.php` and `config/websockets.php` added.
- `package.json` updated to include `laravel-echo` and `pusher-js` dependencies.

Steps to enable full real-time locally:

1. Install PHP package (run in project root):

```
composer require beyondcode/laravel-websockets
```

2. Publish the package config and migrations:

```
php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider" --tag="migrations"
php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider" --tag="config"
php artisan migrate
```

3. Install frontend deps and build:

```
npm install
npm run dev
```

4. Update `.env` (example):

```
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=local
PUSHER_APP_KEY=local
PUSHER_APP_SECRET=local
PUSHER_APP_CLUSTER=mt1
PUSHER_HOST=127.0.0.1
PUSHER_PORT=6001
PUSHER_SCHEME=http
```

5. Run the websockets server:

```
php artisan websockets:serve
```

6. Ensure your frontend connects (it uses window.location hostname). You can optionally add a meta tag `<meta name="pusher-key" content="local">` in `resources/views/layouts/app.blade.php`.

Notes:
- If you prefer hosted Pusher, set PUSHER_APP_* to the credentials and leave `php artisan websockets:serve` out.
- If your app is behind HTTPS, set `PUSHER_APP_USE_TLS=true` and configure `wss` accordingly.

If you want, I can:
- Add the meta tag to the layout now.
- Add example supervisor/PM2 commands to run workers and websockets.
