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
	 * Init Hooks
	 *
	 * @return void
	 */
	public function init(): void {

		if ( ! apply_filters( 'boxuk_disable_comments', true ) ) {
			return;
		}

		add_filter( 'comments_open', '__return_false', 20 );
		add_filter( 'pings_open', '__return_false', 20 );
		add_filter( 'comments_array', '__return_empty_array', 10 );

		add_action( 'admin_init', array( $this, 'disable_comments_post_types_support' ) );
		add_action( 'admin_init', array( $this, 'remove_dashboard_meta' ) );
		add_action( 'admin_init', array( $this, 'prevent_access_to_edit_page' ) );
		add_action( 'admin_menu', array( $this, 'remove_menu_options' ) );
		add_action( 'init', array( $this, 'remove_menu_bar_options' ) );
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
			wp_die( esc_html__( 'Comments are closed.', 'boxuk' ), '', array( 'response' => 403 ) );
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
}
