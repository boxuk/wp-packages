<?php
/**
 * Test BlockLoader class
 * 
 * @package Boxuk\BoxWpEditorTools
 */

declare( strict_types = 1 );

namespace Boxuk\BoxWpEditorTools;

use WP_Mock\Tools\TestCase;

/**
 * BlockLoader class
 */
class TestBlockLoader extends TestCase {

	/**
	 * Test `init` method
	 * 
	 * @return void
	 */
	public function testInit(): void {
		$loader = new BlockLoader();
		\WP_Mock::expectActionAdded( 'init', array( $loader, 'register_blocks' ) );
		\WP_Mock::expectFilterAdded( 'block_type_metadata', array( $loader, 'enforce_versioning' ) );
		$loader->init();
		$this->assertConditionsMet();
	}

	/**
	 * Test `register_blocks` method
	 * 
	 * @return void
	 */
	public function testRegisterBlocks(): void {

		$fixture_dir = __DIR__ . '/fixtures/BlockLoader';

		\WP_Mock::userFunction( 'get_template_directory' )
			->once()
			->andReturn( $fixture_dir );
		
		\WP_Mock::userFunction( 'register_block_type' )
			->once()
			->with( $fixture_dir . '/build/blocks/test-block', array() );

		$loader = new BlockLoader();
		$loader->register_blocks();
		$this->assertConditionsMet();
	}

	/**
	 * Test `register_blocks` with missing file
	 * 
	 * @return void
	 */
	public function testRegisterBlocksMissingFile(): void {
		\WP_Mock::userFunction( 'get_template_directory' )
			->once()
			->andReturn( '' );

		\WP_Mock::userFunction( 'register_block_type' )
			->never();

		$loader = new BlockLoader();
		$loader->register_blocks();
		$this->assertConditionsMet();
	}

	/**
	 * Test `enforce_versioning` method
	 * 
	 * @dataProvider metadataProvider
	 * 
	 * @param array $metadata Block metadata.
	 * @param array $expected Expected metadata.
	 * 
	 * @return void
	 */
	public function testEnforceVersioning( array $metadata, array $expected ): void {
		$loader = new BlockLoader();
		
		\WP_Mock::userFunction( 
			'remove_block_asset_path_prefix', 
			array(
				'return' => function ( $asset_handle_or_path ) {
					$path_prefix = 'file:';
					if ( ! str_starts_with( $asset_handle_or_path, $path_prefix ) ) {
						return $asset_handle_or_path;
					}
					$path = substr(
						$asset_handle_or_path,
						strlen( $path_prefix )
					);
					if ( str_starts_with( $path, './' ) ) {
						$path = substr( $path, 2 );
					}
					return $path;
				},
			)
		);

		$this->assertEquals( $expected, $loader->enforce_versioning( $metadata ) );
	}

	/**
	 * Data provider for `testEnforceVersioning`
	 * 
	 * @return array
	 */
	public function metadataProvider(): array {
		return array(
			'empty'              => array( array(), array() ),
			'Version, no File'   => array( array( 'version' => '1.0.0' ), array( 'version' => '1.0.0' ) ),
			'Editor, no Version' => array( array( 'editorScript' => 'test.js' ), array( 'editorScript' => 'test.js' ) ),
			'Version and File'   => array(
				array(
					'version' => '1.0.0',
					'file'    => __DIR__,
				),
				array(
					'version' => '1.0.0',
					'file'    => __DIR__,
				),
			),
			'Editor, no asset'   => array(
				array(
					'editorScript' => 'test.js',
					'file'         => __DIR__,
				),
				array(
					'editorScript' => 'test.js',
					'file'         => __DIR__,
				),
			),
			'Real File'          => array(
				array(
					'editorScript' => 'file:./test.js',
					'file'         => __DIR__ . '/fixtures/BlockLoader/versioning-block/test.json',
				),
				array(
					'editorScript' => 'file:./test.js',
					'file'         => __DIR__ . '/fixtures/BlockLoader/versioning-block/test.json',
					'version'      => 'test',
				),
			),
		);
	}
}
