<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */
define('ALTERNATE_WP_CRON', true);

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'mydev');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'devils94');

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
define('AUTH_KEY',         '{~18F/Z~rX~+p-[T`tTxV#|t&KKHi,J[$g):<uI6A6&4Zf|:Q+LNp<qLQZX?8FTM');
define('SECURE_AUTH_KEY',  '@#nubQV_g]$t6J[]]qF.-KoKg:-_%~clO3DHF@q)ypU4kock6c4a?tR1$B<R/K)]');
define('LOGGED_IN_KEY',    'R=n{.xN-+K]!q6d<h%*-|(R^|A1sXRC;Wvhcm;}+At1i1n)hMeUZ6{/GZ[7lO-p!');
define('NONCE_KEY',        '{}sKcC]N/|+rr,VL^NM)sfoZEs%1*+&[0>HaB!S8,-;V*9Bn7]C*8LJN-^m<!U/s');
define('AUTH_SALT',        'A1eX#8Cx=s:%kS9oICt68:gciHM^r+w:Z~3%s)V]Qf^#/ HS:~;/<SNnY59rXCTN');
define('SECURE_AUTH_SALT', 'w<+kQcG~m ,CTk}xR#{0MnhIiWw]a{kF$;!lF7_LsbtlaAr0[BrbW$]fgGf6{i#m');
define('LOGGED_IN_SALT',   ')|d-?n)uI`e>G?%.X6yGQvY-[&;E-6yqS= BI6Gg#(ojqQAr?JF|sDUgRGq:bibm');
define('NONCE_SALT',       'Ox66d-r3.v[yi(0Okf*K:;N+k:n*~sYO>Zq=<`U~38k>m9]qz+[s%D_&Ik:#nZ/o');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'md_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
