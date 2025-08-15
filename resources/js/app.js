import './bootstrap';

document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.Echo !== 'undefined') {
        window.Echo.connector.pusher.connection.bind('state_change', (states) => {
            console.log('Estado de conexión:', states);
        });

        window.Echo.connector.pusher.connection.bind('error', (err) => {
            console.error('Error Pusher:', err);
        });
    }
});