<?php
/**
 * Set Minimum Password Validation.
 *
 * @package Boxuk\BoxWpEditorTools
 */

declare( strict_types=1 );

namespace Boxuk\BoxWpEditorTools\Security;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Validation;

/**
 * Class PasswordValidation
 */
class PasswordValidation {

	/**
	 * Init
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'user_profile_update_errors', array( $this, 'user_profile_update_errors' ) );
		add_action( 'validate_password_reset', array( $this, 'user_profile_update_errors' ) );
		add_action( 'registration_errors', array( $this, 'user_profile_update_errors' ) );
		add_filter( 'password_hint', array( $this, 'password_hint' ) );
	}

	/**
	 * User profile update errors.
	 *
	 * @param \WP_Error $errors WP Error.
	 *
	 * @return void
	 */
	public function user_profile_update_errors( \WP_Error $errors ): void {
		$password = sanitize_text_field( $_POST['pass1'] ?? '' ); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verification is handled by WP core.
		$this->validate_password( $password, $errors );
	}

	/**
	 * Validate password.
	 *
	 * @param string    $password The password to be validated.
	 * @param \WP_Error $errors   WP Error.
	 *
	 * @return void
	 */
	public function validate_password( string $password, \WP_Error &$errors ): void { // phpcs:ignore NeutronStandard.Functions.TypeHint.NoArgumentType -- false positive.
		$constraints = array(
			new NotBlank(),
			new Length(
				array(
					'min' => 10,
					'max' => 72,
				)
			),
			new Regex(
				array(
					'pattern' => '/[a-z]/',
					'message' => 'Password must contain at least one lowercase letter.',
				)
			),
			new Regex(
				array(
					'pattern' => '/[A-Z]/',
					'message' => 'Password must contain at least one uppercase letter.',
				)
			),
			new Regex(
				array(
					'pattern' => '/[0-9]/',
					'message' => 'Password must contain at least one number.',
				)
			),
		);

		$validator = Validation::createValidator();

		foreach ( $validator->validate( $password, $constraints ) as $violation ) {
			$errors->add( $violation->getPropertyPath(), strval( $violation->getMessage() ) );
		}
	}

	/**
	 * Password hint.
	 *
	 * @param string $hint The password hint.
	 *
	 * @return string
	 */
	public function password_hint( string $hint ): string {

		$hint = __( 'Hint: The password should be at least ten characters long, and include at least one upper case letter and one number. To make it stronger, use more upper and lower case letters, more numbers, and symbols like ! " ? $ % ^ & ).', 'boxuk' );

		return $hint;
	}
}
