<?php
/**
 * Tests for UserLogin class.
 *
 * @package Security
 */

declare( strict_types=1 );
namespace Boxuk\BoxWpEditorTools\Security;

use Mockery;
use WP_Mock\Tools\TestCase;

/**
 * UserLogin test case.
 */
class TestUserLogin extends TestCase {

	/**
	 * Test `init` method
	 */
	public function test_init(): void {
		
		$user_login = new UserLogin();

		\WP_Mock::expectFilterAdded( 'map_meta_cap', array( $user_login, 'restrict_super_admins' ), 10, 2 );
		\WP_Mock::expectActionAdded( 'login_init', array( $user_login, 'restrict_login_by_username' ) );
		\WP_Mock::expectFilterAdded( 'show_password_fields', array( $user_login, 'show_password_fields' ), 10, 2 );

		$user_login->init();

		$this->assertConditionsMet();
	}

	/**
	 * Test `restrict_super_admins` method
	 * 
	 * @param string   $cap      The capabilities to check.
	 * @param string[] $expected The expected value.
	 * 
	 * @return void
	 * 
	 * @dataProvider restrict_super_admins_provider
	 */
	public function test_restrict_super_admins( string $cap, array $expected ): void {
		$user_login = new UserLogin();

		$this->assertEquals( $expected, $user_login->restrict_super_admins( array(), $cap ) );
	}

	/**
	 * Provider for `restrict_super_admins` method
	 * 
	 * @return array
	 */
	public function restrict_super_admins_provider(): array {
		return array( 
			'should_restrict'     => array( 'create_users', array( 'do_not_allow' ) ),
			'should_not_restrict' => array( 'edit_posts', array() ),
		);
	}

	/**
	 * Test `restrict_login_by_username` method
	 * 
	 * @return void
	 */
	public function test_restrict_login_by_username(): void {
		$user_login = new UserLogin();
		\WP_Mock::userFunction( 'remove_filter' )->once()->with( 'authenticate', 'wp_authenticate_username_password', 20 );
		$user_login->restrict_login_by_username();

		$this->assertConditionsMet();
	}

	/**
	 * Test `show_password_fields` method
	 * 
	 * @param bool $value   The existing value to determine if the password fields should show.
	 * @param int  $user_id The user id.
	 * @param int  $current_id The current user id.
	 * @param bool $expected The expected value.
	 * 
	 * @return void
	 * 
	 * @dataProvider show_password_fields_provider
	 */
	public function test_show_password_fields( bool $value, int $user_id, int $current_id, bool $expected ): void {
		$user_login = new UserLogin();

		$user     = Mockery::mock( 'WP_User' );
		$user->ID = $user_id;

		\WP_Mock::userFunction( 'get_current_user_id' )->once()->andReturn( $current_id );

		$this->assertEquals( $expected, $user_login->show_password_fields( $value, $user ) );
	}

	/**
	 * Provider for `show_password_fields` method
	 * 
	 * @return array
	 */
	public function show_password_fields_provider(): array {
		return array(
			array( true, 1, 1, true ),
			array( true, 1, 2, false ),
			array( false, 1, 1, false ),
			array( false, 1, 2, false ),
		);
	}
}
