<?php
/**
 * Tests for RestrictHTTPRequestMethods class.
 *
 * @package Security
 */

declare( strict_types=1 );
namespace Boxuk\BoxWpEditorTools\Security;

use Mockery;
use WP_Mock\Tools\TestCase;

/**
 * RestrictHTTPRequestMethods test case.
 */
class TestRestictHTTPRequestMethods extends TestCase {

	/**
	 * Test `init` method
	 */
	public function test_init(): void {

		$class_in_test = new RestrictHTTPRequestMethods();

		\WP_Mock::expectActionAdded( 'init', array( $class_in_test, 'block_request_if_not_using_allowed_method' ) );

		$class_in_test->init();

		$this->assertConditionsMet();
	}

	/**
	 * Test `block_request_if_not_using_allowed_method` method
	 *
	 * @param string $method   The request method.
	 * @param bool   $is_cli   Whether the request is from the command line.
	 * @param bool   $expected Whether the request should be blocked.
	 *
	 * @return void
	 *
	 * @dataProvider block_request_if_not_using_allowed_method_provider
	 */
	public function test_block_request_if_not_using_allowed_method( string $method, bool $is_cli, bool $expected ): void {
		$class_in_test = Mockery::mock( RestrictHTTPRequestMethods::class )
			->makePartial();

		$class_in_test->expects( 'is_cli' )
			->once()
			->andReturn( $is_cli );

		if ( ! $is_cli ) { 
			$class_in_test->expects( 'get_method' )
				->once()
				->andReturn( $method );
		} else {
			$class_in_test->expects( 'get_method' )->never();
		}

		if ( $expected ) {
			\WP_Mock::userFunction( 'status_header' )
				->with( 403 );
			\WP_Mock::userFunction( 'wp_die' )
				->with( 'Invalid request method.' );
		} else {
			\WP_Mock::userFunction( 'status_header' )->never();
			\WP_Mock::userFunction( 'wp_die' )->never();
		}

		$class_in_test->block_request_if_not_using_allowed_method();

		$this->assertConditionsMet();
	}

	/**
	 * Data provider for `test_block_request_if_not_using_allowed_method`
	 *
	 * @return array
	 */
	public function block_request_if_not_using_allowed_method_provider(): array {
		return array(
			// Non-CLI Requests should be allowed or blocked dependant on the request method.
			array( 'POST', false, false ),
			array( 'GET', false, false ),
			array( 'PUT', false, false ),
			array( 'PATCH', false, false ),
			array( 'DELETE', false, false ),
			array( 'HEAD', false, false ),
			array( 'OPTIONS', false, false ),
			array( 'PURGE', false, true ),
			array( 'INVALID', false, true ),

			// CLI Requests should always be allowed.
			array( 'POST', true, false ),
			array( 'GET', true, false ),
			array( 'PUT', true, false ),
			array( 'PATCH', true, false ),
			array( 'DELETE', true, false ),
			array( 'HEAD', true, false ),
			array( 'OPTIONS', true, false ),
			array( 'PURGE', true, false ),
			array( 'INVALID', true, false ),
		);
	}
	
	/**
	 * Test `get_method` method
	 * 
	 * @param string $method The request method.
	 * 
	 * @dataProvider get_method_provider
	 * 
	 * @return void
	 */
	public function test_get_method( string $method ): void {
		$class_in_test = new RestrictHTTPRequestMethods();

		$_SERVER['REQUEST_METHOD'] = $method;

		\WP_Mock::userFunction( 'sanitize_text_field' )
			->once()
			->with( $method )
			->andReturn( $method );

		$this->assertEquals( $method, $class_in_test->get_method() );
	}

	/**
	 * Data provider for `test_get_method`
	 *
	 * @return array
	 */
	public function get_method_provider(): array {
		return array(
			array( 'POST' ),
			array( 'GET' ),
			array( 'PUT' ),
			array( 'PATCH' ),
			array( 'DELETE' ),
			array( 'HEAD' ),
			array( 'OPTIONS' ),
			array( 'PURGE' ),
			array( 'INVALID' ),
		);
	}
}
