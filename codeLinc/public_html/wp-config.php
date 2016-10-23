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
define('DB_NAME', 'codeLinc');

/** MySQL database username */
define('DB_USER', 'codeLincUser');

/** MySQL database password */
define('DB_PASSWORD', 'codeLinc3.0');

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

define('AUTH_KEY',         '{!8D-P=9?+=gEk;4Tp+T`,8QW?{$wTz>ba$io[ovSV>F5|=8/NR7lJm}`/4R;RP;');
define('SECURE_AUTH_KEY',  'fZJZ9.8/=K6Pm}T=uSeNo$g>jfl/Wz(z#_ z!DN)b,BwXDA_ST/n--s]|o.nM-|5');
define('LOGGED_IN_KEY',    'V<-.[D?pEY9=mG=f7$J3`L[2md/i!kISX&drq@SC)lWg*8ESo/zj7JX;X aV/mP6');
define('NONCE_KEY',        'J^pSSUO`/~|*o5|i)C&vW#u-is}$;~I>d7vNY+5{?7%vb(V5C*M!.?DaT %<y`l8');
define('AUTH_SALT',        'c})Sd_T#Y],**o{ iyL- kE+rt`3-2G8k-)QuZuk^/{-G+<_=gi_fU94(N#@l-3C');
define('SECURE_AUTH_SALT', 't;I+f2D%nZ/`e{2BT9v~i/Ab)zdIZ3+^TI%>fr/6bjRP5C)fewib+}tr7-sG8QUr');
define('LOGGED_IN_SALT',   'g^H.LTALhdm9mO prM;`9DV}8^6W4XRa^,li2}poa.M h9@=D7S|J{DO2B`k&aZ%');
define('NONCE_SALT',       'NJq&:WF*d|Ngji}`>Uy?oV3Q,m)$56BZIh%KQY[+jFIm^|PF5,/qFWMq^~E^dV~<');

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
