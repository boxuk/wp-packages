<?php
/**
 * RSS Disablement
 *
 * @package Boxuk\BoxWpEditorTools\Security
 */

declare( strict_types=1 );

namespace Boxuk\BoxWpEditorTools\Security;

/**
 * Class AuthorEnumeration
 */
class RSS {

	/**
	 * Init Hooks
	 *
	 * @return void
	 */
	public function init() {
		if ( false === apply_filters( 'boxuk_disable_rss', true ) ) {
			return;
		}

		add_action( 'do_feed', [ $this, 'send_404' ], 1 );
		add_action( 'do_feed_rdf', [ $this, 'send_404' ], 1 );
		add_action( 'do_feed_rss', [ $this, 'send_404' ], 1 );
		add_action( 'do_feed_rss2', [ $this, 'send_404' ], 1 );
		add_action( 'do_feed_atom', [ $this, 'send_404' ], 1 );
		add_action( 'do_feed_rss2_comments', [ $this, 'send_404' ], 1 );
		add_action( 'do_feed_atom_comments', [ $this, 'send_404' ], 1 );

		add_filter( 'feed_content_type', [ $this, 'feed_content_type' ], 10, 0 );

		remove_action( 'wp_head', 'feed_links_extra', 3 );
		remove_action( 'wp_head', 'feed_links', 2 );
	}

	/**
	 * Make sure all RSS feeds are blank.
	 *
	 * @return void
	 */
	public function send_404(): void {
		status_header( 404 );
		nocache_headers();
		include get_query_template( '404' );
		$this->exit();
	}

	/**
	 * Feed content type
	 *
	 * @return string
	 */
	public function feed_content_type(): string {
		return 'text/html';
	}

	/**
	 * Exit
	 *
	 * Test stub for `exit` function.
	 *
	 * @codeCoverageIgnore -- we can't handle the `exit`.
	 *
	 * @return void
	 */
	public function exit(): void {
		exit;
	}
}
