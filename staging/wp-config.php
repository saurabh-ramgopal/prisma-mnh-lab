<?php
define( 'WP_CACHE', true );
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
define( 'DB_NAME', 'u537701131_vGf6d' );

/** Database username */
define( 'DB_USER', 'u537701131_k9jjb' );

/** Database password */
define( 'DB_PASSWORD', 'VHBN5AxNb6' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

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
define( 'AUTH_KEY',          '&mKi~J^[BOT>kza1nz@4YHTVXKpDeX`0jO|3-&M.tU496hGrR!|=`&dGa ^d]AF-' );
define( 'SECURE_AUTH_KEY',   'nC~B75VX==Yb,&g#nn],XxfJlYBMIiJznnjD(1p8kGiFVRd8?:z D=O6.2qTL3x=' );
define( 'LOGGED_IN_KEY',     'LzH.v5r*#h%OSDE:pom{2$CZ7Xx3V/If8L5_aS`;[!f9q##o[}5oN[Vao:%K,sJA' );
define( 'NONCE_KEY',         '04<hc}~zQn!m|I!ZWOAIeuCrcoE;yN7~k]SrR 6F9v{a/+}%ZC=%[6ctEoE4wCjf' );
define( 'AUTH_SALT',         'ZqJlD0qCZnh{C@p+oNx]W?)cD-*)p{5H-;UQ&-rZvFV;HKJN0<f/um5lvko]I JF' );
define( 'SECURE_AUTH_SALT',  'Z`=jCCn.K9*skN`^1L3Hvk$`k3YtRC{ [EWyZ?jX{sdfq;pweJd>I3a76T{#l2 ?' );
define( 'LOGGED_IN_SALT',    'q@T;@zr,J,EOM%RL>s:q=fQ~a %|A9_.;QpsR-HUVA+A-5B$c[{?*/WTR|6s&xjQ' );
define( 'NONCE_SALT',        'Y3HrFPO^EfiYO8;j>_9HkvTprTZ$O<KK>JEoR%.%4(5aO)E0k3xySmWP@NKSas{s' );
define( 'WP_CACHE_KEY_SALT', 'YM.:6}B9{Rm{8WU[VK>uzt`V]w|U*/1UDfTJiYwgGC.rwf^{.1:.Q(*O$O[.#4?&' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );


/* Add any custom values between this line and the "stop editing" line. */



define( 'FS_METHOD', 'direct' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
