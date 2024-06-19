<?php
/**
 * Author Enumeration Prevention
 *
 * @package Boxuk\BoxWpEditorTools\Security
 */

declare( strict_types=1 );

namespace Boxuk\BoxWpEditorTools\Security;

/**
 * Class AuthorEnumeration
 */
class AuthorEnumeration {

	/**
	 * Init Hooks
	 */
	public function init(): void {
		add_filter( 'redirect_canonical', array( $this, 'prevent_author_enum' ) );
		add_filter( 'rest_endpoints', array( $this, 'handle_rest_endpoints' ) );
	}

	/**
	 * Returns a 404 instead of redirecting an author query (?author=1) to the pretty printed URL (/author/admin).
	 *
	 * @param string $redirect The pretty permalink URL.
	 *
	 * @return ?string The pretty permalink URL or null if the author query is set.
	 */
	public function prevent_author_enum( string $redirect ): ?string {
		if ( false === apply_filters( 'boxuk_prevent_author_enum', true ) ) {
			return $redirect;
		}

		if ( get_query_var( 'author', false ) ) {
			global $wp_query;
			$wp_query->set_404();

			add_filter( 'wp_title', array( $this, 'get_404_title' ), PHP_INT_MAX );

			status_header( 404 );
			nocache_headers();

			return null;
		} else {
			return $redirect;
		}
	}

	/**
	 * Get 404 Title
	 *
	 * @return string
	 */
	public function get_404_title(): string {
		return __( '404: Not Found', 'boxuk' );
	}

	/**
	 * Filters out the 'user' endpoints from the default list of endpoints.
	 *
	 * @param array<mixed> $endpoints WordPress provided variable of all available endpoints.
	 * @return array<mixed> The filtered list of endpoints.
	 */
	public function handle_rest_endpoints( array $endpoints ): array {

		if ( false === apply_filters( 'boxuk_prevent_author_rest_endpoint', true ) ) {
			return $endpoints;
		}

		// Block editor requires this endpoint for getting user details for authors.
		if ( current_user_can( 'edit_posts' ) ) {
			return $endpoints;
		}

		unset( $endpoints['/wp/v2/users'] );
		unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );

		return $endpoints;
	}
}
