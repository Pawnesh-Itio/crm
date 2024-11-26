<?php

//defined('BASEPATH') or exit('No direct script access allowed');
/*
* --------------------------------------------------------------------------
* Base Site URL
* --------------------------------------------------------------------------
*
* URL to your CodeIgniter root. Typically this will be your base URL,
* WITH a trailing slash:
*
*   http://example.com/
*
* If this is not set then CodeIgniter will try guess the protocol, domain
* and path to your installation. However, you should always configure this
* explicitly and never rely on auto-guessing, especially in production
* environments.
*
*/
if(!defined('APP_BASE_URL')) define('APP_BASE_URL', 'http://localhost/crm/');

/*
* --------------------------------------------------------------------------
* Encryption Key
* IMPORTANT: Do not change this ever!
* --------------------------------------------------------------------------
*
* If you use the Encryption class, you must set an encryption key.
* See the user guide for more info.
*
* http://codeigniter.com/user_guide/libraries/encryption.html
*
* Auto added on install
*/
if(!defined('APP_ENC_KEY')) define('APP_ENC_KEY', '8d23c5dbcf07eb8c55f087ee49a1e349');

/**
 * Database Credentials
 * The hostname of your database server
 */
if(!defined('APP_DB_HOSTNAME')) define('APP_DB_HOSTNAME', 'localhost');

if (strpos($_SERVER["HTTP_HOST"], "localhost") !== false){
	/**
	 * The username used to connect to the database
	 */
	if(!defined('APP_DB_USERNAME')) define('APP_DB_USERNAME', 'root');
	
	/**
	 * The password used to connect to the database
	 */
	if(!defined('APP_DB_PASSWORD')) define('APP_DB_PASSWORD', '');
	
	/**
	 * The name of the database you want to connect to
	 */
	if(!defined('APP_DB_NAME')) define('APP_DB_NAME', 'it_crm_db');
}
else
{
	/**
	 * The username used to connect to the database
	 */
	if(!defined('APP_DB_USERNAME')) define('APP_DB_USERNAME', 'xeyso_it_crm_db_user');
	
	/**
	 * The password used to connect to the database
	 */
	if(!defined('APP_DB_PASSWORD')) define('APP_DB_PASSWORD', 'wIwMlgkIHayf');
	
	/**
	 * The name of the database you want to connect to
	 */
	if(!defined('APP_DB_NAME')) define('APP_DB_NAME', 'xeyso_it_crm_db');
}
$conn=mysqli_connect(APP_DB_HOSTNAME,APP_DB_USERNAME,APP_DB_PASSWORD,APP_DB_NAME);
/**
 * @since  2.3.0
 * Database charset
 */
if(!defined('APP_DB_CHARSET')) define('APP_DB_CHARSET', 'utf8mb4');

/**
 * @since  2.3.0
 * Database collation
 */
if(!defined('APP_DB_COLLATION')) define('APP_DB_COLLATION', 'utf8mb4_unicode_ci');

/**
 *
 * Session handler driver
 * By default the database driver will be used.
 *
 * For files session use this config:
 * define('SESS_DRIVER', 'files');
 * define('SESS_SAVE_PATH', NULL);
 * In case you are having problem with the SESS_SAVE_PATH consult with your hosting provider to set "session.save_path" value to php.ini
 *
 */
//define('SESS_DRIVER', 'database');
//define('SESS_SAVE_PATH', 'sessions');
//define('APP_SESSION_COOKIE_SAME_SITE', 'Lax');

/**
 * Enables CSRF Protection
 */
if(!defined('APP_CSRF_PROTECTION')) define('APP_CSRF_PROTECTION', true);