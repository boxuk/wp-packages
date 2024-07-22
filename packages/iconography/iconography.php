<?php
/**
 * Plugin Name: Iconography
 * Description: Provides iconography for the block editor.
 * Version: 1.0.0
 * Author: BoxUK
 * Author URI: https://www.boxuk.com
 * Requires at least: 6.4
 *
 * @package Boxuk\Iconography
 */

declare ( strict_types = 1 );

namespace Boxuk\Iconography;

add_action(
	'plugins_loaded',
	function () {

		add_filter(
			'boxuk_iconography_files',
			function ( $config_files ) {
				$plugin_dir = __DIR__;

				$config_files['material-symbols-outlined']        = $plugin_dir . '/config/material-symbols-outlined.config.json';
				$config_files['material-symbols-outlined-filled'] = $plugin_dir . '/config/material-symbols-outlined-filled.config.json';
				$config_files['material-symbols-sharp']           = $plugin_dir . '/config/material-symbols-sharp.config.json';
				$config_files['material-symbols-sharp-filled']    = $plugin_dir . '/config/material-symbols-sharp-filled.config.json';
				$config_files['material-symbols-rounded']         = $plugin_dir . '/config/material-symbols-rounded.config.json';
				$config_files['material-symbols-rounded-filled']  = $plugin_dir . '/config/material-symbols-rounded-filled.config.json';
				return $config_files;
			}
		);
		( new IconographyService( new ConfigurationParser() ) )->init();
	}
);
