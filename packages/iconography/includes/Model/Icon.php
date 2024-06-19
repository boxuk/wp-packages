<?php
/**
 * Icon Class
 *
 * @package Iconography
 *
 * @since 1.0.0
 */

declare ( strict_types = 1 );

namespace Boxuk\Iconography\Model;

/**
 * Icon Model
 */
class Icon {

	/**
	 * Create a new Icon
	 *
	 * @param string $label   The label of the icon.
	 * @param string $content The content of the icon.
	 */
	public function __construct(
		public readonly string $label,
		public readonly string $content,
	) {}
}
