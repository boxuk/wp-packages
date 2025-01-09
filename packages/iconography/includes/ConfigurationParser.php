<?php
/**
 * Configuration Parser
 *
 * @package Iconography
 *
 * @since 1.0.0
 */

declare ( strict_types = 1 );

namespace Boxuk\Iconography;

use Boxuk\Iconography\Model\Icon;
use Boxuk\Iconography\Model\IconGroup;
use Swaggest\JsonSchema\Context;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\SchemaContract;

/**
 * Loads Configurations
 *
 * Handles parsing an icon.config.json file and loading any necessary
 * data for the icons to be used.
 */
class ConfigurationParser {

	/**
	 * Configuration
	 *
	 * @var IconGroup[]
	 */
	private array $configs = [];

	/**
	 * Get Configurations
	 *
	 * @return IconGroup[]
	 */
	public function get_configs(): array {
		if ( empty( $this->configs ) ) {
			$this->load_configs();
		}
		return $this->configs;
	}

	/**
	 * Load Icon Config
	 *
	 * @return void
	 */
	private function load_configs() {
		$files = $this->get_config_files();
		foreach ( $files as $file ) {
			$this->parse_config( $file );
		}
	}

	/**
	 * Get Config Files
	 *
	 * @return string[]
	 */
	private function get_config_files(): array {
		$files = (array) glob( get_stylesheet_directory() . '/icons/*.config.json' );
		return apply_filters( 'boxuk_iconography_files', $files );
	}

	/**
	 * Parse Config
	 *
	 * @param string $file The file to parse.
	 *
	 * @return void
	 */
	public function parse_config( string $file ) {
		$file_contents = file_get_contents( $file ); // phpcs:ignore WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsUnknown -- The warning is for remote files, which we are not using here.

		$valid = $this->validate( json_decode( $file_contents ?: '' ) ); // phpcs:ignore Universal.Operators.DisallowShortTernary.Found -- We need to use the short ternary operator here to prevent a PHP warning.

		if ( is_wp_error( $valid ) ) {
			wp_die( $valid ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- `wp_die` escapes `WP_Error` objects for us.
		}

		$this->configs[] = $valid;
	}

	/**
	 * Get Schema
	 *
	 * @return SchemaContract
	 */
	private function get_schema(): SchemaContract {
		return Schema::import( plugin_dir_path( __DIR__ ) . '/schema/icon-config.json' );
	}

	/**
	 * Validate data
	 *
	 * @param mixed $data JSON Data to validate.
	 *
	 * @return IconGroup|\WP_Error
	 */
	private function validate( mixed $data ): IconGroup|\WP_Error {
		if ( ! is_object( $data ) ) {
			return new \WP_Error( 'invalid_data', 'Data must be an object' );
		}

		$schema = $this->get_schema();
		try {
			$schema->in( $data );
		} catch ( \Swaggest\JsonSchema\InvalidValue $e ) {
			return new \WP_Error( 'invalid_data', $e->getMessage() );
		}

		/**
		 * We can now define the type of `$data` object for static analysis tools like PHPStan since it's been validated.
		 *
		 * @var object{
		*     title:string,
		*     name:string,
		*     tagName:string,
		*     className:string,
		*     url:string,
		*     additionalCss:string,
		*     icons:array<object{label:string,content:string}>
		 * } $data Validated Data
		 */

		return new IconGroup(
			$data->title,
			$data->name,
			$data->tagName, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- This is a JSON object property.
			$data->className, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- This is a JSON object property.
			$data->url,
			$data->additionalCss, // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- This is a JSON object property.
			array_map(
				fn( $icon ) => new Icon( $icon->label, $icon->content ),
				$data->icons
			)
		);
	}
}
