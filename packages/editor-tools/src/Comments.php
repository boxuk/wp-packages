<?php
/**
 * Comment Disablement
 *
 * @package Boxuk\BoxWpEditorTools
 */

declare ( strict_types = 1 );

namespace Boxuk\BoxWpEditorTools;

/**
 * Comment Disablement
 */
class Comments {

	/**
	 * Core comment block name prefixes.
	 *
	 * @var list<string>
	 */
	private const COMMENT_BLOCK_PREFIXES = [
		'core/comment-',
		'core/comments',
		'core/post-comments',
		'core/latest-comments',
	];

	/**
	 * Init Hooks
	 *
	 * @return void
	 */
	public function init(): void {

		if ( ! apply_filters( 'boxuk_disable_comments', true ) ) {
			return;
		}

		add_filter( 'get_comments_number', '__return_zero', 20 );
		add_filter( 'pre_option_default_comment_status', [ $this, 'closed_status' ] );
		add_filter( 'pre_option_default_ping_status', [ $this, 'closed_status' ] );
		add_filter( 'rest_request_before_callbacks', [ $this, 'guard_comment_routes' ], 10, 3 );
		// Run after post types register their supports.
		add_action( 'init', [ $this, 'disable_comments_post_types_support' ], 100 );
		add_filter( 'allowed_block_types_all', [ $this, 'remove_comment_blocks' ], 20 );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_editor_script' ] );
		add_filter( 'wp_insert_post_data', [ $this, 'close_post_statuses' ], 20 );
		add_action( 'admin_init', [ $this, 'prevent_discussion_settings_access' ] );

		add_filter( 'comments_open', '__return_false', 20 );
		add_filter( 'pings_open', '__return_false', 20 );
		add_filter( 'comments_array', '__return_empty_array', 10 );

		add_action( 'admin_init', [ $this, 'disable_comments_post_types_support' ] );
		add_action( 'admin_init', [ $this, 'remove_dashboard_meta' ] );
		add_action( 'admin_init', [ $this, 'prevent_access_to_edit_page' ] );
		add_action( 'admin_menu', [ $this, 'remove_menu_options' ] );
		add_action( 'init', [ $this, 'remove_menu_bar_options' ] );
	}

	/**
	 * Disable Comments Post Types Support
	 *
	 * @return void
	 */
	public function disable_comments_post_types_support(): void {
		// Remove all post-type support for comments.
		foreach ( get_post_types() as $post_type ) {
			if ( post_type_supports( $post_type, 'comments' ) ) {
				remove_post_type_support( $post_type, 'comments' );
				remove_post_type_support( $post_type, 'trackbacks' );
			}
		}
	}

	/**
	 * Remove Dashboard Metabox for comments
	 *
	 * @return void
	 */
	public function remove_dashboard_meta(): void {
		remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
	}

	/**
	 * Prevent access to the edit page for comments
	 *
	 * @return void
	 */
	public function prevent_access_to_edit_page(): void {
		global $pagenow;

		// Prevent access to the comments page by redirecting requests.
		if ( 'edit-comments.php' === $pagenow ) {
			wp_die( esc_html__( 'Comments are closed.', 'boxuk' ), '', [ 'response' => 403 ] );
		}
	}

	/**
	 * Remove menu options for comments, and the
	 * comments section in the settings area.
	 *
	 * @return void
	 */
	public function remove_menu_options(): void {
		remove_menu_page( 'edit-comments.php' );
		remove_submenu_page( 'options-general.php', 'options-discussion.php' );
	}

	/**
	 * Remove the comments menu bar options from the admin menu bar
	 * on the frontend.
	 *
	 * @return void
	 */
	public function remove_menu_bar_options(): void {
		if ( is_admin_bar_showing() ) {
			remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
		}
	}

	/**
	 * Prevent access to discussion settings.
	 *
	 * @return void
	 */
	public function prevent_discussion_settings_access(): void {
		global $pagenow;

		if ( 'options-discussion.php' === $pagenow ) {
			wp_die( esc_html__( 'Comments are closed.', 'boxuk' ), '', [ 'response' => 403 ] );
		}
	}

	/**
	 * Return the default status for comments and pings.
	 *
	 * @return string
	 */
	public function closed_status(): string {
		return 'closed';
	}

	/**
	 * Close comments and pings on every post write.
	 *
	 * Defence in depth: pre_option_default_* only sets the default, so direct wp_insert_post() calls could still store open statuses.
	 *
	 * @param array<string, mixed> $data Post data to store.
	 * @return array<string, mixed>
	 */
	public function close_post_statuses( array $data ): array {
		$data['comment_status'] = 'closed';
		$data['ping_status']    = 'closed';

		return $data;
	}

	/**
	 * Restrict comment REST requests to authenticated editor Notes.
	 *
	 * Guard routes instead of unregistering them because WordPress 7.1 editor Notes are comments of type note read through /wp/v2/comments.
	 * Errors raised earlier, such as core parameter validation, are replaced too, so anonymous requests always receive the 404.
	 *
	 * @param mixed            $response Response before the callback runs.
	 * @param mixed            $handler  Registered route handler.
	 * @param \WP_REST_Request $request  Current REST request.
	 * @return mixed
	 */
	public function guard_comment_routes( $response, $handler, \WP_REST_Request $request ) {
		// The REST router matches routes case-insensitively, so compare in lower case.
		$route = strtolower( $request->get_route() );

		if ( '/wp/v2/comments' !== $route && 0 !== strpos( $route, '/wp/v2/comments/' ) ) {
			return $response;
		}

		if ( ! is_user_logged_in() ) {
			return $this->no_route_error();
		}

		if ( '/wp/v2/comments' === $route ) {
			return 'note' === $request->get_param( 'type' ) ? $response : $this->no_route_error();
		}

		$id = $request->get_param( 'id' );
		if ( ! is_int( $id ) && ! is_string( $id ) ) {
			return $this->no_route_error();
		}

		$comment = get_comment( (int) $id );

		return $comment instanceof \WP_Comment && 'note' === $comment->comment_type ? $response : $this->no_route_error();
	}

	/**
	 * Hide unavailable comment routes using the core REST error.
	 *
	 * @return \WP_Error
	 */
	public function no_route_error(): \WP_Error {
		return new \WP_Error( 'rest_no_route', __( 'No route was found matching the URL and request method.', 'boxuk' ), [ 'status' => 404 ] );
	}

	/**
	 * Exclude comment blocks from explicit allow-lists.
	 *
	 * Preserve boolean flags so blocks registered only in JavaScript remain available.
	 *
	 * @param bool|list<mixed> $allowed_blocks Allowed block names or availability flag.
	 * @return bool|list<string>
	 */
	public function remove_comment_blocks( $allowed_blocks ) {
		if ( is_bool( $allowed_blocks ) ) {
			return $allowed_blocks;
		}

		return array_values(
			array_filter(
				array_filter( $allowed_blocks, 'is_string' ),
				static function ( string $block_name ): bool {
					foreach ( self::COMMENT_BLOCK_PREFIXES as $prefix ) {
						if ( 0 === strpos( $block_name, $prefix ) ) {
							return false;
						}
					}

					return true;
				}
			)
		);
	}

	/**
	 * Enqueue the editor filter that hides comment blocks from the inserter.
	 *
	 * @return void
	 */
	public function enqueue_editor_script(): void {
		if ( ! file_exists( $this->get_asset_path() ) ) {
			return;
		}

		$asset = require $this->get_asset_path(); // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable

		if ( ! is_array( $asset ) || ! isset( $asset['dependencies'], $asset['version'] ) ) {
			return;
		}

		wp_enqueue_script(
			'boxuk-editor-tools',
			plugins_url( 'build/index.js', __DIR__ ),
			$asset['dependencies'],
			$asset['version'],
			true
		);
	}

	/**
	 * Get the editor script build metadata path.
	 *
	 * @return string
	 */
	public function get_asset_path(): string {
		return dirname( __DIR__ ) . '/build/index.asset.php';
	}
}
