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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'testsite' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'Q4<)[Xr[;53=0wqb,^#CCYI=PqdR.DW6~Z#^SWFQer3y~V2W+>9.ynhm>L^inI/m' );
define( 'SECURE_AUTH_KEY',  '_ A*]0*G!s;v+6uVO0As.@!B7GYRl`9Zd(k)>m& Nu>@SD$Dvm~$vmaw@7Uv&G0x' );
define( 'LOGGED_IN_KEY',    'GfWzcJ!(SkBpRuN(vtOBSHZd#g5|JnfFAP7M}Z_L)tI8[*l>gx0+5[MZo>X%[(vj' );
define( 'NONCE_KEY',        'm:Cc]@?)=o2]()H~2Ku2cKt%_6,y*Ck#d.`5B5DxU6Hvzjh<3#`7_T2?gk5xYTlz' );
define( 'AUTH_SALT',        'e:Pvf|%{R`#g<cI<TrYn;+4YA6Zf&w$0up7YJ)])_5DLIR/7baJ1ifE}3@u9kl+R' );
define( 'SECURE_AUTH_SALT', 'V:q>FyGy/xcKH[ZFz!X#SMhG[kZuoZ@r>v(SqAG(q~7M9oI#2S{-qS#rpH%OX*-D' );
define( 'LOGGED_IN_SALT',   'RwPE0LYC$H=];JkXgr}NkmhUe,COZ?N2<3?2j [5*=2gZc:uOr/!M<U/LY}E<8kR' );
define( 'NONCE_SALT',       '@W|zDl,A@FY!gMQZ$ =wzz;a(ObRHdM|kn|<-x#`[eGW9M~K1n(iI<q>eQ{]I3dj' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
