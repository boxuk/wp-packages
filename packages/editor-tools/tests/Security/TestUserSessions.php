<?php
/**
 * Tests for UserSessions class.
 *
 * @package Security
 */

declare( strict_types=1 );
namespace Boxuk\BoxWpEditorTools\Security;

use WP_Mock\Tools\TestCase;

/**
 * UserSessions test case.
 */
class TestUserSessions extends TestCase {

	/**
	 * Test `init` method
	 */
	public function test_init(): void {
		
		$user_sessions = new UserSessions();

		\WP_Mock::expectActionAdded( 'admin_init', [ $user_sessions, 'register_settings' ] );
		\WP_Mock::expectFilterAdded( 'session_token_manager', [ $user_sessions, 'get_session_token_manager' ], 10, 1 );

		$user_sessions->init();

		$this->assertConditionsMet();
	}

	/**
	 * Test `register_settings` method
	 */
	public function test_register_settings(): void {
		$user_sessions = new UserSessions();

		\WP_Mock::userFunction( 'register_setting' )->once()->with(
			'general', 
			'user_session_limit', 
			[
				'type'              => 'integer',
				'show_in_rest'      => true,
				'default'           => 0,
				'sanitize_callback' => 'absint',
			]
		);

		\WP_Mock::userFunction( 'add_settings_field' )->once()->with(
			'user_session_limit',
			'User Session Limit',
			[ $user_sessions, 'render_user_session_limit_field' ],
			'general',
			'default',
			[
				'label_for' => 'user_session_limit',
				'class'     => 'user-session-limit',
			]
		);

		$user_sessions->register_settings();

		$this->assertConditionsMet();
	}

	/**
	 * Test `render_user_session_limit_field` method
	 */
	public function test_render_user_session_limit_field(): void {
		$user_sessions = new UserSessions();

		\WP_Mock::userFunction( 'get_option' )->once()->with( 'user_session_limit', 0 )->andReturn( '0' );
		\WP_Mock::userFunction( 'absint' )->once()->with( '0' )->andReturn( 0 );
		\WP_Mock::userFunction( 'esc_attr' )->once()->with( '0' )->andReturn( '0' );

		$user_sessions->render_user_session_limit_field();

		$this->assertEqualsHtml(
			' <inputtype="number"name="user_session_limit"id="user_session_limit"value="0"class="regular-text"min="0"/><p class="description">The maximum number of concurrent sessions a user can have, 0 for unlimited </p>',
			$this->getActualOutputForAssertion()
		);

		$this->assertConditionsMet();
	}

	/**
	 * Test `get_session_token_manager` method
	 * 
	 * @param string $existing The existing value.
	 * @param string $expected The expected value.
	 * 
	 * @return void
	 * 
	 * @dataProvider get_session_token_manager_provider
	 */
	public function test_get_session_token_manager( string $existing, string $expected ): void {
		$user_sessions = new UserSessions();

		$this->assertEquals( $expected, $user_sessions->get_session_token_manager( $existing ) );
	}

	/**
	 * Provider for `get_session_token_manager` method
	 * 
	 * @return array
	 */
	public function get_session_token_manager_provider(): array {
		return [ 
			[ 'AClassThatShouldntBeFiltered', 'AClassThatShouldntBeFiltered' ],
			[ 'WP_User_Meta_Session_Tokens', RestrictedSessionTokenManager::class ],
		];
	}
}
