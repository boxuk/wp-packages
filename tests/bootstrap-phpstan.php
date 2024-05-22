<?php
/**
 * Define WordPress constants for PHPStan.
 *
 * @package PHPStan
 */

declare ( strict_types=1 );

/**
 * Load Environment Configuration
*/
define( 'PROJECT_NAME', 'boxuk-base-wp-plugin' );
define( 'WP_CONTENT_URL', 'http://localhost/wp-content' );

/* Load Stubs */
$files = glob( __DIR__ . '/stubs/*.php' );
foreach ( $files as $file ) {
	require_once $file;
}
