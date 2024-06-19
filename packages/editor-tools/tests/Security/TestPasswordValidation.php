<?php
/**
 * Tests for PasswordValidation class.
 *
 * @package Security
 */

declare( strict_types=1 );
namespace Boxuk\BoxWpEditorTools\Security;

use Mockery;
use WP_Mock\Tools\TestCase;

/**
 * PasswordValidation test case.
 */
class TestPasswordValidation extends TestCase {

	/**
	 * Test `init` method
	 */
	public function test_init(): void {

		$password_validation = new PasswordValidation();

		\WP_Mock::expectActionAdded( 'user_profile_update_errors', array( $password_validation, 'user_profile_update_errors' ) );
		\WP_Mock::expectActionAdded( 'validate_password_reset', array( $password_validation, 'user_profile_update_errors' ) );
		\WP_Mock::expectActionAdded( 'registration_errors', array( $password_validation, 'user_profile_update_errors' ) );
		\WP_Mock::expectFilterAdded( 'password_hint', array( $password_validation, 'password_hint' ) );

		$password_validation->init();

		$this->assertConditionsMet();
	}

	/**
	 * Test `user_profile_update_errors` method
	 *
	 * @param string   $password The password to check.
	 * @param bool     $filter_enabled Whether the filter is enabled.
	 * @param string   $hook_name The hook name.
	 * @param string[] $expected_errors Whether an error should be expected.
	 *
	 * @return void
	 *
	 * @dataProvider user_profile_update_errors_provider
	 */
	public function test_user_profile_update_errors( string $password, bool $filter_enabled, string $hook_name, array $expected_errors ): void {
		$_POST['pass1'] = $password;

		\WP_Mock::onFilter( 'boxuk_validate_password' )->with( true )->reply( $filter_enabled );

		\WP_Mock::userFunction( 'doing_action' )
			->with( 'user_profile_update_errors' )
			->times( (int) $filter_enabled )
			->andReturn( 'user_profile_update_errors' === $hook_name );

		\WP_Mock::userFunction( 'sanitize_text_field' )
			->with( $password )
			->times( (int) $filter_enabled )
			->andReturn( $password );

		$error_holder = Mockery::mock( 'WP_Error' );

		$error_holder->expects( 'add' )->times( count( $expected_errors ) )->andReturnUsing(
			function ( string $code, string $message ) use ( $expected_errors ) {
				$this->assertContains( $message, $expected_errors );
			}
		);

		
		$password_validation = new PasswordValidation();
		$password_validation->user_profile_update_errors( $error_holder );

		$this->assertConditionsMet();
	}

	/**
	 * Provider for `test_user_profile_update_errors` method.
	 *
	 * @return array
	 */
	public function user_profile_update_errors_provider(): array {
		return array(
			'password too short'                    => array(
				'password'      => 'test',
				'enabled'       => true,
				'hook_name'     => 'validate_password_reset',
				'expect_errors' => array(
					'This value is too short. It should have 10 characters or more.',
					'Password must contain at least one number.',
					'Password must contain at least one uppercase letter.',
				),
			),
			'password too long'                     => array(
				'password'      => 'testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest',
				'enabled'       => true,
				'hook_name'     => 'validate_password_reset',
				'expect_errors' => array(
					'This value is too long. It should have 72 characters or less.',
					'Password must contain at least one uppercase letter.',
					'Password must contain at least one number.',
				),
			),
			'no number'                             => array(
				'password'      => 'testtesttest',
				'enabled'       => true,
				'hook_name'     => 'validate_password_reset',
				'expect_errors' => array(
					'Password must contain at least one number.',
					'Password must contain at least one uppercase letter.',
				),
			),
			'no uppercase'                          => array(
				'password'      => 'testtesttest1',
				'enabled'       => true,
				'hook_name'     => 'validate_password_reset',
				'expect_errors' => array(
					'Password must contain at least one uppercase letter.',
				),
			),
			'no lowercase'                          => array(
				'password'      => 'TESTTESTTEST1',
				'enabled'       => true,
				'hook_name'     => 'validate_password_reset',
				'expect_errors' => array(
					'Password must contain at least one lowercase letter.',
				),
			),
			'valid password'                        => array(
				'password'      => 'Testtesttest1',
				'enabled'       => true,
				'hook_name'     => 'validate_password_reset',
				'expect_errors' => array(),
			),
			'disabled feature, valid password'      => array(
				'password'      => 'Testtesttest1',
				'enabled'       => false,
				'hook_name'     => 'validate_password_reset',
				'expect_errors' => array(),
			),
			'disabled feature, invalid password'    => array(
				'password'      => 'test',
				'enabled'       => false,
				'hook_name'     => 'validate_password_reset',
				'expect_errors' => array(),
			),
			'on profile-update with empty password' => array(
				'password'      => '',
				'enabled'       => true,
				'hook_name'     => 'user_profile_update_errors',
				'expect_errors' => array(),
			),
		);
	}

	/**
	 * Test Password Hint
	 *
	 * @param bool   $enabled  Whether the filter is enabled.
	 * @param string $expected The expected password hint.
	 *
	 * @dataProvider password_hint_provider
	 *
	 * @return void
	 */
	public function test_password_hint( bool $enabled, string $expected ): void {

		\WP_Mock::onFilter( 'boxuk_validate_password' )->with( true )->reply( $enabled );

		$password_validation = new PasswordValidation();

		$this->assertEquals( $expected, $password_validation->password_hint( 'test' ) );
	}

	/**
	 * Provider for `test_password_hint` method.
	 *
	 * @return array
	 */
	public function password_hint_provider(): array {
		return array(
			'enabled'  => array(
				'enabled'  => true,
				'expected' => 'Hint: The password should be at least ten characters long, and include at least one upper case letter and one number. To make it stronger, use more upper and lower case letters, more numbers, and symbols like ! " ? $ % ^ & ).',
			),
			'disabled' => array(
				'enabled'  => false,
				'expected' => 'test',
			),
		);
	}
}
