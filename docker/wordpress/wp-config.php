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
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

define( 'DATABASE_TYPE', 'sqlite' );
define( 'DB_DIR', '/var/www/html/wp-content/database' );
define( 'DB_FILE', 'wordpress.db' );

define( 'AUTH_KEY', 'uDh8?//l(9c$Qu`NRD4H3k426U3V}x!I#{pbFV7Z!1eT)T$[+6Go)Gpg?K5Z|R{D' );
define( 'SECURE_AUTH_KEY', 'nGAeRQI%DhdGhMoT.{Ba(&V5D[Z7FDO@NHRX_d3;q~S+jko-tsA; `+~6!<r_X>b' );
define( 'LOGGED_IN_KEY', 'cqiEHUYDtHxsqW=w|_.g{uCL2cifNJMi_WL2yf?sn)=UF+6nhO>a-qKLH75bZ)n1' );
define( 'NONCE_KEY', ';H#]ee;nLS~h l~Oy(p|1f<@-;yG1%Y{ h(P8-T<(9X+M(?uUkD)obyL+R[)<Z4z' );
define( 'AUTH_SALT', 'pjVYn-F]kG%^)##=3^Y@_bH@OW~3#fp:D;B-+#skTM{W+|MtedR|qVn%hQ3Iv2 &' );
define( 'SECURE_AUTH_SALT', ')C4@PV(d:DU0}i{,P[r.mrjfwyX!>kZvvhRW$|QZQ;S&8&Rep.N$*E938l9bxi`m' );
define( 'LOGGED_IN_SALT', '%V}O=GU*_{),8)-n-DXKhZXhqeu}lR1U0oA,#f+=7br+&g2TAE_>ZL-,ogsWuedO' );
define( 'NONCE_SALT', 'dm[=a6+2b1rHuGW=xE`#-<}<w_ -dnmdvM%:ZcxLCCPtq2>o{}NlqT>4<kL6nX-#' );

/**#@-*/
$table_prefix = 'wp_'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
define( 'WP_DEBUG', true );
define( 'SCRIPT_DEBUG', true );

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-content/plugins/vendor/autoload.php';
require_once ABSPATH . 'wp-settings.php';
