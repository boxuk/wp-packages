<?php
/**
 * Post Type Management
 *
 * @package Boxuk\BoxWpEditorTools
 */

declare ( strict_types = 1 );

namespace Boxuk\BoxWpEditorTools;

/**
 * Post Type Management
 */
class PostTypes {

	/**
	 * Init Hooks
	 */
	public function init(): void {
		add_action( 'init', [ $this, 'register_post_types' ] );
		add_filter( 'register_post_type_args', [ $this, 'modify_post_types' ], 10, 2 );
	}

	/**
	 * Register Post Types
	 *
	 * @return void
	 */
	public function register_post_types(): void {

		$path = apply_filters(
			'boxuk_post_types_json_file_path',
			get_template_directory() . '/post-types.json'
		);
		if ( ! file_exists( $path ) ) {
			return;
		}
		$file = file_get_contents( $path ); // phpcs:ignore WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsUnknown
		$data = json_decode( $file ? $file : 'invalid file', true );
		if ( ! is_array( $data ) ) {
			return;
		}

		foreach ( $data['taxonomies'] ?? [] as $name => $args ) {
			register_taxonomy( $name, $args['post_types'] ?? 'post', $args );
		}

		foreach ( $data['post_types'] ?? [] as $name => $args ) {
			$args = wp_parse_args(
				$args,
				[
					'show_in_rest' => true,
					'public'       => true,
					'template'     => $this->blocks_to_template(
						$this->get_blocks_from_file( $name ) ?? []
					) ?? [],
				]
			);

			register_post_type( $name, $args ); // phpcs:ignore WordPress.NamingConventions.ValidPostTypeSlug.NotStringLiteral
		}   
	}

	/**
	 * Locate and Parse Template for Post Type
	 *
	 * @param string $name Post Type Name.
	 *
	 * @return array<array>|null
	 * // @phpstan-ignore-next-line -- return shape can't be desribed for phpstan because it's recursive.
	 */
	public function get_blocks_from_file( string $name ): ?array {

		if ( ! is_admin() ) {
			// Template is only used in the editor, so we don't need
			// to waste time parsing it for the front end.
			return null;
		}

		$path = get_template_directory() . '/post-type-templates/' . $name . '.html';
		if ( ! file_exists( $path ) ) {
			return null;
		}
		$file = file_get_contents( $path ); // phpcs:ignore WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsUnknown

		return parse_blocks( $file ? $file : '' );
	}

	/**
	 * Transform Blocks to Template
	 *
	 * @param array<array> $blocks Blocks.
	 *
	 * @return ?array<array{string,array,?array<array>}>
	 * // @phpstan-ignore-next-line -- return shape can't be desribed for phpstan because it's recursive.
	 */
	public function blocks_to_template( array $blocks ): ?array {

		$template = [];

		foreach ( $blocks as $block ) {
			$template[] = $this->block_to_template( $block );
		}

		return array_values( array_filter( $template ) );
	}

	/**
	 * Convert a block to a template array
	 *
	 * @param array $block The block to convert.
	 *
	 * @return ?array{string,array,?array<array>}
	 * // @phpstan-ignore-next-line -- return shape can't be desribed for phpstan because it's recursive.
	 */
	public function block_to_template( array $block ): ?array {
		$name = strval( $block['blockName'] ?? '' );
		if ( empty( $name ) ) {
			return null;
		}

		return [
			$name,
			$block['attrs'] ?? [],
			$this->blocks_to_template( $block['innerBlocks'] ?? [] ),
		];
	}

	/**
	 * Modify built in post types to load templates if they exist.
	 *
	 * @param array  $args Array of arguments.
	 * @param string $post_type Post Type.
	 * 
	 * // @phpstan-ignore-next-line -- return shape can't be desribed for phpstan because it's recursive.
	 */
	public function modify_post_types( array $args, string $post_type ): array {
		if ( 'post' !== $post_type && 'page' !== $post_type ) {
			return $args;
		}

		$args['template'] = $this->blocks_to_template(
			$this->get_blocks_from_file( $post_type ) ?? []
		) ?? [];

		return $args;
	}
}
