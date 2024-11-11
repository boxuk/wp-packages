<?php
/**
 * Test Flag class
 *
 * @package BoxUk\WpFeatureFlags
 */

declare( strict_types = 1 );

namespace BoxUk\WpFeatureFlags;

use DateTime;
use Mockery;
use WP_Mock\Tools\TestCase;

/**
 * Flag class
 */
class TestFlag extends TestCase {

	/**
	 * Get Flag
	 * 
	 * @param DateTime $dt Date time.
	 * 
	 * @return Flag
	 */
	private function get_flag( ?DateTime $dt = null ): Flag {
		return new Flag( 'test-flag', 'Test Flag', 'This is a test flag', $dt );
	}

	/**
	 * Test `get_key` method
	 */
	public function test_get_key(): void {
		$this->assertEquals( 'test-flag', $this->get_flag()->get_key() );
	}

	/**
	 * Test `get_name` method
	 */
	public function test_get_name(): void {
		$this->assertEquals( 'Test Flag', $this->get_flag()->get_name() );
	}

	/**
	 * Test `get_description` method
	 */
	public function test_get_description(): void {
		$this->assertEquals( 'This is a test flag', $this->get_flag()->get_description() );
	}

	/**
	 * Test `get_created` method
	 */
	public function test_get_created(): void {
		$dt = new DateTime();
		$this->assertEquals( $dt, $this->get_flag( $dt )->get_created() );
	}

	/**
	 * Test `get_group` method
	 */
	public function test_get_group(): void {
		$this->assertEquals( 'Default', $this->get_flag()->get_group() );
	}

	/**
	 * Test `is_enforced` method
	 */
	public function test_is_enforced(): void {
		$this->assertFalse( $this->get_flag()->is_enforced() );
	}

	/**
	 * Test `is_stable` method
	 */
	public function test_is_stable(): void {
		$this->assertTrue( $this->get_flag()->is_stable() );
	}

	/**
	 * Test `can_be_published` method
	 * 
	 * @param bool $stable Is stable.
	 * @param bool $enforced Is enforced.
	 * @param bool $expected Expected result.
	 * 
	 * @dataProvider data_provider_test_can_be_published
	 */
	public function test_can_be_published( bool $stable, bool $enforced, bool $expected ): void {
		$flag = new Flag( 'test-flag', 'Test Flag', 'This is a test flag', null, 'Default', $enforced, $stable );
		$this->assertEquals( $expected, $flag->can_be_published() );
	}

	/**
	 * Data provider for `test_can_be_published` method
	 * 
	 * @return array<array<bool>>
	 */
	public function data_provider_test_can_be_published(): array {
		return array(
			array( true, false, true ),
			array( false, true, false ),
			array( true, true, false ),
			array( false, false, false ),
		);
	}

	/**
	 * Test `is_published` method
	 */
	public function test_is_published(): void {
		\WP_Mock::userFunction( 'get_option' )
			->with( Flag::FLAG_PUBLISH_OPTION )
			->andReturn( array() );

		$this->assertFalse( $this->get_flag()->is_published() );
	}

	/**
	 * Test `is_published` method
	 */
	public function test_is_published_true(): void {
		$flag = $this->get_flag();
		\WP_Mock::userFunction( 'get_option' )
			->with( Flag::FLAG_PUBLISH_OPTION )
			->andReturn( array( $flag ) );

		$this->assertTrue( $flag->is_published() );
	}

	/**
	 * Test `get_users` method
	 */
	public function test_get_users(): void {
		\WP_Mock::userFunction( 'get_users' )
			->once()
			->with( array( 'fields' => 'ID' ) )
			->andReturn( array( 1, 2, 3 ) );

		\WP_Mock::userFunction( 'absint' )->andReturnArg( 0 );

		\WP_Mock::userFunction( 'get_user_meta' )
			->with( \Mockery::anyOf( 1, 2, 3 ), 'wp_feature_flags_user_flags', true )
			->andReturn( array( $this->get_flag() ) );
		


		$this->assertEquals( array(), $this->get_flag()->get_users() );
	}

	/**
	 * Test `is_enabled_for_user` method
	 */
	public function test_is_enabled_for_user(): void {
		\WP_Mock::userFunction( 'get_user_meta' )
			->with( 1, 'wp_feature_flags_user_flags', true )
			->andReturn( array( $this->get_flag() ) );

		$this->assertTrue( $this->get_flag()->is_enabled_for_user( 1 ) );
	}

	/**
	 * Test `is_enabled` method
	 */
	public function test_is_enabled(): void {
		$flag = $this->get_flag();
		\WP_Mock::userFunction( 'get_option' )
			->with( Flag::FLAG_PUBLISH_OPTION )
			->andReturn( array() );

		\WP_Mock::userFunction( 'get_user_meta' )
			->with( 1, 'wp_feature_flags_user_flags', true )
			->andReturn( array() );

		$this->assertFalse( $flag->is_enabled( 1 ) );
	}

	/**
	 * Test `jsonSerialize` method
	 */
	public function test_jsonSerialize(): void {
		\WP_Mock::userFunction( 'update_option' );
		\WP_Mock::userFunction( 'get_users' )->andReturn( array() );

		$flag = $this->get_flag();
		$this->assertEquals(
			array(
				'key'          => 'test-flag',
				'name'         => 'Test Flag',
				'description'  => 'This is a test flag',
				'created'      => null,
				'group'        => 'Default',
				'enforced'     => false,
				'stable'       => true,
				'is_published' => false,
				'users'        => array(),
			),
			$flag->jsonSerialize()
		);
	}

	/**
	 * Test `publish` method
	 */
	public function test_publish(): void {
		$flag = $this->get_flag();
		\WP_Mock::userFunction( 'update_option' )
			->with( Flag::FLAG_PUBLISH_OPTION, array(), true )
			->andReturn( true );
		\WP_Mock::userFunction( 'update_option' )
			->with( Flag::FLAG_PUBLISH_OPTION, array( $flag->get_key() ), true )
			->andReturn( true );

		$this->assertTrue( $flag->publish() );
	}
}
