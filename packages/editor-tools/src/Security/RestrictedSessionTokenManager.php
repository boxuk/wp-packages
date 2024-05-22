<?php
/**
 * Set UserSessions.
 *
 * @package Boxuk\BoxWpEditorTools
 */

declare( strict_types=1 );

namespace Boxuk\BoxWpEditorTools\Security;

/**
 * Handles user session limits
 */
class RestrictedSessionTokenManager extends \WP_User_Meta_Session_Tokens {

	/**
	 * Retrieves latest sessions of a user as defined by the limit
	 *
	 * @return array<mixed> Limited sessions of the user.
	 */
	protected function get_sessions(): array {
		$sessions = parent::get_sessions();
		$limit    = UserSessions::get_session_limit();

		if ( 0 === $limit ) {
			return $sessions;
		}

		return array_slice( $sessions, $limit * -1 );
	}
}
