<?php
/**
 * Bootstrap the unit testing environment.
 * 
 * @package Boxuk\BoxWpEditorTools
 */

declare ( strict_types=1 );
 
$root_dir = dirname( __DIR__, 1 );
require_once $root_dir . '/vendor/autoload.php';

define( 'WP_DEBUG', true );
define( 'WP_CONTENT_DIR', $root_dir );
define( 'WP_CONTENT_URL', 'http://example.org/wp-content' );

WP_Mock::bootstrap();
