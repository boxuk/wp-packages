<?php
/**
 * Bootstrap the unit testing environment.
 * 
 * @package Boxuk\BoxWpEditorTools
 */

declare ( strict_types=1 );
 
$root_dir = dirname( __DIR__ );
require_once $root_dir . '/vendor/autoload.php';
WP_Mock::bootstrap();
