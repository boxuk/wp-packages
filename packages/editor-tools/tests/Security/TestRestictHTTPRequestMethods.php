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

		$_SERVER['REQUEST_METHOD'] = $method;

		\WP_Mock::userFunction( 'sanitize_text_field' )
			->with( $method )
			->andReturn( $method );

		$class_in_test = Mockery::mock( RestrictHTTPRequestMethods::class )
			->makePartial();

		$class_in_test->expects( 'is_cli' )->once()->andReturn( $is_cli );

		if ( $expected ) { 
			\WP_Mock::userFunction( 'status_header' )
				->with( 403 );
			\WP_Mock::userFunction( 'wp_die' )
				->with( 'Invalid request method.' );
		} else { 
			\WP_Mock::userFunction( 'wp_die' )->never();
		}

		$class_in_test->block_request_if_not_using_allowed_method();

		unset( $_SERVER['REQUEST_METHOD'] );

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
}
