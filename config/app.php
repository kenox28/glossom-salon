<?php
/**
 * Application-wide settings.
 */

define('APP_NAME', 'Glossom Salon');
define('APP_URL', 'http://localhost/glossom-salon/');
define('SESSION_TIMEOUT', 1800); // 30 minutes in seconds
define('CSRF_TOKEN_NAME', '_csrf_token');

// Timezone
date_default_timezone_set('Asia/Manila');
