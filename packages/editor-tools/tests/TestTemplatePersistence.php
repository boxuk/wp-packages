<?php
/**
 * Test TemplatePersistence class
 * 
 * @package BoxUK\EditorTools
 */

declare( strict_types = 1 );

namespace Boxuk\BoxWpEditorTools;

use Mockery;
use WP_Mock\Tools\TestCase;

/**
 * TestTemplatePersistence class
 */
class TestTemplatePersistence extends TestCase {

	/**
	 * Class in test
	 * 
	 * @var TemplatePersistence
	 */
	private $class_in_test; 

	/**
	 * Setup
	 * 
	 * @return void
	 */
	public function setUp(): void { 
		$this->class_in_test = new TemplatePersistence();
		parent::setUp();
	}

	/**
	 * Test init
	 * 
	 * @return void
	 */
	public function test_init(): void { 
		\WP_Mock::expectActionAdded( 'save_post', [ $this->class_in_test, 'persist_template' ], 10, 2 );
		$this->class_in_test->init();
		$this->assertConditionsMet();
	}

	/**
	 * Test init with disabled filter
	 * 
	 * @return void
	 */
	public function test_init_with_disabled_filter(): void {
		\WP_Mock::userFunction( 'wp_get_environment_type' )
			->once()
			->andReturn( 'production' );
		\WP_Mock::onFilter( 'boxuk_disable_template_persistence' )
			->with( true )
			->reply( true );

		$this->class_in_test->persist_template( 0, $this->getMockPost( 'wp_template' ) );
		$this->assertConditionsMet();
	}

	/**
	 * Test persist template
	 * 
	 * @param \WP_Post     $post                  The post to work with.
	 * @param bool         $expect_template_write If we should expect to write a template.
	 * @param string|false $template_path         The expected template path.
	 * 
	 * @dataProvider persistTemplateDataProvider
	 * 
	 * @return void
	 */
	public function test_persist_template(
		\WP_Post $post, 
		bool $expect_template_write,
		string|bool $template_path
	) { 
		\WP_Mock::userFunction( 'wp_get_environment_type' )
			->once()
			->andReturn( 'local' );
		\WP_Mock::onFilter( 'boxuk_disable_template_persistence' )
			->with( false )
			->reply( false );
			
		if ( $expect_template_write ) {
			\WP_Mock::userFunction( 'get_stylesheet_directory' )
				->once()
				->andReturn( __DIR__ );
			\WP_Mock::userFunction( 'wp_delete_post' )
				->once()
				->with( $post->ID, true );
		}

		$this->class_in_test->persist_template( 0, $post );

		if ( $template_path ) {
			$this->assertFileExists( __DIR__ . $template_path );
			unlink( __DIR__ . $template_path ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_unlink -- this is a test-only file
			rmdir( __DIR__ . dirname( $template_path ) ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.directory_rmdir -- this is a test-only directory
		}

		$this->assertConditionsMet();
	}

	/**
	 * Data provider
	 * 
	 * @return array
	 */
	protected function persistTemplateDataProvider(): array { 
		return [
			'invalid_post_type' => [
				'post'                  => $this->getMockPost( 'post' ),
				'expect_template_write' => false,
				'template_path'         => false,
			],
			'template'          => [
				'post'                  => $this->getMockPost( 'wp_template' ),
				'expect_template_write' => true,
				'template_path'         => '/templates/test.html',
			],
			'template_part'     => [
				'post'                  => $this->getMockPost( 'wp_template_part' ),
				'expect_template_write' => true,
				'template_path'         => '/parts/test.html',
			],
		];
	}

	/**
	 * Get Mock Post
	 * 
	 * @param string $post_type The post-type.
	 * 
	 * @return \WP_Post
	 */
	private function getMockPost( string $post_type ): \WP_Post { 
		/**
		 * Post (Mocked)
		 * 
		 * @var \WP_Post $post Post (Mocked) 
		 */
		$post               = Mockery::mock( 'WP_Post' );
		$post->ID           = 0; 
		$post->post_type    = $post_type; 
		$post->post_name    = 'test';
		$post->post_content = 'test';

		return $post;
	}
}
