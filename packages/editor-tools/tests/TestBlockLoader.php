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
}
