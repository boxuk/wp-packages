<?php
/**
 * Tests for RestrictedSessionTokenManager class.
 *
 * @package Security
 */

declare( strict_types=1 );
namespace Boxuk\BoxWpEditorTools\Security;

use WP_Mock\Tools\TestCase;

/**
 * RestrictedSessionTokenManager test case.
 */
class TestRestrictedSessionTokenManager extends TestCase {

	/**
	 * Test `get_sessions` method
	 * 
	 * @dataProvider get_sessions_provider
	 * 
	 * @param int   $session_limit      The session limit.
	 * @param array $existing_sessions  The existing sessions.
	 * @param array $expected           The expected result.
	 * 
	 * @return void
	 */
	public function test_get_sessions(
		int $session_limit,
		array $existing_sessions,
		array $expected
	): void {

		\WP_Mock::userFunction( 'get_option' )->andReturn( $session_limit );
		\WP_Mock::userFunction( 'absint' )->andReturnArg( 0 );
		
		// Define the global WP_User_Meta_Session_Tokens class as a mock so we can override the get_sessions method.
		\Mockery::mock( 'overload:WP_User_Meta_Session_Tokens' )
			->shouldReceive( 'get_sessions' )
			->andReturn( $existing_sessions );

		// Reflect the class in test so we can access the protected method.
		$reflected_class = new \ReflectionClass( RestrictedSessionTokenManager::class );
		$method          = $reflected_class->getMethod( 'get_sessions' );
		$method->setAccessible( true );

		$token_manager = new RestrictedSessionTokenManager( 1 );
		$actual        = $method->invokeArgs( $token_manager, [] );
		
		$this->assertEquals( $expected, $actual );
	}

	/**
	 * Data provider for `test_get_sessions` method.
	 * 
	 * @return array
	 */
	public function get_sessions_provider(): array {
		return [
			'no session limit' => [
				'session_limit'     => 0,
				'existing_sessions' => [
					[
						'session_id'         => 'session_id_1',
						'session_expiration' => 1,
					],
					[
						'session_id'         => 'session_id_2',
						'session_expiration' => 2,
					],
				],
				'expected'          => [
					[
						'session_id'         => 'session_id_1',
						'session_expiration' => 1,
					],
					[
						'session_id'         => 'session_id_2',
						'session_expiration' => 2,
					],
				],
			],
			'session limit'    => [
				'session_limit'     => 1,
				'existing_sessions' => [
					[
						'session_id'         => 'session_id_1',
						'session_expiration' => 1,
					],
					[
						'session_id'         => 'session_id_2',
						'session_expiration' => 2,
					],
				],
				'expected'          => [
					[
						'session_id'         => 'session_id_2',
						'session_expiration' => 2,
					],
				],
			],
		];
	}
}
