import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: 'swulgjbrgylc7915nemd',
    wsHost: 'localhost',
    wsPort: 8080,
    wssPort: 443,
    forceTLS: false, // (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});
