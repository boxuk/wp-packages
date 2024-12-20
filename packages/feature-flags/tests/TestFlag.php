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
		return new Flag( 'test', 'Test Flag', 'This is a test flag', $dt );
	}

	/**
	 * Test `get_key` method
	 */
	public function test_get_key(): void {
		$this->assertEquals( 'test', $this->get_flag()->get_key() );
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
	 */
	public function test_can_be_published(): void {
		$flag = new Flag( 'test', 'Test Flag', 'This is a test flag', null, 'Default', false, true );
		$this->assertTrue( $flag->can_be_published() );

		$flag = new Flag( 'test', 'Test Flag', 'This is a test flag', null, 'Default', true, false );
		$this->assertFalse( $flag->can_be_published() );
	}

	/**
	 * Test `is_published` method
	 */
	public function test_is_published(): void {
		$flag = $this->get_flag();
		\WP_Mock::userFunction( 'get_option' )
			->with( Flag::FLAG_PUBLISH_OPTION )
			->andReturn( array( $flag->get_key() ) );

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
			->andReturn( array( $this->get_flag()->get_key() ) );

		$this->assertEquals( array( 1, 2, 3 ), $this->get_flag()->get_users() );
	}

	/**
	 * Test `is_enabled_for_user` method
	 */
	public function test_is_enabled_for_user(): void {
		\WP_Mock::userFunction( 'get_user_meta' )->once()
			->with( 1, 'wp_feature_flags_user_flags', true )
			->andReturn( array( $this->get_flag()->get_key() ) );

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
				'key'          => 'test',
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
		\WP_Mock::userFunction( 'update_option' )->once()
			->with( Flag::FLAG_PUBLISH_OPTION, array(), true )
			->andReturn( true );
		\WP_Mock::userFunction( 'update_option' )->once()
			->with( Flag::FLAG_PUBLISH_OPTION, array( 0 => $flag->get_key() ) )
			->andReturn( true );

		$this->assertTrue( $flag->publish() );
	}

	/**
	 * Test `unpublish` method
	 */
	public function test_unpublish(): void {
		$flag = $this->get_flag();
		\WP_Mock::userFunction( 'get_option' )->once()
			->with( Flag::FLAG_PUBLISH_OPTION )
			->andReturn( array( $flag->get_key() ) );
		\WP_Mock::userFunction( 'update_option' )->once()
			->with( Flag::FLAG_PUBLISH_OPTION, array() )
			->andReturn( true );

		$this->assertTrue( $flag->unpublish() );
	}

	/**
	 * Test `publish_for_user` method
	 */
	public function test_publish_for_user(): void {
		$flag = $this->get_flag();
		\WP_Mock::userFunction( 'get_user_meta' )->once()
			->with( 1, 'wp_feature_flags_user_flags', true )
			->andReturn( array() );
		\WP_Mock::userFunction( 'update_user_meta' )->once()
			->with( 1, 'wp_feature_flags_user_flags', array( $flag->get_key() ) )
			->andReturn( true );

		$this->assertTrue( $flag->publish_for_user( 1 ) );
	}

	/**
	 * Test `unpublish_for_user` method
	 */
	public function test_unpublish_for_user(): void {
		$flag = $this->get_flag();
		\WP_Mock::userFunction( 'get_user_meta' )->once()
			->with( 1, 'wp_feature_flags_user_flags', true )
			->andReturn( array( $flag->get_key() ) );
		\WP_Mock::userFunction( 'update_user_meta' )->once()
			->with( 1, 'wp_feature_flags_user_flags', array() )
			->andReturn( true );

		$this->assertTrue( $flag->unpublish_for_user( 1 ) );
	}
}
