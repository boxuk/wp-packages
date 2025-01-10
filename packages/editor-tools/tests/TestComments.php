<?php
/**
 * Test Comments class
 * 
 * @package Boxuk\BoxWpEditorTools 
 */

declare( strict_types = 1 );

namespace Boxuk\BoxWpEditorTools;

use WP_Mock\Tools\TestCase;

/**
 * TestComments class
 */
class TestComments extends TestCase {

	/**
	 * Test init
	 * 
	 * @return void
	 */
	public function testInit(): void {
		$comments = new Comments();

		\WP_Mock::onFilter( 'boxuk_disable_comments' )
			->with( true )
			->reply( true );

		\WP_Mock::expectFilterAdded( 'comments_open', '__return_false', 20 );
		\WP_Mock::expectFilterAdded( 'pings_open', '__return_false', 20 );
		\WP_Mock::expectFilterAdded( 'comments_array', '__return_empty_array', 10 );
		\WP_Mock::expectActionAdded( 'admin_init', [ $comments, 'disable_comments_post_types_support' ] );
		\WP_Mock::expectActionAdded( 'admin_init', [ $comments, 'remove_dashboard_meta' ] );
		\WP_Mock::expectActionAdded( 'admin_init', [ $comments, 'prevent_access_to_edit_page' ] );
		\WP_Mock::expectActionAdded( 'admin_menu', [ $comments, 'remove_menu_options' ] );
		\WP_Mock::expectActionAdded( 'init', [ $comments, 'remove_menu_bar_options' ] );
		
		$comments->init();

		$this->assertConditionsMet();
	}

	/**
	 * Test Disable Comments Post Types Support
	 * 
	 * @return void
	 */
	public function testDisableCommentsPostTypesSupport(): void {
		$post_types = [ 'post', 'page' ];
		\WP_Mock::userFunction( 'get_post_types' )
			->once()
			->andReturn( $post_types );

		foreach ( $post_types as $post_type ) { 
		
			\WP_Mock::userFunction( 'post_type_supports' )
				->once()->with( $post_type, 'comments' )->andReturn( true );

			\WP_Mock::userFunction( 'remove_post_type_support' )
				->once()
				->with( $post_type, 'comments' );
			\WP_Mock::userFunction( 'remove_post_type_support' )
				->once()
				->with( $post_type, 'trackbacks' );
		}

		$comments = new Comments();
		$comments->disable_comments_post_types_support();

		$this->assertConditionsMet();
	}

	/**
	 * Test Remove Dashboard Meta
	 * 
	 * @return void
	 */
	public function testRemoveDashboardMeta(): void {

		\WP_Mock::userFunction( 'remove_meta_box' )
			->once()
			->with( 'dashboard_recent_comments', 'dashboard', 'normal' );

		$comments = new Comments();
		$comments->remove_dashboard_meta();

		$this->assertConditionsMet();
	}

	/**
	 * Test Prevent Access to Edit Page
	 * 
	 * @return void
	 */
	public function testPreventAccessToEditPage(): void {
		global $pagenow;
		$pagenow = 'edit-comments.php'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- this is a test.

		\WP_Mock::userFunction( 'wp_die' )
			->once()
			->with( 'Comments are closed.', '', [ 'response' => 403 ] );
		
		$comments = new Comments();
		$comments->prevent_access_to_edit_page();

		$this->assertConditionsMet();
	}

	/**
	 * Test Remove Menu Options
	 * 
	 * @return void
	 */
	public function testRemoveMenuOptions(): void {
		\WP_Mock::userFunction( 'remove_menu_page' )
			->once()
			->with( 'edit-comments.php' );
		\WP_Mock::userFunction( 'remove_submenu_page' )
			->once()
			->with( 'options-general.php', 'options-discussion.php' );

		$comments = new Comments();
		$comments->remove_menu_options();

		$this->assertConditionsMet();
	}

	/**
	 * Test Remove Menu Bar Options
	 * 
	 * @return void
	 */ 
	public function testRemoveMenuBarOptions(): void {
		\WP_Mock::userFunction( 'is_admin_bar_showing' )
			->once()
			->andReturn( true );
		\WP_Mock::userFunction( 'remove_action' )
			->once()
			->with( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );

		$comments = new Comments();
		$comments->remove_menu_bar_options();

		$this->assertConditionsMet();
	}

	/**
	 * Test Disabled Comment Remover
	 * 
	 * @return void
	 */
	public function testDisabledCommentRemover(): void {
		$comments = new Comments();
		
		\WP_Mock::onFilter( 'boxuk_disable_comments' )
			->with( true )
			->reply( false );

		\WP_Mock::expectFilterNotAdded( 'comments_open', '__return_false', 20 );

		\WP_Mock::expectFilterNotAdded( 'comments_open', '__return_false', 20 );
		\WP_Mock::expectFilterNotAdded( 'pings_open', '__return_false', 20 );
		\WP_Mock::expectFilterNotAdded( 'comments_array', '__return_empty_array', 10 );
		\WP_Mock::expectActionNotAdded( 'admin_init', [ $comments, 'disable_comments_post_types_support' ] );
		\WP_Mock::expectActionNotAdded( 'admin_init', [ $comments, 'remove_dashboard_meta' ] );
		\WP_Mock::expectActionNotAdded( 'admin_init', [ $comments, 'prevent_access_to_edit_page' ] );
		\WP_Mock::expectActionNotAdded( 'admin_menu', [ $comments, 'remove_menu_options' ] );
		\WP_Mock::expectActionNotAdded( 'init', [ $comments, 'remove_menu_bar_options' ] );
		
		$comments->init();

		$this->assertConditionsMet();
	}
}
