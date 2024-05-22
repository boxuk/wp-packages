<?php
/**
 * Tests for Session_Timeout_Modifier.
 *
 * @package Security
 */

declare( strict_types=1 );
namespace Boxuk\BoxWpEditorTools\Security;

use WP_Mock\Tools\TestCase;

/**
 * Session_Timeout_Modifier test case.
 */
class TestSessionTimeoutModifier extends TestCase {

	/**
	 * SUT.
	 *
	 * @var SessionTimeoutModifier the system under test.
	 */
	private SessionTimeoutModifier $sut;

	/**
	 * Set up.
	 */
	public function setUp(): void {
		$this->sut = new SessionTimeoutModifier();
	}

	/**
	 * Test `init` method
	 * 
	 * @return void
	 */
	public function test_init(): void {
		\WP_Mock::expectFilterAdded( 'auth_cookie_expiration', array( $this->sut, 'auth_cookie_expiration_filter' ), 99, 3 );

		$this->sut->init();

		$this->assertConditionsMet();
	}

	/**
	 * Test `auth_cookie_expiration_filter` method
	 * 
	 * @param bool $remember_me Whether the user ticked the 'remember me' box.
	 * @param int  $expected    The expected expiration time.
	 * 
	 * @return void
	 * 
	 * @dataProvider auth_cookie_expiration_filter_provider
	 */
	public function test_auth_cookie_expiration_filter( bool $remember_me, int $expected ): void {
		$this->assertEquals( $expected, $this->sut->auth_cookie_expiration_filter( 200, 1, $remember_me ) );
	}

	/**
	 * Data provider for `test_auth_cookie_expiration_filter`
	 * 
	 * @return array
	 */
	public function auth_cookie_expiration_filter_provider(): array {
		return array(
			array( true, 200 ),
			array( false, 36000 ),
		);
	}
}
