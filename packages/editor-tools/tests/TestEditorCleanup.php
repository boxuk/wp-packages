<?php
/**
 * Test EditorCleanup class
 * 
 * @package Boxuk\BoxWpEditorTools
 */

declare( strict_types = 1 );

namespace Boxuk\BoxWpEditorTools;

use WP_Mock\Tools\TestCase;

/**
 * EditorCleanup class
 */
class TestEditorCleanup extends TestCase {

	/**
	 * Test init
	 * 
	 * @return void
	 */
	public function testInit(): void {
		$editor_cleanup = new EditorCleanup();
		\WP_Mock::expectActionAdded( 'after_setup_theme', array( $editor_cleanup, 'remove_theme_support' ) );
		\WP_Mock::expectActionAdded( 'init', array( $editor_cleanup, 'remove_actions' ), 100 );
		$editor_cleanup->init();
		$this->assertConditionsMet();
	}

	/**
	 * Test Remove Theme Support
	 * 
	 * @return void
	 */
	public function testRemoveThemeSupport(): void {
		\WP_Mock::userFunction( 'remove_theme_support' )
			->once()
			->with( 'core-block-patterns' );

		$editor_cleanup = new EditorCleanup();
		$editor_cleanup->remove_theme_support();

		$this->assertConditionsMet();
	}

	/**
	 * Test Remove Actions
	 * 
	 * @return void
	 */
	public function testRemoveActions(): void {

		\WP_Mock::userFunction( 'remove_action' )
			->once()
			->with( 'enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets' );
		\WP_Mock::userFunction( 'remove_action' )
			->once()
			->with( 'admin_notices', 'wpcom_vip_render_vip_featured_plugins', 99 );
		\WP_Mock::userFunction( 'remove_action' )
			->once()
			->with( 'network_admin_notices', 'wpcom_vip_render_vip_featured_plugins', 99 );

		$editor_cleanup = new EditorCleanup();
		$editor_cleanup->remove_actions();
		$this->assertConditionsMet();
	}
}
