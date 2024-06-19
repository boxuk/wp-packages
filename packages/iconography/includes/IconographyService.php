<?php
/**
 * Handles registering and enqueing assets for the Iconography solution.
 *
 * @package Iconography
 *
 * @since 1.0.0
 */

declare( strict_types = 1 );

namespace Boxuk\Iconography;

use Boxuk\Iconography\Model\IconGroup;

/**
 * IconographyService
 */
class IconographyService {

	/**
	 * Create a new EnqueueAssets
	 *
	 * @param ConfigurationParser $configuration_parser The configuration parser.
	 */
	public function __construct( private ConfigurationParser $configuration_parser ) {}

	/**
	 * Init Hooks
	 */
	public function init(): void {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
		add_action( 'wp_footer', array( $this, 'enqueue_assets' ), 1, 0 );
		add_action( 'enqueue_block_assets', array( $this, 'register_assets' ), 1, 0 );
		add_action( 'enqueue_block_assets', array( $this, 'enqueue_editor_scripts' ) );
		add_action( 'enqueue_block_assets', array( $this, 'enqueue_all_assets' ) );
	}

	/**
	 * Register all assets in WP
	 *
	 * @return void
	 */
	public function register_assets() {
		foreach ( $this->configuration_parser->get_configs() as $config ) {
			$config->register_assets();
		}
	}

	/**
	 * Enqueue Assets based on content.
	 *
	 * @return void
	 */
	public function enqueue_assets(): void {
		$html = get_the_block_template_html();
		foreach ( $this->configuration_parser->get_configs() as $config ) {
			$config->enqueue_assets( $html );
		}
	}

	/**
	 * Enqueue all assets
	 *
	 * Useful in the block editor where we don't know the content to test against.
	 *
	 * @return void
	 */
	public function enqueue_all_assets(): void {
		foreach ( $this->configuration_parser->get_configs() as $config ) {
			$config->enqueue_assets();
		}
	}

	/**
	 * Get Grouped Icons
	 *
	 * @return array<Model\IconGroup>
	 */
	public function get_icon_groups(): array {
		return $this->configuration_parser->get_configs();
	}

	/**
	 * Enqueue all iconography scripts (for the block editor)
	 *
	 * @return void
	 */
	public function enqueue_editor_scripts(): void {
		$asset = require plugin_dir_path( __DIR__ ) . 'build/index.asset.php';
		wp_enqueue_script(
			'iconography',
			plugins_url( 'build/index.js', __DIR__ ),
			$asset['dependencies'],
			$asset['version'],
			true
		);

		wp_localize_script(
			'iconography',
			'boxIconography',
			array(
				'iconGroups' => array_map(
					fn( IconGroup $group ) => $group->to_json(),
					$this->get_icon_groups()
				),
			)
		);
	}
}
