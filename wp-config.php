<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'j?Aee)^NyY<%)3U0-9JkR=YcOM`6weg+`*$971C@ v`&uiz/EL&Om@<Fa2=9a:G]' );
define( 'SECURE_AUTH_KEY',   'A+w2Z*|=Q=r<Mo>iiCE-q:oXb[C0!JND.5H69BaXIka$? $ s#VKsJ2uOygP$OX5' );
define( 'LOGGED_IN_KEY',     '>1rtG^2 O=2].HTSp]CHWjc2(8xIg~sz4s90X;xZOgQKK!0~Om!7Nv$$Swc$pno=' );
define( 'NONCE_KEY',         '_g}-^NM3`#d`S34_Ay]NTX?^%5stQX8JVU3$]d9d9`R8cfg88hN:1O!5ckJE(C2H' );
define( 'AUTH_SALT',         '!PJg4psJKguRK}8+: kVH@J%&4riib8[GA,YH7.KgYxmL614s)<_M]dVqb$;tIj?' );
define( 'SECURE_AUTH_SALT',  '9gK$9{=8hj_cW/%774-Ss HSL?{bo22BRh<UTL*dRPZ,UwE9>.*K%&vT nODbewr' );
define( 'LOGGED_IN_SALT',    'OXJUz%A&XB]9|qq;WcsWaKeSC;EI]w[RTfkER>C|eA6#dKXp40e#mV~v;J3xgtE!' );
define( 'NONCE_SALT',        '0B(%_1a/?1uv8hmhzRZ]c2(H0#0-m@)qz=gHQ0Ix;]iPHize{$R`Jvn0I@0r!xo{' );
define( 'WP_CACHE_KEY_SALT', '1XhXPRDWVEyKODR+b]v/)/a8|VB3tr)g)>TpJ~$W2=C/qfW0dop%0?R,=|~yL|NN' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
