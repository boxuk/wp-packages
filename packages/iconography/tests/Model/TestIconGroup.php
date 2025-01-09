<?php
/**
 * Test IconGroup class
 *
 * @package Boxuk\Iconography
 */

declare( strict_types = 1 );

namespace Boxuk\Iconography;

use WP_Mock\Tools\TestCase;

/**
 * TestIconGroup class
 */
class TestIconGroup extends TestCase {

	/**
	 * Test Get Title
	 */
	public function testGetTitle(): void {
		$icon_group = new Model\IconGroup(
			'title',
			'name',
			'tag_name',
			'class_name',
			'url',
			'additional_css',
			[]
		);

		\WP_Mock::expectFilter( 'boxuk_iconography_configuration_title', 'title' );
		$this->assertSame( 'title', $icon_group->get_title() );
	}

	/**
	 * Test Get Name
	 */
	public function testGetName(): void {
		$icon_group = new Model\IconGroup(
			'title',
			'name',
			'tag_name',
			'class_name',
			'url',
			'additional_css',
			[]
		);

		\WP_Mock::expectFilter( 'boxuk_iconography_configuration_name', 'name' );
		$this->assertSame( 'name', $icon_group->get_name() );
	}

	/**
	 * Test Get Tag Name
	 */
	public function testGetTagName(): void {
		$icon_group = new Model\IconGroup(
			'title',
			'name',
			'tag_name',
			'class_name',
			'url',
			'additional_css',
			[]
		);

		\WP_Mock::expectFilter( 'boxuk_iconography_configuration_tag_name', 'tag_name' );

		$this->assertSame( 'tag_name', $icon_group->get_tag_name() );
	}

	/**
	 * Test Get Class Name
	 */
	public function testGetClassName(): void {
		$icon_group = new Model\IconGroup(
			'title',
			'name',
			'tag_name',
			'class_name',
			'url',
			'additional_css',
			[]
		);

		\WP_Mock::expectFilter( 'boxuk_iconography_configuration_class_name', 'class_name' );

		$this->assertSame( 'class_name', $icon_group->get_class_name() );
	}

	/**
	 * Test Get Icons
	 */
	public function testGetIcons(): void {
		$icon_group = new Model\IconGroup(
			'title',
			'name',
			'tag_name',
			'class_name',
			'url',
			'additional_css',
			[]
		);

		\WP_Mock::expectFilter( 'boxuk_iconography_configuration_icons', [] );

		$this->assertSame( [], $icon_group->get_icons() );
	}

	/**
	 * Test Register Assets
	 */
	public function testRegisterAssets(): void {
		$icon_group = new Model\IconGroup(
			'title',
			'name',
			'tag_name',
			'class_name',
			'url',
			'additional_css',
			[]
		);

		\WP_Mock::userFunction( 'wp_register_style' )
			->once()
			->with( 'name', 'url', [], null, 'all' );
		\WP_Mock::userFunction( 'wp_add_inline_style' )
			->once()
			->with( 'name', 'additional_css' );

		$icon_group->register_assets();
		$this->assertConditionsMet();
	}

	/**
	 * Test Enqueue Assets
	 *
	 * @dataProvider dataProviderTestEnqueueAssets
	 *
	 * @param string|null $content        The content to validate against.
	 * @param bool        $should_enqueue Whether the assets should be enqueued.
	 */
	public function testEnqueueAssets( string|null $content, bool $should_enqueue ): void {
		if ( $should_enqueue ) {
			\WP_Mock::userFunction( 'wp_enqueue_style' )
				->once()
				->with( 'name' );
		}

		$icon_group = new Model\IconGroup(
			'title',
			'name',
			'tag_name',
			'class_name',
			'url',
			'additional_css',
			[]
		);

		$icon_group->enqueue_assets( $content );

		$this->assertConditionsMet();
	}

	/**
	 * Data provider for testEnqueueAssets
	 *
	 * @return array
	 */
	public function dataProviderTestEnqueueAssets(): array {
		return [
			[ 'class_name', true ],
			[ null, true ],
			[ 'nope', false ],
		];
	}

	/**
	 * Test `to_json`
	 *
	 * @return void
	 */
	public function testToJson(): void {
		$icon_group = new Model\IconGroup(
			'title',
			'name',
			'tag_name',
			'class_name',
			'url',
			'additional_css',
			[]
		);

		$expected = [
			'title'     => 'title',
			'name'      => 'name',
			'tagName'   => 'tag_name',
			'className' => 'class_name',
			'icons'     => [],
		];

		$this->assertSame( $expected, $icon_group->to_json() );
	}
}
