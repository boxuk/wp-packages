<?php
/**
 * IconGroup Class
 *
 * @package Iconography
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Boxuk\Iconography\Model;

/**
 * IconGroup Model
 */
class IconGroup {

	/**
	 * Create a new Icon Group
	 *
	 * @param string $title          The title of the icon group.
	 * @param string $name           The name of the icon group.
	 * @param string $tag_name       The tag name of the icon group.
	 * @param string $class_name     The class name of the icon group.
	 * @param string $url            The URL of the icon group.
	 * @param string $additional_css The additional CSS of the icon group.
	 * @param Icon[] $icons          The icons of the icon group.
	 */
	public function __construct(
		private readonly string $title,
		private readonly string $name,
		private readonly string $tag_name,
		private readonly string $class_name,
		private readonly string $url,
		private readonly string $additional_css,
		private readonly array $icons
	) {}

	/**
	 * Get Title
	 *
	 * @return string
	 */
	public function get_title(): string {
		return apply_filters( 'boxuk_iconography_configuration_title', $this->title );
	}

	/**
	 * Get Name
	 *
	 * @return string
	 */
	public function get_name(): string {
		return apply_filters( 'boxuk_iconography_configuration_name', $this->name );
	}

	/**
	 * Get Tag Name
	 *
	 * @return string
	 */
	public function get_tag_name(): string {
		return apply_filters( 'boxuk_iconography_configuration_tag_name', $this->tag_name );
	}

	/**
	 * Get Class Name
	 *
	 * @return string
	 */
	public function get_class_name(): string {
		return apply_filters( 'boxuk_iconography_configuration_class_name', $this->class_name );
	}

	/**
	 * Get Icons
	 *
	 * @return Icon[]
	 */
	public function get_icons(): array {
		return apply_filters( 'boxuk_iconography_configuration_icons', $this->icons );
	}


	/**
	 * Regiser Assets in WP
	 */
	public function register_assets(): void {
		wp_register_style( $this->name, $this->url, array(), null, 'all' ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- The version isn't required for external stylesheets.
		wp_add_inline_style( $this->name, $this->additional_css );
	}

	/**
	 * Enqueue Assets in WP
	 *
	 * @param string|null $content The content to validate against.
	 */
	public function enqueue_assets( string|null $content = null ): void {
		if ( null === $content || $this->has_match( $content ) ) {
			wp_enqueue_style( $this->name );
		}
	}

	/**
	 * Has Match
	 *
	 * @param string $content The content to validate against.
	 *
	 * @return bool
	 */
	private function has_match( string $content ): bool {
		return str_contains( $content, $this->class_name );
	}

	/**
	 * To JSON array
	 *
	 * An array for JSON representation to be consumed by the block editor.
	 *
	 * @return array{title:string,name:string,tagName:string,className:string,icons:array<Icon>}
	 */
	public function to_json(): array {
		return array(
			'title'     => $this->get_title(),
			'name'      => $this->get_name(),
			'tagName'   => $this->get_tag_name(),
			'className' => $this->get_class_name(),
			'icons'     => $this->get_icons(),
		);
	}
}
