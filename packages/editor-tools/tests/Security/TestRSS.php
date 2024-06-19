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
	 * 
	 * @param bool $enabled Whether the feature is enabled.
	 * 
	 * @dataProvider init_provider
	 */
	public function test_init( bool $enabled ) { 

		\WP_Mock::onFilter( 'boxuk_disable_rss' )->with( true )->reply( $enabled );
		$class_in_test = new RSS();

		if ( ! $enabled ) { 
			\WP_Mock::expectActionNotAdded( 'do_feed', array( $class_in_test, 'send_404' ), 1 );
			\WP_Mock::expectActionNotAdded( 'do_feed_rdf', array( $class_in_test, 'send_404' ), 1 );
			\WP_Mock::expectActionNotAdded( 'do_feed_rss', array( $class_in_test, 'send_404' ), 1 );
			\WP_Mock::expectActionNotAdded( 'do_feed_rss2', array( $class_in_test, 'send_404' ), 1 );
			\WP_Mock::expectActionNotAdded( 'do_feed_atom', array( $class_in_test, 'send_404' ), 1 );
			\WP_Mock::expectActionNotAdded( 'do_feed_rss2_comments', array( $class_in_test, 'send_404' ), 1 );
			\WP_Mock::expectActionNotAdded( 'do_feed_atom_comments', array( $class_in_test, 'send_404' ), 1 );
	
			\WP_Mock::userFunction( 'remove_action' )
				->never()->with( 'wp_head', 'feed_links_extra', 3 );
			\WP_Mock::userFunction( 'remove_action' )
				->never()->with( 'wp_head', 'feed_links', 2 );
		} else {
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
		}

		$class_in_test->init();
		$this->assertConditionsMet();
	}

	/**
	 * Provider for `init` method
	 *
	 * @return array
	 */
	public function init_provider(): array {
		return array(
			'enabled'  => array( true ),
			'disabled' => array( false ),
		);
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
