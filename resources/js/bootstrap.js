import axios from 'axios';
import Echo from 'laravel-echo';
window.axios = axios;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: true,
    forceTLS: true, 
    wsHost: window.location.hostname,
    wsPort: 6001,
    disableStats: true
});