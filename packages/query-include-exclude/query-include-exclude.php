<?php
/**
 * Plugin Name: Query Include Exclude
 * Description: Adds the ability to specify which posts to include or exclude in a query loop.
 * Version: 1.0.0
 * Author: BoxUK
 * Author URI: https://boxuk.com
 * 
 * @package Boxuk\QueryIncludeExclude
 */

declare( strict_types=1 );

namespace Boxuk\QueryIncludeExclude;

add_action(
	'enqueue_block_editor_assets',
	function () {
		$asset = require plugin_dir_path( __FILE__ ) . 'build/index.asset.php';

		wp_enqueue_script(
			'boxuk-query-include-exclude',
			plugins_url( 'build/index.js', __FILE__ ),
			$asset['dependencies'],
			$asset['version'],
			true
		);
	}
);
