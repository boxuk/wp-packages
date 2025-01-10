<?php
/**
 * Test ConfigurationParser class
 *
 * @package Boxuk\Iconography
 */

declare( strict_types = 1 );

namespace Boxuk\Iconography;

use Mockery;
use WP_Mock\Tools\TestCase;

/**
 * TestConfigurationParser class
 */
class TestConfigurationParser extends TestCase {

	/**
	 * Test Get Configs
	 */
	public function testGetConfigs(): void {
		\WP_Mock::userFunction( 'get_stylesheet_directory' )
			->once()
			->andReturn( __DIR__ . '/fixtures' );

		\WP_Mock::expectFilter(
			'boxuk_iconography_files',
			[
				__DIR__ . '/fixtures/icons/invalid.config.json',
				__DIR__ . '/fixtures/icons/not-json.config.json',
				__DIR__ . '/fixtures/icons/valid.config.json',
			]
		);

		$class_in_test = Mockery::mock( ConfigurationParser::class )->makePartial();

		$class_in_test->shouldReceive( 'parse_config' )->times( 3 );
		$configs = $class_in_test->get_configs();

		$this->assertIsArray( $configs );
	}

	/**
	 * Test Parse config file
	 *
	 * @dataProvider parseConfigDataProvider
	 *
	 * @param string           $file        File path.
	 * @param bool             $is_wp_error Is WP_Error.
	 * @param array<IconGroup> $expected    Expected result.
	 */
	public function testParseConfig( string $file, bool $is_wp_error, array $expected ): void {

		$class_in_test = new ConfigurationParser();

		\WP_Mock::userFunction( 'plugin_dir_path' )
			->andReturn( __DIR__ . '/fixtures/' );

		\WP_Mock::userFunction( 'is_wp_error' )
			->once()
			->andReturnUsing(
				function ( $value ) {
					return is_a( $value, 'WP_Error' );
				}
			);

		if ( $is_wp_error ) {
			\WP_Mock::userFunction( 'wp_die' )->once();
			$class_in_test->parse_config( $file );
			$this->assertConditionsMet();
			return;
		} else {
			\WP_Mock::userFunction( 'wp_die' )->never();
		}


		$class_in_test->parse_config( $file );
		$this->assertEquals( $expected, $class_in_test->get_configs() );
	}

	/**
	 * Data provider for testParseConfig
	 *
	 * @return array
	 */
	public function parseConfigDataProvider(): array {
		return [
			[
				__DIR__ . '/fixtures/icons/invalid.config.json',
				true,
				[],
			],
			[
				__DIR__ . '/fixtures/icons/not-json.config.json',
				true,
				[],
			],
			[
				__DIR__ . '/fixtures/icons/valid.config.json',
				false,
				[
					new Model\IconGroup(
						'Test',
						'test/example',
						'span',
						'test',
						'http://example.com',
						'.test { color: red; }',
						[
							new Model\Icon( 'TestOne', 'TestOne' ),
							new Model\Icon( 'TestTwo', 'TestTwo' ),
						]
					),
				],
			],
		];
	}
}
