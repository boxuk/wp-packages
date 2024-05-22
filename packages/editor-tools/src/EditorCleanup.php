<?php
/**
 * Editor Cleanup
 * 
 * Removes unnecessary features from the editor.
 * 
 * @package Boxuk\BoxWpEditorTools
 */

declare ( strict_types = 1 );

namespace Boxuk\BoxWpEditorTools;

/**
 * Editor Cleanup
 */
class EditorCleanup {
	/**
	 * Init Hooks
	 * 
	 * @return void
	 */
	public function init(): void {
		add_action( 'after_setup_theme', array( $this, 'remove_theme_support' ) );
		add_action( 'init', array( $this, 'remove_actions' ), 100 );
	}

	/**
	 * Remove Theme Support
	 * 
	 * @return void
	 */
	public function remove_theme_support(): void {
		remove_theme_support( 'core-block-patterns' );
	}

	/**
	 * Remove Actions
	 * 
	 * @return void
	 */
	public function remove_actions(): void {
		// Disable the 'block-directory', the UI for adding blocks from the plugins directory - it's not needed.
		remove_action( 'enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets' );
		
		// Remove the VIP feature plugins notices.
		remove_action( 'admin_notices', 'wpcom_vip_render_vip_featured_plugins', 99 );
		remove_action( 'network_admin_notices', 'wpcom_vip_render_vip_featured_plugins', 99 );
	}
}
