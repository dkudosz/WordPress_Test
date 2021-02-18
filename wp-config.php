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
define( 'DB_NAME', 'wp_test' );

/** MySQL database username */
define( 'DB_USER', 'tester' );

/** MySQL database password */
define( 'DB_PASSWORD', 'tester' );

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
define( 'AUTH_KEY',         'HLR6lcC|WP2hrhw6;uDZ~+Hi2:cf,eMd,,/{<XI]v75hFO =FwY3%:)ZXCV4t{P#' );
define( 'SECURE_AUTH_KEY',  'F]mp[M05Sd~U8nxSpKXL8kP>58^j:JIbk~`@LfwU9bQI?gFWmop.ZU*$6|fz(+3w' );
define( 'LOGGED_IN_KEY',    '4gZ/:66rjaof2zPFLN~C~60.]YP=bE;kUW75^o:J $nZPzrBa?)?+VPBg_Zm4#I2' );
define( 'NONCE_KEY',        ',^,;<iDYMrDW_9I$-k7PHL=DMc@r2iB*n?d00:eP{/xCfwx9/7%(Kec9^mZ8nXrV' );
define( 'AUTH_SALT',        'rfw AouVz!MjJF39IL63]$/S8,jG42u7qd(RH6{ffNV9(tFTTGh2tgeL|sU90A^p' );
define( 'SECURE_AUTH_SALT', '#%1Hby#v&<5;TU$Qw(2Uzwky)=efs:9E(+CtQ!^C=C21>T4PE*bF5*LXCPq=ig}[' );
define( 'LOGGED_IN_SALT',   'u77ISt(wN~rkcA<c,#y$<+3cReMh/8w1hSgaE7K@=(+I7;_gO8>?:v@#^ab[WmXM' );
define( 'NONCE_SALT',       '#U[|s>g9Rn=JG+m*^BvM{ME-uVdDzoz<LG[<~l,OQ}s1;0:]:=79R)#d>((^!5qu' );

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
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
