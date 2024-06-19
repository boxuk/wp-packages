<?php
/**
 * Test Icon class
 *
 * @package Boxuk\Iconography
 */

declare( strict_types = 1 );

namespace Boxuk\Iconography;

use WP_Mock\Tools\TestCase;

/**
 * TestIcon class
 */
class TestIcon extends TestCase {

	/**
	 * Test `__construct`
	 *
	 * @return void
	 */
	public function testConstruct(): void {
		$icon = new Model\Icon( 'label', 'content' );

		$this->assertSame( 'label', $icon->label );
		$this->assertSame( 'content', $icon->content );
	}
}
