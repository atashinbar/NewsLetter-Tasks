<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

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
define('DB_NAME', 'wp_nl2go');

/** MySQL database username */
define('DB_USER', 'wp_user');

/** MySQL database password */
define('DB_PASSWORD', 'wp_pass');

/** MySQL hostname */
define('DB_HOST', 'db');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'iF@W;y!_}XH!|L+ro)<teY8YC%^~H)g!93n)I;.kI>PUd$9DZt(gW1s$-~?a;G{a');
define('SECURE_AUTH_KEY',  '{,E<idj*~~hB9_O@edndBx@#vrT8^oq{yg=`qj#vd%B{3dl[9uBL-WR;V><(.Aae');
define('LOGGED_IN_KEY',    '{-v2x .kVoHz@YtuMuO=U]x#su:j.6wn6VhM<r]w/~:mz a2yS/:f{J%eE{cF/TF');
define('NONCE_KEY',        '9>NJvu-PX+H&~!*[.T$FRXIi&c#k8AdrN{8@~dLuwVOR&Q7K*&8c|dm=!DX p@!r');
define('AUTH_SALT',        'Y,qKuKmVw.bJDM0hs{E8}ewm1_w;4z#9wo~p.yIR+Sw_Ak6+5H/?!y?Jh!];rY.C');
define('SECURE_AUTH_SALT', '#&p@%7cR+;VUG()Np/p:T{u reON+T,hf:PwT-]Z!2ZLJn&YvG>et6k4hcG2]aBR');
define('LOGGED_IN_SALT',   '#X[(lL.h:pzfN><$:jbW;x:~]Ku{7K!+i{sU?C2-&@n()ko`d(fvBf$8l}O)0},9');
define('NONCE_SALT',       '8G]cn4*un.F1hvS-0ua-C~cst_:o.9aGv&C5cit/#)yOmeHV^CN4Bux*K2uehm9U');

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
