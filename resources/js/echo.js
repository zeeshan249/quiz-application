import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
});

// Log when Echo is initialized
console.log('🎉 Echo initialized with Reverb');
console.log('Reverb Host:', import.meta.env.VITE_REVERB_HOST);
console.log('Reverb Port:', import.meta.env.VITE_REVERB_PORT);