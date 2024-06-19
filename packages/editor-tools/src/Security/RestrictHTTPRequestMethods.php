<?php
/**
 * Restrict HTTP Request Methods.
 *
 * @package Boxuk\BoxWpEditorTools
 */

declare( strict_types=1 );

namespace Boxuk\BoxWpEditorTools\Security;

/**
 * Class Restrict_HTTP_Request_Methods
 *
 * @package Boxuk\BoxWpEditorTools
 */
class RestrictHTTPRequestMethods {

	/**
	 * Only requests using one of the following HTTP methods will be allowed.
	 *
	 * VIP already handle 'HEAD' and 'PURGE' requests, so these methods will be unaffected by this change
	 * on VIP environments. 'HEAD' has been added to the allowed methods list to avoid conflicting with the
	 * VIP code, in case the order of hooks/actions gets changed (or something like that.)
	 *
	 * 'OPTIONS' has been added, because it's used to update widget areas (and possibly other things).
	 *
	 * @var string[]
	 */
	public const ALLOWED_METHODS = array( 'POST', 'GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS' );

	/**
	 * Init
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'init', array( $this, 'block_request_if_not_using_allowed_method' ) );
	}

	/**
	 * Send a 403 response if this request isn't using one of our explicitly allowed methods.
	 *
	 * @return void
	 * */
	public function block_request_if_not_using_allowed_method() {
		if ( $this->is_cli() || ( ! apply_filters( 'boxuk_restrict_http_request_methods', true ) ) ) {
			return;
		}

		if ( ! in_array( $this->get_method(), self::ALLOWED_METHODS, true ) ) {
			status_header( 403 );
			wp_die( 'Invalid request method.' );
		}
	}

	/**
	 * Get the current method.
	 *
	 * @return string The current method or empty string if it can't be determined.
	 */
	public function get_method(): string {
		return sanitize_text_field( $_SERVER['REQUEST_METHOD'] ?? '' );
	}

	/**
	 * Check if the request is from the command line.
	 *
	 * @return bool Whether the request is from the command line.
	 *
	 * @codeCoverageIgnore -- We can't mock constants.
	 */
	public function is_cli(): bool {
		return defined( 'WP_CLI' ) && \WP_CLI;
	}
}
