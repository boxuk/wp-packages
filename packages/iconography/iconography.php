<?php
/**
 * Plugin Name: Iconography
 * 
 * @package Peake\Client\Mu\Plugins\Iconography
 */

declare ( strict_types = 1 );

namespace Peake\Client\Mu\Plugins\Iconography;

/**
 * Loads Iconography
 */
class Iconography {

	/**
	 * Scripts
	 * 
	 * @var array<string,string>
	 */
	protected array $scripts = array(
		'material-symbols-sharp'    => 'https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200',
		'material-symbols-rounded'  => 'https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200',
		'material-symbols-outlined' => 'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200',
	);

	/**
	 * Page Content
	 * 
	 * @var string
	 */
	public string $content = '';

	/**
	 * Init Hooks
	 * 
	 * @return void
	 */
	public function init(): void {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'enqueue_block_assets', array( $this, 'register_scripts' ) );
		add_action( 'enqueue_block_assets', array( $this, 'enqueue_all_iconography' ) );
		add_action( 'wp_footer', array( $this, 'maybe_add_scripts' ), 1, 0 );
	}

	/**
	 * Register Scripts
	 * 
	 * @return void
	 */
	public function register_scripts(): void {
		foreach ( $this->scripts as $handle => $src ) {
			$this->register_style( $handle, $src );
		}
	}

	/**
	 * Enqueue all iconography scripts (for the block editor)
	 * 
	 * @return void
	 */
	public function enqueue_all_iconography(): void {
		foreach ( $this->scripts as $handle => $src ) {
			wp_enqueue_style( $handle );
		}
		
		$asset = require __DIR__ . '/build/index.asset.php';
		wp_enqueue_script( 
			'iconography', 
			plugins_url( 'build/index.js', __FILE__ ), 
			$asset['dependencies'], 
			$asset['version'], 
			true 
		);
	}

	/**
	 * Maybe add scripts
	 * 
	 * @return void
	 */
	public function maybe_add_scripts(): void {
		$html = get_the_block_template_html();
		foreach ( $this->scripts as $handle => $src ) {
			if ( str_contains( $html, $handle ) ) {
				wp_enqueue_style( $handle );
			}
		}
	}

	/**
	 * Register a style
	 * 
	 * @param string $handle The handle.
	 * @param string $src The source.
	 * 
	 * @return void
	 */
	public function register_style( string $handle, string $src ): void {
		$inline_css = <<<CSS
			.{$handle} {
				font-size: inherit;
				vertical-align: text-bottom;
				line-height: 1.333;
				text-decoration: inherit;
				font-variation-settings:
					'FILL' 0,
					'wght' 400,
					'GRAD' 0,
					'opsz' 24;
			}
CSS;

		wp_register_style( $handle, $src ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		wp_add_inline_style( $handle, $inline_css );
	}
}

( new Iconography() )->init();
