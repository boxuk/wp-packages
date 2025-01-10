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
		add_action( 'init', [ $this, 'register_blocks' ] );
		add_filter( 'block_type_metadata', [ $this, 'enforce_versioning' ] );
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
			register_block_type( dirname( $block_json_file ), [] );
		}
	}

	/**
	 * Enforce Versioning
	 *
	 * @param array{version?:string,editorScript?:string,file?:string} $metadata Block metadata.
	 * 
	 * @return array{version?:string,editorScript?:string,file?:string} Block metadata.
	 */
	public function enforce_versioning( array $metadata ): array {
		if ( ! empty( $metadata['version'] ) ) {
			return $metadata;
		}

		$editor_script = $metadata['editorScript'] ?? '';
		if ( empty( $editor_script ) ) {
			return $metadata;
		}

		$json_file_path = $metadata['file'] ?? '';
		if ( empty( $json_file_path ) ) {
			return $metadata;
		}
		
		// @see `register_block_script_handle()` in WordPress core.
		$script_path       = remove_block_asset_path_prefix( $editor_script );
		$path              = dirname( $json_file_path );
		$script_asset_path = realpath( $path . '/' . substr_replace( $script_path, '.asset.php', - strlen( '.js' ) ) );

		if ( ! $script_asset_path || ! file_exists( $script_asset_path ) ) {
			return $metadata;
		}

		$asset = require $script_asset_path; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable -- This is required.
		
		$metadata['version'] = $asset['version'] ?? '';
		return $metadata;
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
