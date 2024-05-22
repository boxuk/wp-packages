<?php
/**
 * Set User Login/Registration Restrictions.
 *
 * @package Boxuk\BoxWpEditorTools
 */

declare( strict_types=1 );

namespace Boxuk\BoxWpEditorTools\Security;

/**
 * Class UserLogin
 */
class UserLogin {

	/**
	 * Init
	 * 
	 * @return void
	 */
	public function init(): void {
		add_filter( 'map_meta_cap', array( $this, 'restrict_super_admins' ), 10, 2 );
		add_action( 'login_init', array( $this, 'restrict_login_by_username' ) );
		add_filter( 'show_password_fields', array( $this, 'show_password_fields' ), 10, 2 );
	}

	/**
	 * Restrict the abilities to create users.
	 *
	 * @param array<string> $caps the array of capabilities.
	 * @param string|null   $cap the capability to check.
	 * @return array<string>
	 */
	public function restrict_super_admins( array $caps, ?string $cap ): array {
		if ( 'create_users' === $cap ) {
			$caps[] = 'do_not_allow';
		}
	
		return $caps;
	}

	/**
	 * Restrict login by username.
	 * 
	 * Prevents users from logging in with their username and enforces the use of their email address.
	 * This is added on `login_init` because if we add it at a global level it prevents users from
	 * being able to reset their password.
	 *
	 * @return void
	 */
	public function restrict_login_by_username(): void {
		remove_filter( 'authenticate', 'wp_authenticate_username_password', 20 );
	}

	/**
	 * Remove password fields from profile if editing another user.
	 * 
	 * @param bool     $value The existing value to determine if the password fields should show.
	 * @param \WP_User $user  The user object.
	 *
	 * @return bool
	 */
	public function show_password_fields( bool $value, \WP_User $user ): bool {
		if ( get_current_user_id() !== $user->ID ) {
			$value = false;
		}

		return $value;
	}
}
