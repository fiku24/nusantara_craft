<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'db_nusantara_craft' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'G};`2)Fp;x0)<lK@Hcp}KP(]S<9xD+<S&lJ=M=[?Q]j?|-Iknwd]mA^^5u{g;J/x' );
define( 'SECURE_AUTH_KEY',  'S2_IfHf6i.w(jcmiaV0V/c!Ej_s6t%A6r<]lo ER0MCK|6)@7US`^t_!LE7cW~Ms' );
define( 'LOGGED_IN_KEY',    'n07VT;^`8Ox;}(LLX3*-&0?zZ%O|CHb&@eu]3L<(Hz-2CB;}Po[LV9)V%gky;_4(' );
define( 'NONCE_KEY',        '-.tI]iy;6|!?e(Fvn=YJ{B{C2Pn^^2-vqP(>&$1y&J#eARp(BpNlemd56mlu*x~(' );
define( 'AUTH_SALT',        'Tz/g:`rr9.+}HiqYJRaPj*DY:yiHcJjQ/Jl/dcZNed5A]U>R tK9?dmLG/P6Mtj[' );
define( 'SECURE_AUTH_SALT', 'VGYKywLT_Yh&:AVq:gJP6B/8XNw34s:]8iUZXIFs]$xGah!X:ZM[I*#a,ZMm M8D' );
define( 'LOGGED_IN_SALT',   'Y+jW>=^Et=JJJT{XPP,9G{[%C%9byFAcm+lBDkslG^f#.elJe?>4,@GyLS3^/QWZ' );
define( 'NONCE_SALT',       '<p,mL]`;ulgLE` D6.$nIsMY%RzO_f{oqp2+(N,5Ttca.e~ef8:6Fe{n~~)vUs8F' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
