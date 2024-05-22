<?php
/**
 * Tests for RSS class.
 *
 * @package Security
 */

declare( strict_types=1 );
namespace Boxuk\BoxWpEditorTools\Security;

use Mockery;
use WP_Mock\Tools\TestCase;

/**
 * RSS test case.
 */
class TestRSS extends TestCase {

	/**
	 * Test `init` method
	 */
	public function test_init() { 

		$class_in_test = new RSS();

		\WP_Mock::expectActionAdded( 'do_feed', array( $class_in_test, 'send_404' ), 1 );
		\WP_Mock::expectActionAdded( 'do_feed_rdf', array( $class_in_test, 'send_404' ), 1 );
		\WP_Mock::expectActionAdded( 'do_feed_rss', array( $class_in_test, 'send_404' ), 1 );
		\WP_Mock::expectActionAdded( 'do_feed_rss2', array( $class_in_test, 'send_404' ), 1 );
		\WP_Mock::expectActionAdded( 'do_feed_atom', array( $class_in_test, 'send_404' ), 1 );
		\WP_Mock::expectActionAdded( 'do_feed_rss2_comments', array( $class_in_test, 'send_404' ), 1 );
		\WP_Mock::expectActionAdded( 'do_feed_atom_comments', array( $class_in_test, 'send_404' ), 1 );

		\WP_Mock::userFunction( 'remove_action' )
			->once()->with( 'wp_head', 'feed_links_extra', 3 );
		\WP_Mock::userFunction( 'remove_action' )
			->once()->with( 'wp_head', 'feed_links', 2 );

		$class_in_test->init();
		$this->assertConditionsMet();
	}

	/**
	 * Test `send_404` method
	 */
	public function test_send_404() {
		$class_in_test = Mockery::mock( RSS::class )->makePartial();
		$class_in_test->shouldReceive( 'exit' )->once();

		\WP_Mock::userFunction( 'status_header' )
			->once()->with( 404 );
		\WP_Mock::userFunction( 'nocache_headers' )
			->once();
		\WP_Mock::userFunction( 'get_query_template' )
			->once()->with( '404' )->andReturn( __DIR__ . '/fixtures/empty-template.php' );

		$class_in_test->send_404();
		$this->assertConditionsMet();
	}

	/**
	 * Test `feed_content_type` method
	 */
	public function test_feed_content_type() {
		$class_in_test = new RSS();

		$this->assertEquals( 'text/html', $class_in_test->feed_content_type() );
	}
}
