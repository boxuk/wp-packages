<?php
/**
 * Test PostTypes class
 * 
 * @package Boxuk\BoxWpEditorTools
 */

declare( strict_types = 1 );

use WP_Mock\Tools\TestCase;

/**
 * TestPostTypes class
 */
class TestPostTypes extends TestCase {

	/**
	 * Test init
	 * 
	 * @return void
	 */
	public function testInit(): void {
		$post_types = new \Boxuk\BoxWpEditorTools\PostTypes();
		\WP_Mock::expectActionAdded( 'init', array( $post_types, 'register_post_types' ) );
		$post_types->init();
		$this->assertConditionsMet();
	}

	/**
	 * Test Register Post Types
	 * 
	 * @return void
	 */
	public function testRegisterPostTypes(): void {

		$expected_parsed_json = array(
			'labels'     => array(
				'name'          => 'TestName',
				'singular_name' => 'TestName',
			),
			'menu_icon'  => 'dashicons-admin-post',
			'taxonomies' => array( 'category', 'post_tag' ),
		);

		$expected_defaults = array(
			'show_in_rest' => true,
			'public'       => true,
			'template'     => array(),
		);

		$expected_parsed_json_with_defaults = array_merge(
			$expected_parsed_json,
			$expected_defaults
		);

		\WP_Mock::userFunction( 'get_template_directory' )
			->once()->andReturn( __DIR__ . '/fixtures/PostTypes' );
		
		\WP_Mock::userFunction( 'wp_parse_args' )
			->twice()
			->with( $expected_parsed_json, $expected_defaults )
			->andReturn( $expected_parsed_json_with_defaults );

		\WP_Mock::userFunction( 'register_post_type' )
			->once()
			->with( 'foo', $expected_parsed_json_with_defaults );

		\WP_Mock::userFunction( 'register_post_type' )
			->once()
			->with( 'bar', $expected_parsed_json_with_defaults );

		\WP_Mock::userFunction( 'register_taxonomy' )
			->once()
			->with( 
				'example_category', 
				'post', 
				array(
					'labels'  => array(
						'name'          => 'TestName',
						'singular_name' => 'TestName',
					),
					'public'  => true,
					'rewrite' => array( 'slug' => 'example-category' ),
				) 
			);

		// Short-circuit the test here as we'll test the blocks-to-template methods separately.
		\WP_Mock::userFunction( 'is_admin' )
			->twice()
			->andReturn( false );

		$post_types = new \Boxuk\BoxWpEditorTools\PostTypes();
		$post_types->register_post_types();
		$this->assertConditionsMet();
	}

	/**
	 * Test testRegisterPostTypes with no file present
	 * 
	 * @return void
	 */
	public function testRegisterPostTypesWithNoFilePresent(): void {
		\WP_Mock::userFunction( 'get_template_directory' )
			->once()->andReturn( __DIR__ . '/not-fixtures' );

		$post_types = new \Boxuk\BoxWpEditorTools\PostTypes();
		$post_types->register_post_types();
		$this->assertConditionsMet();
	}

	/**
	 * Test testRegisterPostTypes with invalid json
	 * 
	 * @return void
	 */
	public function testRegisterPostTypesWithInvalidJson(): void {
		\WP_Mock::userFunction( 'get_template_directory' )
			->once()->andReturn( __DIR__ . '/fixtures/PostTypes/invalid' );

		$post_types = new \Boxuk\BoxWpEditorTools\PostTypes();
		$post_types->register_post_types();
		$this->assertConditionsMet();
	}

	/**
	 * Test Get Blocks From File
	 * 
	 * @return void
	 */
	public function testGetBlocksFromFile(): void {
		\WP_Mock::userFunction( 'is_admin' )
			->once()
			->andReturn( true );

		\WP_Mock::userFunction( 'get_template_directory' )
			->once()
			->andReturn( __DIR__ . '/fixtures/PostTypes' );

		\WP_Mock::userFunction( 'parse_blocks' )
			->once()
			->with( file_get_contents( __DIR__ . '/fixtures/PostTypes/post-type-templates/bar.html' ) )
			->andReturn( array() );

		$post_types = new \Boxuk\BoxWpEditorTools\PostTypes();
		$post_types->get_blocks_from_file( 'bar' );

		$this->assertConditionsMet();
	}

	/**
	 * Test Gets Blocks from File with no template file
	 * 
	 * @return void
	 */
	public function testGetBlocksFromFileWithNoTemplateFile(): void {
		\WP_Mock::userFunction( 'is_admin' )
			->once()
			->andReturn( true );

		\WP_Mock::userFunction( 'get_template_directory' )
			->once()
			->andReturn( __DIR__ . '/fixtures' );

		\WP_Mock::userFunction( 'parse_blocks' )
			->never();

		$post_types = new \Boxuk\BoxWpEditorTools\PostTypes();
		$post_types->get_blocks_from_file( 'foo' );

		$this->assertConditionsMet();
	}

	/**
	 * Test blocks_to_template
	 * 
	 * @dataProvider blocks_to_template_provider
	 * 
	 * @param array  $blocks Blocks.
	 * @param ?array $expected Expected.
	 * 
	 * @return void
	 */
	public function testBlocksToTemplate( array $blocks, ?array $expected ): void {
		$post_types = new \Boxuk\BoxWpEditorTools\PostTypes();
		$this->assertEquals( $expected, $post_types->blocks_to_template( $blocks ) );
	}

	/**
	 * Blocks to template provider
	 * 
	 * @return array
	 * 
	 * phpcs:ignore NeutronStandard.Functions.LongFunction.LongFunction -- This is a data provider
	 */
	public function blocks_to_template_provider(): array { 
		return array(
			'empty'                       => array(
				'blocks'   => array(),
				'expected' => array(),
			),
			'invalid'                     => array(
				'blocks'   => array( array( 'foo' => 'bar' ) ),
				'expected' => array(),
			),
			'valid'                       => array(
				'blocks'   => array( array( 'blockName' => 'core/paragraph' ) ),
				'expected' => array( array( 'core/paragraph', array(), array() ) ),
			),
			'valid-with-attrs'            => array(
				'blocks'   => array(
					array(
						'blockName' => 'core/paragraph',
						'attrs'     => array( 'align' => 'left' ),
					),
				),
				'expected' => array( array( 'core/paragraph', array( 'align' => 'left' ), array() ) ),
			),
			'valid-with-children'         => array(
				'blocks'   => array(
					array(
						'blockName'   => 'core/paragraph',
						'innerBlocks' => array( array( 'blockName' => 'core/heading' ) ),
					),
				),
				'expected' => array( array( 'core/paragraph', array(), array( array( 'core/heading', array(), array() ) ) ) ),
			),
			'valid-with-invalid-children' => array(
				'blocks'   => array( 
					array(
						'blockName'   => 'core/paragraph',
						'innerBlocks' => array( array( 'foo' => 'bar' ) ),
					),
				),
				'expected' => array( array( 'core/paragraph', array(), array() ) ),
			),
		);
	}
}
