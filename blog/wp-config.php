<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'uptaylor_wp843');

/** MySQL database username */
define('DB_USER', 'uptaylor_wp843');

/** MySQL database password */
define('DB_PASSWORD', '8PN6S][LP1');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '4wmba3blpa1bgoacy1caea7xffubbkoboce7tuhsk6v2mhbfippb1i796l6bwhel');
define('SECURE_AUTH_KEY',  'otap9ko9cmerx5rtl7tz0k7xxjfx1fqgpxi2mgesgjrmbfqbacatr0symtr4ncfn');
define('LOGGED_IN_KEY',    'v3xzdn0luzkxo6wnako7ad7xyme71fq3y0sd9vilymp4rklxguliv2dubqdibnox');
define('NONCE_KEY',        'jsr3gxkkhgapk9u6c4kphwycttzd76bunclpiqkftrmyjyazat5ndawunqbqltdn');
define('AUTH_SALT',        'vzk3jle8ze0y2gobtnrlpk2ienchuvdjau20mis87l9aqmen2zfqji4nw2rsdyiq');
define('SECURE_AUTH_SALT', 'xjv4q4mhbr7xbn4dnneaj7mfee24xa4i4ymwwztygska3cmcnnc0k9h8pemaaohd');
define('LOGGED_IN_SALT',   'i8nqt8mmemffggmc6ymhjajag4lqresildkdk5beonbjsasaspfn1fsfoduw56uj');
define('NONCE_SALT',       'tbzwsxpbgxoygtpftugiqgnxkde9y2vrrhoy4s9sqdflpo2uglquz95iacss0r1u');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
