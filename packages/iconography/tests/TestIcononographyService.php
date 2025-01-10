<?php
/**
 * Test Iconography class
 *
 * @package Boxuk\Iconography
 */

declare( strict_types = 1 );

namespace Boxuk\Iconography;

use Boxuk\Iconography\Model\IconGroup;
use Mockery;
use WP_Mock\Tools\TestCase;

/**
 * TestIcononography class
 */
class TestIcononographyService extends TestCase {

	/**
	 * Test `init`
	 *
	 * @return void
	 */
	public function testInit(): void {
		$class_in_test = new IconographyService( new ConfigurationParser() );
		\WP_Mock::expectActionAdded( 'init', [ $class_in_test, 'register_block' ] );
		\WP_Mock::expectActionAdded( 'wp_enqueue_scripts', [ $class_in_test, 'register_assets' ] );
		\WP_Mock::expectActionAdded( 'wp_footer', [ $class_in_test, 'enqueue_assets' ], 1, 0 );
		\WP_Mock::expectActionAdded( 'enqueue_block_assets', [ $class_in_test, 'register_assets' ], 1, 0 );
		\WP_Mock::expectActionAdded( 'enqueue_block_editor_assets', [ $class_in_test, 'enqueue_editor_scripts' ] );
		\WP_Mock::expectActionAdded( 'enqueue_block_assets', [ $class_in_test, 'enqueue_all_assets' ] );

		$class_in_test->init();
		$this->assertConditionsMet();
	}

	/**
	 * Test Register Block
	 *
	 * @return void
	 */
	public function testRegisterBlock(): void {
		\WP_Mock::userFunction( 'plugin_dir_path' )
			->once()
			->andReturn( 'test/' );

		\WP_Mock::userFunction( 'register_block_type_from_metadata' )
			->once()
			->with( 'test/build/block' );

		$class_in_test = new IconographyService( new ConfigurationParser() );
		$class_in_test->register_block();
		$this->assertConditionsMet();
	}

	/**
	 * Test Register Assets
	 *
	 * @return void
	 */
	public function testRegisterAssets(): void {

		$mock_icon_group = Mockery::mock( IconGroup::class );

		$mock_config = Mockery::mock( ConfigurationParser::class );
		$mock_config->shouldReceive( 'get_configs' )->once()->andReturn(
			[
				$mock_icon_group,
			]
		);

		$class_in_test = new IconographyService( $mock_config );

		$mock_icon_group->expects( 'register_assets' )->once();

		$class_in_test->register_assets();
		$this->assertConditionsMet();
	}

	/**
	 * Test Enqueue Assets
	 *
	 * @return void
	 */
	public function testEnqueueAssets(): void {
		\WP_Mock::userFunction( 'get_the_block_template_html' )
			->once()->andReturn( 'html' );

		$mock_icon_group = Mockery::mock( IconGroup::class );

		$mock_config = Mockery::mock( ConfigurationParser::class );
		$mock_config->shouldReceive( 'get_configs' )->once()->andReturn(
			[
				$mock_icon_group,
			]
		);

		$mock_icon_group->shouldReceive( 'enqueue_assets' )->once()->with( 'html' );

		$class_in_test = new IconographyService( $mock_config );
		$class_in_test->enqueue_assets();
		$this->assertConditionsMet();
	}

	/**
	 * Test Enqueue All Assets
	 *
	 * @return void
	 */
	public function testEnqueueAllAssets(): void {
		$mock_icon_group = Mockery::mock( IconGroup::class );

		$mock_config = Mockery::mock( ConfigurationParser::class );
		$mock_config->shouldReceive( 'get_configs' )->once()->andReturn(
			[
				$mock_icon_group,
			]
		);

		$mock_icon_group->shouldReceive( 'enqueue_assets' )->once();

		$class_in_test = new IconographyService( $mock_config );
		$class_in_test->enqueue_all_assets();
		$this->assertConditionsMet();
	}

	/**
	 * Test Get Icon Groups
	 *
	 * @return void
	 */
	public function testGetIconGroups(): void {
		$mock_icon_group = Mockery::mock( IconGroup::class );

		$mock_config = Mockery::mock( ConfigurationParser::class );
		$mock_config->shouldReceive( 'get_configs' )->once()->andReturn(
			[
				$mock_icon_group,
			]
		);

		$class_in_test = new IconographyService( $mock_config );
		$this->assertEquals( [ $mock_icon_group ], $class_in_test->get_icon_groups() );
	}

	/**
	 * Test Enqueue Editor Scripts
	 *
	 * @return void
	 */
	public function testEnqueueEditorScripts(): void {

		$mock_icon_group = Mockery::mock( IconGroup::class );
		$mock_icon_group->shouldReceive( 'to_json' )
			->once()
			->andReturn(
				[ 'test' ]
			);

		$mock_config = Mockery::mock( ConfigurationParser::class );
		$mock_config->shouldReceive( 'get_configs' )
			->once()
			->andReturn(
				[
					$mock_icon_group,
				]
			);

		$class_in_test = new IconographyService( $mock_config );

		\WP_Mock::userFunction( 'plugin_dir_path' )
			->once()
			->andReturn( __DIR__ . '/fixtures/' );

		\WP_Mock::userFunction( 'plugins_url' )
			->once()
			->andReturn( 'https://example.com/assets/build/index.js' );

		\WP_Mock::userFunction( 'wp_enqueue_script' )
			->once()
			->with(
				'iconography',
				'https://example.com/assets/build/index.js',
				[],
				'1',
				true
			);

		\WP_Mock::userFunction( 'wp_localize_script' )
			->once()
			->with(
				'iconography',
				'boxIconography',
				[
					'iconGroups' => [ [ 'test' ] ],
				]
			);

		$class_in_test->enqueue_editor_scripts();
		$this->assertConditionsMet();
	}
}
