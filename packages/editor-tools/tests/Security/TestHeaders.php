<?php
/**
 * Tests for security features.
 *
 * @package Security
 */

declare( strict_types=1 );
namespace Boxuk\BoxWpEditorTools\Security;

use WP_Mock\Tools\TestCase;

/**
 * Security features test case.
 */
class TestHeaders extends TestCase {

	/**
	 * Test `init` method
	 * 
	 * @return void
	 */
	public function test_init(): void {

		$headers = new Headers();

		\WP_Mock::expectFilterAdded( 'wp_headers', array( $headers, 'remove_vip_headers' ) );
		\WP_Mock::expectActionAdded( 'init', array( $headers, 'send_headers' ) );
	
		$headers->init();

		$this->assertConditionsMet();
	}

	/**
	 * Test `send_headers` method
	 * 
	 * @return void
	 */
	public function test_send_headers(): void {
		\WP_Mock::userFunction( 'is_page_template' )
			->with( 'template-blank.php' )
			->andReturn( false );

		\WP_Mock::expectActionAdded( 'send_headers', 'send_frame_options_header', 10, 0 );
		\WP_Mock::expectActionAdded( 'send_headers', 'send_nosniff_header', 10, 0 );

		$headers = new Headers();
		$headers->send_headers();

		$this->assertConditionsMet();
	}

	/**
	 * Test `remove_vip_headers` method
	 * 
	 * @return void
	 */
	public function test_set_headers(): void {

		$headers_class = new Headers();
		$this->assertEquals(
			array(),
			$headers_class->remove_vip_headers(
				array(
					'X-hacker'     => 'test-value',
					'X-Powered-By' => 'test-value',
				) 
			) 
		);
	}
}
