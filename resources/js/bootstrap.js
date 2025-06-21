import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.PusherConfig = {
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER
};

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: window.PusherConfig.key,
    cluster: window.PusherConfig.cluster,
    forceTLS: true,
    encrypted: true
});