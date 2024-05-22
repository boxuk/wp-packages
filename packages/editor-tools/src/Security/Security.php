<?php
/**
 * Security Hardening
 * 
 * @package Boxuk\BoxWpEditorTools\Security
 */

declare( strict_types=1 );

namespace Boxuk\BoxWpEditorTools\Security;

/**
 * Class Security
 */
class Security {

	/**
	 * Init Hooks
	 * 
	 * @param boolean $author_enumeration Enable the author enumeration hardening (default: true).
	 * @param boolean $headers Remove headers (default: true).
	 * @param boolean $password_validation Enabled stronger password validation (default: true).
	 * @param boolean $restricted_user_sessions Restrict user sessions (default: true).
	 * @param boolean $restricted_http_request_methods Restrict HTTP request methods (default: true).
	 * @param boolean $restrict_rss Restrict RSS (default: true).
	 * @param boolean $modify_session_timeouts Modify session timeouts (default: true).
	 * @param boolean $user_login_hardening User login hardening (default: true).
	 */
	public function init(
		bool $author_enumeration = true,
		bool $headers = true,
		bool $password_validation = true,
		bool $restricted_user_sessions = true,
		bool $restricted_http_request_methods = true,
		bool $restrict_rss = true,
		bool $modify_session_timeouts = true,
		bool $user_login_hardening = true
	): void {

		if ( $author_enumeration ) { 
			( new AuthorEnumeration() )->init();
		}

		if ( $headers ) { 
			( new Headers() )->init();
		}

		if ( $password_validation ) { 
			( new PasswordValidation() )->init();
		}

		if ( $restricted_user_sessions ) { 
			( new UserSessions() )->init();
		}

		if ( $restricted_http_request_methods ) { 
			( new RestrictHTTPRequestMethods() )->init();
		}

		if ( $restrict_rss ) { 
			( new RSS() )->init();
		}

		if ( $modify_session_timeouts ) { 
			( new SessionTimeoutModifier() )->init();
		}

		if ( $user_login_hardening ) { 
			( new UserLogin() )->init();
		}
	}
}
