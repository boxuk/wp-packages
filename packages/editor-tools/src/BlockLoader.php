<?php
/**
 * Loads Blocks
 *
 * @package Boxuk\BoxWpEditorTools
 */

declare ( strict_types = 1 );

namespace Boxuk\BoxWpEditorTools;

/**
 * Loads Blocks
 */
class BlockLoader {

	/**
	 * Init Hooks
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'init', array( $this, 'register_blocks' ) );
	}

	/**
	 * Register Blocks
	 *
	 * Finds all block.json files and registers them as blocks.
	 *
	 * @return void
	 */
	public function register_blocks(): void {

		$block_json_file_paths = glob( $this->get_base_path() . '/**/*/block.json' );

		if ( ! $block_json_file_paths ) {
			return;
		}

		foreach ( $block_json_file_paths as $block_json_file ) {
			register_block_type( dirname( $block_json_file ), array() );
		}
	}

	/**
	 * Get the base path
	 *
	 * @return string
	 */
	private function get_base_path(): string {
		return apply_filters( 'boxuk_block_loader_base_path', get_template_directory() . '/build' );
	}
}
