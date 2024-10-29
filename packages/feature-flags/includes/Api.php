<?php
/**
 * API class.
 * 
 * @package BoxUk\WpFeatureFlags
 */

declare(strict_types=1);

namespace BoxUk\WpFeatureFlags;

use WP_REST_Controller;
use WP_REST_Response;
use WP_REST_Request;
use WP_Error;

/**
 * API class.
 */
class Api extends WP_REST_Controller {

	/**
	 * ID lookup for the API.
	 *
	 * @var string
	 */
	protected $id_lookup = '(?P<flag>[a-zA-Z0-9_-]+)';

	/**
	 * Constructor for the API.
	 */
	public function __construct() {
		$this->namespace = 'feature-flags/v1';
		$this->rest_base = '/flags';
		$this->id_lookup = '(?P<flag>[a-zA-Z0-9_-]+)';
	}

	/**
	 * Initialize the API.
	 */
	public function init(): void {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register the API routes.
	 */
	public function register_routes(): void {
		register_rest_route(
			$this->namespace,
			'/flags',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_items' ),
				'permission_callback' => array( $this, 'get_items_permissions_check' ),
			)
		);

		register_rest_route(
			$this->namespace,
			$this->rest_base . '/' . $this->id_lookup,
			array(
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => array( $this, 'update_item' ),
				'permission_callback' => array( $this, 'update_item_permissions_check' ),
			)
		);
	}

	/**
	 * Check if the current user has the required permissions.
	 * 
	 * @param WP_REST_Request<array<mixed>> $request Full details about the request.
	 */
	public function get_items_permissions_check( $request ): true|WP_Error {
		if ( current_user_can( 'manage_options' ) ) {
			return true;
		}
		return new WP_Error( 'rest_forbidden', esc_html__( 'You do not have permissions to manage flags.', 'boxuk' ), array( 'status' => 401 ) );
	}

	/**
	 * Get all flags.
	 * 
	 * @param WP_REST_Request<array<mixed>> $request Full details about the request.
	 */
	public function get_items( $request ) {
		return rest_ensure_response( array_values( FlagRegister::instance()->get_flags() ) );
	}

	/**
	 * Check if the current user has the required permissions.
	 * 
	 * @param WP_REST_Request<array<mixed>> $request Full details about the request.
	 */
	public function update_item_permissions_check( $request ): true|WP_Error {
		if ( current_user_can( 'manage_options' ) ) {
			return true;
		}
		return new WP_Error( 'rest_forbidden', esc_html__( 'You do not have permissions to manage flags.', 'boxuk' ), array( 'status' => 401 ) );
	}

	/**
	 * Update a flag.
	 * 
	 * @param WP_REST_Request<array<array{flag:string}>> $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function update_item( $request ) {
		$key = $request->get_param( 'flag' );

		if ( ! $key ) {
			wp_die( new WP_Error( 'missing_params', 'Missing required parameters.', array( 'status' => 400 ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		$flag = FlagRegister::instance()->get_flag( $key );

		if ( ! $flag ) {
			wp_die( new WP_Error( 'flag_not_found', 'Flag not found.', array( 'status' => 404 ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		$flag_data = $request->get_json_params();

		if ( boolval( $flag_data['is_published'] ) ) { 
			$flag->publish();
		} else { 
			$flag->unpublish();
		}

		// Remove the flag from all users.
		foreach ( $flag->get_users() as $user_id ) {
			$flag->unpublish_for_user( $user_id );
		}

		// Add the flag to the selected users.
		foreach ( $flag_data['users'] ?? array() as $user_id ) { 
			$flag->publish_for_user( absint( $user_id ) );
		}

		return rest_ensure_response( $flag );
	}
}
