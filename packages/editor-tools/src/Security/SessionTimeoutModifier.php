<?php
/**
 * Session Timeout Modifier
 *
 * @package Boxuk\BoxWpEditorTools
 */

declare( strict_types=1 );

namespace Boxuk\BoxWpEditorTools\Security;

/**
 * Class Session_Timeout_Modifier
 *
 * @package Boxuk\BoxWpEditorTools
 */
class SessionTimeoutModifier {

	/**
	 * Initialise the filter.
	 */
	public function init(): void {
		add_filter( 'auth_cookie_expiration', array( $this, 'auth_cookie_expiration_filter' ), 99, 3 );
	}

	/**
	 * Modify the default session timeout value. 
	 *
	 * @param int  $wp_default_expiration the default WP session expiration timeout value, in seconds.
	 * @param int  $user_id the current user id.
	 * @param bool $remember_me whether or not the user selected 'remember me' when logging in.
	 *
	 * @return int
	 */
	public function auth_cookie_expiration_filter( int $wp_default_expiration, int $user_id, bool $remember_me ): int {
		if ( $remember_me ) {
			return $wp_default_expiration;
		}

		return 60 * 60 * 10;
	}
}
