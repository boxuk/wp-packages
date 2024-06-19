<?php
/**
 * Tests for Author Enumaration class.
 *
 * @package Security
 */

declare( strict_types=1 );
namespace Boxuk\BoxWpEditorTools\Security;

use Mockery;
use WP_Mock\Tools\TestCase;

/**
 * Author Enumartion test case.
 */
class TestAuthorEnumeration extends TestCase {

	/**
	 * Test `init` method
	 */
	public function test_init(): void {

		$author_enumeration = new AuthorEnumeration();

		\WP_Mock::expectFilterAdded( 'redirect_canonical', array( $author_enumeration, 'prevent_author_enum' ) );
		\WP_Mock::expectFilterAdded( 'rest_endpoints', array( $author_enumeration, 'handle_rest_endpoints' ) );

		$author_enumeration->init();

		$this->assertConditionsMet();
	}

	/**
	 * Test `prevent_author_enum` method
	 *
	 * @param string|bool $query_var      The author query var value.
	 * @param bool        $expected       Whether the author enumeration should be disabled.
	 * @param bool        $filter_enabled Whether the filter is enabled.
	 *
	 * @return void
	 *
	 * @dataProvider disable_author_enumeration_provider
	 */
	public function test_prevent_author_enum( bool|string $query_var, bool $expected, bool $filter_enabled ): void {

		\WP_Mock::onFilter( 'boxuk_prevent_author_enum' )->with( true )->reply( $filter_enabled );

		\WP_Mock::userFunction( 'get_query_var' )
			->with( 'author', false )
			->andReturn( $query_var );

		$author_enumeration = new AuthorEnumeration();

		if ( ! $expected ) {
			\WP_Mock::expectFilterNotAdded( 'wp_title', array( $author_enumeration, 'get_404_title' ), PHP_INT_MAX );
			$this->assertEquals( 'test', $author_enumeration->prevent_author_enum( 'test' ) );
		} else {
			global $wp_query;
			$wp_query = Mockery::mock( 'WP_Query' ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- Mocking WP_Query
			$wp_query->expects( 'set_404' )->once();
			\WP_Mock::expectFilterAdded( 'wp_title', array( $author_enumeration, 'get_404_title' ), PHP_INT_MAX );

			\WP_Mock::userFunction( 'status_header' )
				->with( 404 )
				->once();
			\WP_Mock::userFunction( 'nocache_headers' )
				->once();

			$this->assertNull( $author_enumeration->prevent_author_enum( 'test' ) );
		}
	}

	/**
	 * Data provider for `test_disable_author_enumeration`
	 *
	 * @return array
	 */
	public function disable_author_enumeration_provider(): array {
		return array(
			array(
				'author',
				true,
				true,
			),
			array(
				false,
				false,
				true,
			),
			array(
				'author',
				false,
				false,
			),
			array(
				false,
				false,
				false,
			),
		);
	}

	/**
	 * Test `get_404_title` method
	 *
	 * @return void
	 */
	public function test_get_404_title(): void {
		$author_enumeration = new AuthorEnumeration();

		$this->assertEquals( '404: Not Found', $author_enumeration->get_404_title() );
	}

	/**
	 * Test `handle_rest_endpoints`
	 *
	 * @param bool $is_authorised_user If the current user has permissions.
	 * @param bool $filter_enabled      If the filter is enabled.
	 *
	 * @return void
	 *
	 * @dataProvider rest_endpoint_data_provider
	 */
	public function test_handle_rest_endpoints( bool $is_authorised_user, bool $filter_enabled ) {

		\WP_Mock::onFilter( 'boxuk_prevent_author_rest_endpoint' )
			->with( true )
			->reply( $filter_enabled );

		$endpoints = array(
			'/wp/v2/users'               => true,
			'/wp/v2/users/(?P<id>[\d]+)' => true,
		);

		\WP_Mock::userFunction( 'current_user_can' )
			->times( (int) $filter_enabled )
			->with( 'edit_posts' )
			->andReturn( $is_authorised_user );

		$author_enumeration = new AuthorEnumeration();

		$expected = $is_authorised_user ? $endpoints : array();

		$this->assertEquals( $expected, $author_enumeration->handle_rest_endpoints( $endpoints ) );
	}

	/**
	 * Data provider for `test_handle_rest_endpoints`
	 *
	 * @return array
	 */
	public function rest_endpoint_data_provider(): array {
		return array(
			array( true, true ),
			array( false, true ),
			array( true, false ),
		);
	}
}
