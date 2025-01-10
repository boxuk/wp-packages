<?php
/**
 * Set UserSessions.
 *
 * @package Boxuk\BoxWpEditorTools
 */

declare( strict_types=1 );

namespace Boxuk\BoxWpEditorTools\Security;

/**
 * Class UserSessions
 */
class UserSessions {

	/**
	 * Init Hooks
	 * 
	 * @return void
	 */
	public function init(): void {
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_filter( 'session_token_manager', [ $this, 'get_session_token_manager' ], 10, 1 );
	}

	/**
	 * Register settings.
	 *
	 * @return void
	 */
	public function register_settings(): void {
		register_setting(
			'general', 
			'user_session_limit', 
			[
				'type'              => 'integer',
				'show_in_rest'      => true,
				'default'           => 0,
				'sanitize_callback' => 'absint',
			]
		);

		add_settings_field(
			'user_session_limit',
			__( 'User Session Limit', 'boxuk' ),
			[ $this, 'render_user_session_limit_field' ],
			'general',
			'default',
			[
				'label_for' => 'user_session_limit',
				'class'     => 'user-session-limit',
			]
		);
	}

	/**
	 * Render user session limit field.
	 *
	 * @return void
	 */
	public function render_user_session_limit_field(): void {
		?>
		<input
			type="number"
			name="user_session_limit"
			id="user_session_limit"
			value="<?php echo esc_attr( strval( static::get_session_limit() ) ); ?>"
			class="regular-text"
			min="0"
		/>
		<p class="description">
			<?php esc_html_e( 'The maximum number of concurrent sessions a user can have, 0 for unlimited', 'boxuk' ); ?>
		</p>
		<?php
	}

	/**
	 * Get session token manager.
	 *
	 * @param string $class_name The class name.
	 * @return string
	 */
	public function get_session_token_manager( string $class_name ): string {
		// Only replace the token manager if the class is the default, since
		// other plugins may have replaced it and our replacement may not be
		// compatible.
		if ( 'WP_User_Meta_Session_Tokens' === $class_name ) {
			$class_name = RestrictedSessionTokenManager::class;
		}
		return $class_name;
	}
	
	/**
	 * Get current session limit
	 * 
	 * @return int
	 */
	public static function get_session_limit(): int {
		$option = get_option( 'user_session_limit', 0 );
		return is_numeric( $option ) ? absint( $option ) : 0;
	}
}
