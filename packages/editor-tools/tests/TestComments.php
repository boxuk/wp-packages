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
		\WP_Mock::expectFilterAdded( 'get_comments_number', '__return_zero', 20 );
		\WP_Mock::expectFilterAdded( 'pre_option_default_comment_status', [ $comments, 'closed_status' ] );
		\WP_Mock::expectFilterAdded( 'pre_option_default_ping_status', [ $comments, 'closed_status' ] );
		\WP_Mock::expectFilterAdded( 'wp_insert_post_data', [ $comments, 'close_post_statuses' ], 20 );
		\WP_Mock::expectFilterAdded( 'allowed_block_types_all', [ $comments, 'remove_comment_blocks' ], 20 );
		\WP_Mock::expectActionAdded( 'enqueue_block_editor_assets', [ $comments, 'enqueue_editor_script' ] );
		\WP_Mock::expectFilterAdded( 'rest_request_before_callbacks', [ $comments, 'guard_comment_routes' ], 10, 3 );
		\WP_Mock::expectActionAdded( 'init', [ $comments, 'disable_comments_post_types_support' ], 100 );
		\WP_Mock::expectActionAdded( 'admin_init', [ $comments, 'prevent_discussion_settings_access' ] );
		\WP_Mock::expectActionAdded( 'admin_init', [ $comments, 'disable_comments_post_types_support' ] );
		\WP_Mock::expectActionAdded( 'admin_init', [ $comments, 'remove_dashboard_meta' ] );
		\WP_Mock::expectActionAdded( 'admin_init', [ $comments, 'prevent_access_to_edit_page' ] );
		\WP_Mock::expectActionAdded( 'admin_menu', [ $comments, 'remove_menu_options' ] );
		\WP_Mock::expectActionAdded( 'init', [ $comments, 'remove_menu_bar_options' ] );
		\WP_Mock::expectActionAdded( 'add_admin_bar_menus', [ $comments, 'remove_admin_bar_comments_menu' ] );
		\WP_Mock::expectActionAdded( 'wp_dashboard_setup', [ $comments, 'hide_activity_comments' ] );
		\WP_Mock::expectFilterAdded( 'rest_pre_dispatch', [ $comments, 'guard_options_requests' ], 9, 3 );
		
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
		\WP_Mock::expectFilterNotAdded( 'get_comments_number', '__return_zero', 20 );
		\WP_Mock::expectFilterNotAdded( 'pre_option_default_comment_status', [ $comments, 'closed_status' ] );
		\WP_Mock::expectFilterNotAdded( 'pre_option_default_ping_status', [ $comments, 'closed_status' ] );
		\WP_Mock::expectFilterNotAdded( 'wp_insert_post_data', [ $comments, 'close_post_statuses' ], 20 );
		\WP_Mock::expectFilterNotAdded( 'allowed_block_types_all', [ $comments, 'remove_comment_blocks' ], 20 );
		\WP_Mock::expectActionNotAdded( 'enqueue_block_editor_assets', [ $comments, 'enqueue_editor_script' ] );
		\WP_Mock::expectFilterNotAdded( 'rest_request_before_callbacks', [ $comments, 'guard_comment_routes' ], 10, 3 );
		\WP_Mock::expectActionNotAdded( 'init', [ $comments, 'disable_comments_post_types_support' ], 100 );
		\WP_Mock::expectActionNotAdded( 'admin_init', [ $comments, 'prevent_discussion_settings_access' ] );
		\WP_Mock::expectActionNotAdded( 'admin_init', [ $comments, 'disable_comments_post_types_support' ] );
		\WP_Mock::expectActionNotAdded( 'admin_init', [ $comments, 'remove_dashboard_meta' ] );
		\WP_Mock::expectActionNotAdded( 'admin_init', [ $comments, 'prevent_access_to_edit_page' ] );
		\WP_Mock::expectActionNotAdded( 'admin_menu', [ $comments, 'remove_menu_options' ] );
		\WP_Mock::expectActionNotAdded( 'init', [ $comments, 'remove_menu_bar_options' ] );
		\WP_Mock::expectActionNotAdded( 'add_admin_bar_menus', [ $comments, 'remove_admin_bar_comments_menu' ] );
		\WP_Mock::expectActionNotAdded( 'wp_dashboard_setup', [ $comments, 'hide_activity_comments' ] );
		\WP_Mock::expectFilterNotAdded( 'rest_pre_dispatch', [ $comments, 'guard_options_requests' ], 9, 3 );
		
		$comments->init();

		$this->assertConditionsMet();
	}

	/**
	 * Close default and stored statuses and hide comment counts.
	 *
	 * @return void
	 */
	public function testStatusAndCountFilters(): void {
		$comments = new Comments();
		\WP_Mock::onFilter( 'boxuk_disable_comments' )->with( true )->reply( true );
		\WP_Mock::expectFilterAdded( 'get_comments_number', '__return_zero', 20 );
		$comments->init();

		self::assertSame( 'closed', $comments->closed_status() );
		$this->assertHooksAdded();
		self::assertSame(
			[
				'post_title'     => 'Example',
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
			],
			$comments->close_post_statuses(
				[
					'post_title'     => 'Example',
					'comment_status' => 'open',
					'ping_status'    => 'open',
				]
			)
		);
	}

	/**
	 * Restrict discussion settings without blocking other screens.
	 *
	 * @return void
	 */
	public function testDiscussionSettingsAccess(): void {
		global $pagenow;
		$original = $pagenow;
		\WP_Mock::userFunction( 'wp_die' )->once()->with( 'Comments are closed.', '', [ 'response' => 403 ] );
		try {
			$pagenow = 'options-discussion.php'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- this is a test.
			( new Comments() )->prevent_discussion_settings_access();
			$pagenow = 'options-general.php'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- this is a test.
			( new Comments() )->prevent_discussion_settings_access();
		} finally {
			$pagenow = $original; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- restore the test global.
		}
		$this->assertConditionsMet();
	}

	/**
	 * Filter explicit block lists and preserve a disabled inserter.
	 *
	 * @return void
	 */
	public function testRemoveCommentBlocks(): void {
		$comments = new Comments();
		self::assertFalse( $comments->remove_comment_blocks( false ) );
		self::assertTrue( $comments->remove_comment_blocks( true ) );
		self::assertSame(
			[ 'core/paragraph', 'example/custom' ],
			$comments->remove_comment_blocks( [ 'core/comments', 'core/comment-template', 'core/post-comments-count', 'core/latest-comments', 'core/paragraph', 'example/custom', 42 ] )
		);
	}

	/**
	 * Do not enqueue the editor script when its build metadata is missing.
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 * @return void
	 */
	public function testMissingEditorAsset(): void {
		\WP_Mock::userFunction( __NAMESPACE__ . '\\file_exists' )
			->once()
			->with( dirname( __DIR__ ) . '/build/index.asset.php' )
			->andReturn( false );
		\WP_Mock::userFunction( 'wp_enqueue_script' )->never();

		( new Comments() )->enqueue_editor_script();
		$this->assertConditionsMet();
	}

	/**
	 * Enqueue the editor script with its build metadata.
	 *
	 * @return void
	 */
	public function testEnqueueEditorScript(): void {
		$comments = $this->createPartialMock( Comments::class, [ 'get_asset_path' ] );
		$comments->method( 'get_asset_path' )->willReturn( __DIR__ . '/fixtures/Comments/build/index.asset.php' );

		\WP_Mock::userFunction( 'plugins_url' )
			->once()
			->with( 'build/index.js', dirname( __DIR__ ) . '/src' )
			->andReturn( 'https://example.com/build/index.js' );
		\WP_Mock::userFunction( 'wp_enqueue_script' )
			->once()
			->with( 'boxuk-editor-tools', 'https://example.com/build/index.js', [ 'wp-hooks' ], '1', true );

		$comments->enqueue_editor_script();
		$this->assertConditionsMet();
	}

	/**
	 * Do not enqueue the editor script when its build metadata is invalid.
	 *
	 * @return void
	 */
	public function testInvalidEditorAsset(): void {
		$comments = $this->createPartialMock( Comments::class, [ 'get_asset_path' ] );
		$comments->method( 'get_asset_path' )->willReturn( __DIR__ . '/fixtures/Comments/build/invalid.asset.php' );

		\WP_Mock::userFunction( 'wp_enqueue_script' )->never();

		$comments->enqueue_editor_script();
		$this->assertConditionsMet();
	}

	/**
	 * Preserve only authenticated Notes on comment routes.
	 *
	 * @param string               $route Route to guard.
	 * @param bool                 $logged_in Whether the user is logged in.
	 * @param string|null          $type Requested comment type.
	 * @param array<string, mixed> $url_params Parameters the router captured from the path.
	 * @param mixed                $id Merged id parameter the controller loads.
	 * @param string|null          $stored_type Stored comment type, or missing comment.
	 * @param bool                 $allowed Whether the response passes through.
	 * @param bool                 $incoming_error Whether an earlier error exists.
	 * @dataProvider commentRouteProvider
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 * @return void
	 */
	public function testCommentRoutes( string $route, bool $logged_in, ?string $type, array $url_params, $id, ?string $stored_type, bool $allowed, bool $incoming_error ): void {
		$error = \Mockery::mock( 'overload:WP_Error' );
		if ( ! $allowed ) {
			$error->shouldReceive( '__construct' )->once()->with( 'rest_no_route', 'No route was found matching the URL and request method.', [ 'status' => 404 ] );
		}
		$response = $incoming_error ? $error : new \stdClass();
		$request  = \Mockery::mock( 'WP_REST_Request' );
		$request->shouldReceive( 'get_route' )->once()->andReturn( $route );
		$request->shouldReceive( 'get_url_params' )->andReturn( $url_params );
		$request->shouldReceive( 'get_param' )->with( 'type' )->andReturn( $type );
		$request->shouldReceive( 'get_param' )->with( 'id' )->andReturn( $id );
		\WP_Mock::userFunction( 'is_user_logged_in' )->andReturn( $logged_in );
		if ( null !== $stored_type ) {
			$comment               = \Mockery::mock( 'WP_Comment' );
			$comment->comment_type = $stored_type;
		} else {
			$comment = null;
		}
		\WP_Mock::userFunction( 'get_comment' )->with( is_scalar( $id ) ? (int) $id : 0 )->andReturn( $comment );
		$result = ( new Comments() )->guard_comment_routes( $response, [], $request );
		if ( $allowed ) {
			self::assertSame( $response, $result );
		} else {
			self::assertInstanceOf( \WP_Error::class, $result );
			self::assertNotSame( $response, $result );
		}
	}

	/**
	 * Comment route access cases.
	 *
	 * @return array
	 */
	public function commentRouteProvider(): array {
		$item = [ 'id' => '5' ];

		return [
			'other route'                => [ '/wp/v2/posts', false, null, [], null, null, true, false ],
			'other route error'          => [ '/wp/v2/posts', false, null, [], null, null, true, true ],
			'anonymous collection'       => [ '/wp/v2/comments', false, 'note', [], null, null, false, false ],
			'anonymous single'           => [ '/wp/v2/comments/5', false, null, $item, 5, 'note', false, false ],
			'anonymous uppercase'        => [ '/WP/V2/Comments', false, null, [], null, null, false, false ],
			'anonymous trailing newline' => [ "/wp/v2/comments\n", false, 'note', [], null, null, false, false ],
			'anonymous item newline'     => [ "/wp/v2/comments/5\n", false, null, $item, 5, 'note', false, false ],
			'anonymous prefix route'     => [ '/wp/v2/comments-feed', false, null, [], null, null, false, false ],
			'anonymous collection error' => [ '/wp/v2/comments', false, null, [], null, null, false, true ],
			'anonymous single error'     => [ '/wp/v2/comments/5', false, null, $item, 5, null, false, true ],
			'anonymous uppercase error'  => [ '/WP/V2/Comments', false, null, [], null, null, false, true ],
			'notes collection'           => [ '/wp/v2/comments', true, 'note', [], null, null, true, false ],
			'notes collection error'     => [ '/wp/v2/comments', true, 'note', [], null, null, true, true ],
			'notes uppercase'            => [ '/WP/V2/Comments', true, 'note', [], null, null, true, false ],
			'notes collection newline'   => [ "/wp/v2/comments\n", true, 'note', [], null, null, true, false ],
			'regular collection'         => [ '/wp/v2/comments', true, 'comment', [], null, null, false, false ],
			'regular collection newline' => [ "/wp/v2/comments\n", true, 'comment', [], null, null, false, false ],
			'untyped collection'         => [ '/wp/v2/comments', true, null, [], null, null, false, false ],
			'single note'                => [ '/wp/v2/comments/5', true, null, $item, '5', 'note', true, false ],
			'single note error'          => [ '/wp/v2/comments/5', true, null, $item, 5, 'note', true, true ],
			'single note newline'        => [ "/wp/v2/comments/5\n", true, null, $item, '5', 'note', true, false ],
			'single regular newline'     => [ "/wp/v2/comments/5\n", true, 'note', $item, '5', 'comment', false, false ],
			'single regular comment'     => [ '/wp/v2/Comments/5', true, 'note', $item, 5, 'comment', false, false ],
			'regular id param over note' => [ '/wp/v2/comments/5', true, null, $item, '6', 'comment', false, false ],
			'missing comment'            => [ '/wp/v2/comments/5', true, null, $item, 5, null, false, false ],
			'invalid id'                 => [ '/wp/v2/comments/5', true, null, $item, [], null, false, false ],
		];
	}

	/**
	 * Hide comment route schemas from anonymous OPTIONS requests only.
	 *
	 * @param string $method HTTP method.
	 * @param string $route Route requested.
	 * @param bool   $logged_in Whether the user is logged in.
	 * @param bool   $hidden Whether the route is hidden.
	 * @dataProvider optionsRequestProvider
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 * @return void
	 */
	public function testGuardOptionsRequests( string $method, string $route, bool $logged_in, bool $hidden ): void {
		$error = \Mockery::mock( 'overload:WP_Error' );
		if ( $hidden ) {
			$error->shouldReceive( '__construct' )->once()->with( 'rest_no_route', 'No route was found matching the URL and request method.', [ 'status' => 404 ] );
		}
		$request = \Mockery::mock( 'WP_REST_Request' );
		$request->shouldReceive( 'get_method' )->andReturn( $method );
		$request->shouldReceive( 'get_route' )->andReturn( $route );
		\WP_Mock::userFunction( 'is_user_logged_in' )->andReturn( $logged_in );

		$previous = new \stdClass();
		$result   = ( new Comments() )->guard_options_requests( $previous, new \stdClass(), $request );

		if ( $hidden ) {
			self::assertInstanceOf( \WP_Error::class, $result );
		} else {
			self::assertSame( $previous, $result );
		}
	}

	/**
	 * OPTIONS request cases.
	 *
	 * @return array
	 */
	public function optionsRequestProvider(): array {
		return [
			'anonymous collection'       => [ 'OPTIONS', '/wp/v2/comments', false, true ],
			'anonymous item'             => [ 'OPTIONS', '/wp/v2/comments/5', false, true ],
			'anonymous uppercase'        => [ 'OPTIONS', '/WP/V2/COMMENTS', false, true ],
			'anonymous trailing newline' => [ 'OPTIONS', "/wp/v2/comments\n", false, true ],
			'logged in'                  => [ 'OPTIONS', '/wp/v2/comments', true, false ],
			'anonymous GET'              => [ 'GET', '/wp/v2/comments', false, false ],
			'anonymous other route'      => [ 'OPTIONS', '/wp/v2/posts', false, false ],
		];
	}

	/**
	 * Remove the admin bar comments node on the hook that runs after core adds it.
	 *
	 * @return void
	 */
	public function testRemoveAdminBarCommentsMenu(): void {
		\WP_Mock::userFunction( 'remove_action' )
			->once()
			->with( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );

		( new Comments() )->remove_admin_bar_comments_menu();
		$this->assertConditionsMet();
	}

	/**
	 * Empty comment queries on the dashboard.
	 *
	 * @return void
	 */
	public function testHideActivityComments(): void {
		$comments = new Comments();
		\WP_Mock::expectFilterAdded( 'comments_pre_query', [ $comments, 'empty_comment_rows' ], 10, 2 );

		$comments->hide_activity_comments();
		$this->assertHooksAdded();
	}

	/**
	 * Return no rows for list queries and leave count queries to core.
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 * @return void
	 */
	public function testEmptyCommentRows(): void {
		$comments    = new Comments();
		$list_query  = \Mockery::mock( 'WP_Comment_Query' );
		$count_query = \Mockery::mock( 'WP_Comment_Query' );

		$list_query->query_vars  = [];
		$count_query->query_vars = [ 'count' => true ];

		self::assertSame( [], $comments->empty_comment_rows( null, $list_query ) );
		self::assertNull( $comments->empty_comment_rows( null, $count_query ) );
	}
}
