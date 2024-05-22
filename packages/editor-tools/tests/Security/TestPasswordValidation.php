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
	 * @param string[] $expected_errors Whether an error should be expected.
	 * 
	 * @return void
	 * 
	 * @dataProvider user_profile_update_errors_provider
	 */
	public function test_user_profile_update_errors( string $password, array $expected_errors ): void {

		\WP_Mock::userFunction( 'sanitize_text_field' )->once()->andReturn( $password );
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
			'password too short' => array(
				'password'      => 'test',
				'expect_errors' => array(
					'This value is too short. It should have 10 characters or more.',
					'Password must contain at least one number.',
					'Password must contain at least one uppercase letter.',
				),
			),
			'password too long'  => array(
				'password'      => 'testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest',
				'expect_errors' => array(
					'This value is too long. It should have 72 characters or less.',
					'Password must contain at least one uppercase letter.',
					'Password must contain at least one number.',
				),
			),
			'no number'          => array( 
				'testtesttest',
				array(
					'Password must contain at least one number.',
					'Password must contain at least one uppercase letter.',
				),
			),
			'no uppercase'       => array( 
				'testtesttest1',
				array(
					'Password must contain at least one uppercase letter.',
				),
			),
			'no lowercase'       => array( 
				'TESTTESTTEST1',
				array(
					'Password must contain at least one lowercase letter.',
				),
			),
			'valid password'     => array( 
				'Testtesttest1',
				array(),
			),
		);
	}

	/**
	 * Test Password Hint
	 * 
	 * @return void
	 */
	public function test_password_hint(): void {
		$password_validation = new PasswordValidation();
		$this->assertEquals( 'Hint: The password should be at least ten characters long, and include at least one upper case letter and one number. To make it stronger, use more upper and lower case letters, more numbers, and symbols like ! " ? $ % ^ & ).', $password_validation->password_hint( '' ) );
	}
}
