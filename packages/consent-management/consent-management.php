<?php
/**
 * Plugin Name: ConsentManagement
 * Description: A plugin that allows you to add GTM and cookie consent to your site.
 * Version: 1.0.0
 * Author: BoxUK
 * Author URI: https://boxuk.com
 * 
 * @package Boxuk\ConsentManagement
 */

declare( strict_types=1 );

namespace Boxuk\ConsentManagement;

/**
 * Loads Consent Management
 */
class ConsentManagement {

	/**
	 * Hook Name
	 *
	 * @var string
	 */
	private const HOOK_NAME = 'consent-management';

	/**
	 * Option Name
	 *
	 * @var string
	 */
	private const OPTION_NAME = 'consent_options';

	/**
	 * Init Hooks
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin' ) );
		add_action( 'init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'add_settings_screen' ) );
	}

	/**
	 * Enqueue Scripts
	 *
	 * @return void
	 */
	public function enqueue_frontend(): void {

		$asset = require __DIR__ . '/build/frontend.asset.php';

		wp_enqueue_script(
			self::HOOK_NAME,
			plugins_url( '/build/frontend.js', __FILE__ ),
			$asset['dependencies'],
			$asset['version'],
			false
		);

		wp_localize_script(
			self::HOOK_NAME,
			'consentManagement',
			(array) get_option( self::OPTION_NAME, array() ),
		);

		wp_enqueue_style(
			self::HOOK_NAME,
			plugins_url( '/build/frontend.css', __FILE__ ),
			array(),
			$asset['version']
		);
	}

	/**
	 * Enqueue Admin Assets
	 *
	 * @param string $suffix The current admin page.
	 *
	 * @return void
	 */
	public function enqueue_admin( string $suffix ): void {
		if ( 'settings_page_' . self::HOOK_NAME !== $suffix ) {
			return;
		}

		$this->enqueue_frontend();

		$asset = require __DIR__ . '/build/admin.asset.php';

		wp_enqueue_script(
			self::HOOK_NAME . '-admin',
			plugins_url( '/build/admin.js', __FILE__ ),
			$asset['dependencies'],
			$asset['version'],
			true
		);


		wp_enqueue_style(
			self::HOOK_NAME . '-admin',
			plugins_url( '/build/style-frontend.css', __FILE__ ),
			array(),
			$asset['version']
		);

		wp_enqueue_style( 'wp-edit-post' );
		do_action( 'enqueue_block_editor_assets' );
	}

	/**
	 * Register Settings
	 *
	 * @return void
	 */
	public function register_settings(): void {
		$schema = file_get_contents( __DIR__ . '/schema.json' );
		register_setting(
			self::OPTION_NAME,
			self::OPTION_NAME,
			array(
				'type'         => 'object',
				'show_in_rest' => array(
					'schema' => json_decode( $schema ? $schema : '{}', true ),
				),
			)
		);

		$block_json_file = plugin_dir_path( __FILE__ ) . 'build/blocks/ShowConsent/block.json';

		register_block_type( $block_json_file );
	}

	/**
	 * Add Settings Screen
	 *
	 * @return void
	 */
	public function add_settings_screen(): void {
		add_options_page(
			__( 'Cookie Consent', 'boxuk' ),
			__( 'Cookie Consent', 'boxuk' ),
			'manage_options',
			self::HOOK_NAME,
			array( $this, 'render_settings_screen' ),
		);
	}

	/**
	 * Render Settings Screen
	 *
	 * @return void
	 */
	public function render_settings_screen(): void {
		?>
		<div class='wrap'>
			<div id="consent-settings-root"></div>
		</div>
		<?php
	}
}

add_action(
	'plugins_loaded',
	function () {
		$consent_management = new ConsentManagement();
		$consent_management->init();
	}
);
