<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Detect if the app is running in a local or live environment
if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
    // Local environment
    define('APP_BASE_URL', 'http://localhost/live_crm');
    define('APP_DB_HOSTNAME', 'localhost');
    define('APP_DB_USERNAME', 'root');
    define('APP_DB_PASSWORD', '');
    define('APP_DB_NAME', 'it_crm_db');
} else {
    // Live environment
    define('APP_BASE_URL', 'https://xeyso.com/crm');
    define('APP_DB_HOSTNAME', 'localhost');
    define('APP_DB_USERNAME', 'xeyso_it_crm_db_user');
    define('APP_DB_PASSWORD', 'wIwMlgkIHayf');
    define('APP_DB_NAME', 'xeyso_it_crm_db');
}

/*
* --------------------------------------------------------------------------
* Encryption Key
* IMPORTANT: Do not change this ever!
* --------------------------------------------------------------------------
*/
define('APP_ENC_KEY', '7455bbb8733011a3d46f93921250e991');

/**
 * Database charset and collation
 */
define('APP_DB_CHARSET', 'utf8mb4');
define('APP_DB_COLLATION', 'utf8mb4_unicode_ci');

/**
 * Session handler and CSRF protection
 */
define('SESS_DRIVER', 'database');
define('SESS_SAVE_PATH', 'sessions');
define('APP_SESSION_COOKIE_SAME_SITE', 'Lax');
define('APP_CSRF_PROTECTION', true);
