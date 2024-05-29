<?php
/**
 * Test Iconography class
 * 
 * @package Boxuk\Iconography
 */

declare( strict_types = 1 );

namespace Boxuk\Iconography;

use WP_Mock\Tools\TestCase;

require_once __DIR__ . '/../iconography.php';

/**
 * TestIcononography class
 */
class TestIcononography extends TestCase {

	/**
	 * Test `init`
	 * 
	 * @return void
	 */
	public function testInit(): void {
		$class_in_test = new Iconography();
		\WP_Mock::expectActionAdded( 'wp_enqueue_scripts', array( $class_in_test, 'register_scripts' ) );
		\WP_Mock::expectActionAdded( 'enqueue_block_assets', array( $class_in_test, 'register_scripts' ) );
		\WP_Mock::expectActionAdded( 'enqueue_block_assets', array( $class_in_test, 'enqueue_all_iconography' ) );
		\WP_Mock::expectActionAdded( 'wp_footer', array( $class_in_test, 'maybe_add_scripts' ), 1, 0 );

		$class_in_test->init();
		$this->assertConditionsMet();
	}

	/**
	 * Test `register_scripts`
	 * 
	 * @return void
	 */
	public function testRegisterScripts(): void {
		$class_in_test = new Iconography();
		\WP_Mock::userFunction( 'wp_register_style' )
			->times( 3 )
			->with( \WP_Mock\Functions::type( 'string' ), \WP_Mock\Functions::type( 'string' ) )
			->andReturn( true );
		
		\WP_Mock::userFunction( 'wp_add_inline_style' )
			->times( 3 )
			->with( \WP_Mock\Functions::type( 'string' ), \WP_Mock\Functions::type( 'string' ) )
			->andReturn( true );

		$class_in_test->register_scripts();
		$this->assertConditionsMet();
	}

	/**
	 * Test `enqueue_all_iconography`
	 * 
	 * @return void
	 */
	public function testEnqueueAllIconography(): void {
		$class_in_test = new Iconography();
		\WP_Mock::userFunction( 'wp_enqueue_style' )
			->times( 3 )
			->with( \WP_Mock\Functions::type( 'string' ) )
			->andReturn( true );
		
		\WP_Mock::userFunction( 'plugins_url' )
			->once()
			->andReturn( 'http://example.com/build/index.js' );
	
		\WP_Mock::userFunction( 'wp_enqueue_script' )
			->once()
			->with( 
				'iconography', 
				'http://example.com/build/index.js', 
				\WP_Mock\Functions::type( 'array' ), 
				\WP_Mock\Functions::type( 'string' ), 
				true
			);
			
		$asset_path = __DIR__ . '/../build/index.asset.php';
		if ( ! file_exists( $asset_path ) ) { 
			mkdir( __DIR__ . '/../build' ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.directory_mkdir
			file_put_contents( $asset_path, '<?php return [ "dependencies" => [ "wp-element" ], "version" => "1.0.0" ];' ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_file_put_contents -- this is in a test env only.
		}

		$class_in_test->enqueue_all_iconography();
		$this->assertConditionsMet();
	}

	/**
	 * Test `maybe_add_scripts`
	 * 
	 * @param string   $content          The content to test.
	 * @param string[] $expected_scripts The expected scripts to be added.
	 * 
	 * @dataProvider maybeAddScriptsProvider
	 * 
	 * @return void
	 */
	public function testMaybeAddScripts( string $content, array $expected_scripts ): void {

		\WP_Mock::userFunction( 'get_the_block_template_html' )
			->once()
			->andReturn( $content );

		foreach ( $expected_scripts as $handle ) {
			\WP_Mock::userFunction( 'wp_enqueue_style' )
				->once()
				->with( $handle );
		}

		if ( empty( $expected_scripts ) ) {
			\WP_Mock::userFunction( 'wp_enqueue_style' )
				->never();
		}

		$class_in_test = new Iconography();
		$class_in_test->maybe_add_scripts();
		$this->assertConditionsMet();
	}

	/**
	 * Data provider for `testMaybeAddScripts`
	 * 
	 * @return array<string, array{0:string,1:string[]}>
	 */
	public function maybeAddScriptsProvider(): array {
		return array( 
			'empty'                      => array( '', array() ),
			'no scripts'                 => array( '<p>hello world</p>', array() ),
			'one script'                 => array( '<p>hello world</p><i class="material-symbols-sharp"></i>', array( 'material-symbols-sharp' ) ),
			'two icons, one script'      => array( '<p>hello world</p><i class="material-symbols-sharp"></i><i class="material-symbols-sharp"></i>', array( 'material-symbols-sharp' ) ),
			'two icons, two scripts'     => array( '<p>hello world</p><i class="material-symbols-sharp"></i><i class="material-symbols-rounded"></i>', array( 'material-symbols-sharp', 'material-symbols-rounded' ) ),
			'three icons, two scripts'   => array( '<p>hello world</p><i class="material-symbols-sharp"></i><i class="material-symbols-rounded"></i><i class="material-symbols-sharp"></i>', array( 'material-symbols-sharp', 'material-symbols-rounded' ) ),
			'three icons, three scripts' => array( '<p>hello world</p><i class="material-symbols-sharp"></i><i class="material-symbols-rounded"></i><i class="material-symbols-outlined"></i>', array( 'material-symbols-sharp', 'material-symbols-rounded', 'material-symbols-outlined' ) ),
		);
	}
}
