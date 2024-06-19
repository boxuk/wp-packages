<?php
/**
 * Set Headers.
 *
 * @package Boxuk\BoxWpEditorTools
 */

declare( strict_types=1 );

namespace Boxuk\BoxWpEditorTools\Security;

/**
 * Class Headers
 */
class Headers {

	/**
	 * Init hooks.
	 */
	public function init(): void {
		add_filter( 'wp_headers', array( $this, 'remove_vip_headers' ) );
		add_action( 'init', array( $this, 'send_headers' ) );
	}

	/**
	 * Hook the nosniff and frame option headers to the send_headers action.
	 *
	 * @return void
	 */
	public function send_headers(): void {
		if ( true === apply_filters( 'boxuk_send_no_sniff_headers', true ) ) {
			add_action( 'send_headers', 'send_frame_options_header', 10, 0 );
			add_action( 'send_headers', 'send_nosniff_header', 10, 0 );
		}
	}

	/**
	 * Removes VIP headers.
	 *
	 * @param array<mixed> $headers Http Headers.
	 * @return array<mixed>
	 */
	public function remove_vip_headers( array $headers ): array {
		if ( true === apply_filters( 'boxuk_remove_vip_headers', true ) ) {
			unset( $headers['X-hacker'], $headers['X-Powered-By'] );
		}
		return $headers;
	}
}
