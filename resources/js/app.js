// Global custom JS only.
// NOTE: Do NOT import Bootstrap here (it loads on the Gentelella admin layout).
// Bootstrap for the login page lives in resources/js/login.js.
// Login page JS — Bootstrap 5 (includes Popper for dropdowns, tooltips, etc.).
// Loaded ONLY by the login layout, kept separate from the Gentelella dashboard.
import 'bootstrap';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';
