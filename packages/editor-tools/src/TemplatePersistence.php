<?php
/**
 * A method to persist template changes to disk.
 * 
 * @package Boxuk\BoxWpEditorTools
 */

declare ( strict_types = 1 );

namespace Boxuk\BoxWpEditorTools;

/**
 * TemplatePersistence
 */
class TemplatePersistence {

	/**
	 * Init Hooks
	 * 
	 * @return void
	 */
	public function init(): void {
		add_action( 'save_post', array( $this, 'persist_template' ), 10, 2 );
	}

	/**
	 * Persist Template
	 * 
	 * @param int      $post_id Post ID.
	 * @param \WP_Post $post    The post.
	 *  
	 * @return void
	 */
	public function persist_template( int $post_id, \WP_Post $post ): void { 
		
		$default = wp_get_environment_type() !== 'local';
		if ( apply_filters( 'boxuk_disable_template_persistence', $default ) ) {
			return;
		}
		
		$filename = $this->get_template_filename( $post );
		
		if ( false === $filename || ! $this->is_writeable( $filename ) ) {
			return;
		}

		if ( file_put_contents( $filename, $post->post_content . PHP_EOL ) ) { // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_file_put_contents -- We're not in a VIP environment.
			wp_delete_post( $post->ID, true );
		}
	}

	/**
	 * Gets the filename for a template.
	 * 
	 * @param \WP_Post $post The post object.
	 * 
	 * @return string|false The filename or false if the post is not a template.
	 */
	public static function get_template_filename( \WP_Post $post ): string|false {
		// We only want to persist templates.
		$template_dir = match ( $post->post_type ) {
			'wp_template' => 'templates',
			'wp_template_part' => 'parts',
			default => false,
		};

		if ( false === $template_dir ) {
			return false;
		}

		return join(
			'/', 
			array( 
				rtrim( get_stylesheet_directory(), '/' ),
				$template_dir,
				$post->post_name . '.html',
			)
		);
	}

	/**
	 * Checks if a file is writeable
	 * 
	 * @param string $filename The filename.
	 * 
	 * @return bool True if the file is writeable, false otherwise.
	 */
	public static function is_writeable( string $filename ): bool {

		$directory = dirname( $filename );

		if ( ! is_dir( $directory ) ) {
			mkdir( $directory, 0755, true ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.directory_mkdir -- We're not in a VIP environment.
		}

		return is_writeable( $directory ) && touch( $filename ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_touch, WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_is_writeable -- We're not in a VIP environment.
	}
}
