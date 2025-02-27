<?php
/**
 * Test SSO class
 *
 * @package Boxuk\Sso
 */

declare( strict_types = 1 );

namespace Boxuk\Sso;

use WP_Mock\Tools\TestCase;

/**
 * Test SSO class
 */
class TestSSO extends TestCase {

	/**
	 * Test Init
	 * 
	 * @return void
	 */
	public function test_init() { 
		$class_in_test = new SSO();

		\WP_Mock::expectFilterAdded( 'option_sso_sp_base', [ $class_in_test, 'get_sso_url' ] );
		\WP_Mock::expectFilterAdded( 'option_sso_enabled', [ $class_in_test, 'get_sso_enabled_state' ] );
		\WP_Mock::expectFilterAdded( 'option_sso_role_management', [ $class_in_test, 'get_role_management_state' ] );
		\WP_Mock::expectActionAdded( 'admin_head', [ $class_in_test, 'hide_sso_settings' ] );

		/* Disable VIP Login Options */
		\WP_Mock::expectFilterAdded( 'wpcom_vip_enable_two_factor', '__return_false' );
		\WP_Mock::expectFilterAdded( 'wpcom_vip_is_two_factor_forced', '__return_false' );
		\WP_Mock::expectFilterAdded( 'wpcom_vip_two_factor_prep_hide_admin_notice', '__return_true' );

		/* Set SAML Attributes */
		\WP_Mock::expectFilterAdded( 'wpsimplesaml_attribute_mapping', [ $class_in_test, 'attribute_mapping' ] );
		\WP_Mock::expectFilterAdded( 'wpsimplesaml_idp_metadata_xml_path', [ $class_in_test, 'get_metadata_path' ] );
		\WP_Mock::expectFilterAdded( 'wpsimplesaml_config', [ $class_in_test, 'change_saml_config' ], 12 );
		\WP_Mock::expectFilterAdded( 'wpsimplesaml_map_role', [ $class_in_test, 'map_user_group_to_wp_role' ], 10, 2 );
		\WP_Mock::expectFilterAdded( 'wpsimplesaml_match_user', [ $class_in_test, 'maybe_update_role' ], 10, 3 );

		$class_in_test->init();
		$this->assertConditionsMet();
	}

	/**
	 * Test get metadata path
	 *
	 * @return void
	 */
	public function test_get_metadata_path() { 
		$class_in_test = new SSO();
		\WP_Mock::userFunction( 'plugin_dir_path' )->once()->andReturn( dirname( __DIR__ ) );
		$this->assertStringContainsString( 'mock-saml.xml', $class_in_test->get_metadata_path( '' ) );
	}

	/**
	 * Test SSO Enabled State
	 * 
	 * @dataProvider true_false_provider
	 *
	 * @param boolean $forced The value of the filter.
	 * 
	 * @return void
	 */
	public function test_get_sson_enabled_state( bool $forced ) { 
		$class_in_test = new SSO();
		
		\WP_Mock::onFilter( 'boxuk_sso_force_redirect' )->with( false )->reply( $forced );

		$this->assertEquals(
			$forced ? 'force' : 'link',
			$class_in_test->get_sso_enabled_state()
		);
	}

	/**
	 * True False Provider
	 */
	public function true_false_provider(): array {
		return [
			[ true ],
			[ false ],
		];
	}

	/**
	 * Test Get Role Management State
	 * 
	 * @return void
	 */
	public function test_get_role_management_state() { 
		$class_in_test = new SSO();

		$this->assertEquals(
			'enabled',
			$class_in_test->get_role_management_state()
		);
	}

	/**
	 * Test SSO URL
	 * 
	 * @return void
	 */
	public function test_get_sso_url() { 
		$class_in_test = new SSO();
		\WP_Mock::userFunction( 'home_url' )->once()->andReturn( 'http://example.com' );

		$this->assertEquals(
			'http://example.com',
			$class_in_test->get_sso_url()
		);
	}

	/**
	 * Test Attribute Mapping
	 * 
	 * @return void
	 */
	public function test_attribute_mapping() {
		$class_in_test = new SSO();

		$this->assertEquals(
			[
				'first_name' => 'firstName',
				'last_name'  => 'lastName',
			],
			$class_in_test->attribute_mapping( [] )
		);
	}

	/**
	 * Test Maybe Update User
	 * 
	 * @return void
	 */
	public function test_maybe_update_role() {
		$class_in_test = new SSO();
		// @todo - write this test.
	}

	/**
	 * Test Mapping user Roles
	 *
	 * @return void
	 */
	public function test_map_user_group_to_wp_role() {
		$class_in_test = new SSO();

		$this->assertEquals(
			'test',
			$class_in_test->map_user_group_to_wp_role( 'test', [] )
		);

		$this->assertEquals(
			'administrator',
			$class_in_test->map_user_group_to_wp_role( 'test', [ 'user_role' => [ 0 => 'BoxUKWP_Admin' ] ] )
		);
	}

	/**
	 * Test Saml Config
	 *
	 * @dataProvider true_false_provider
	 * 
	 * @param boolean $is_error The value of the input.
	 * 
	 * @return void
	 */
	public function test_saml_config( bool $is_error ) { 
		$class_in_test = new SSO();

		\WP_Mock::userFunction( 'is_wp_error' )->once()->andReturn( $is_error );

		$result = $class_in_test->change_saml_config( [] );
		if ( $is_error ) {
			$this->assertIsArray( $result );
			$this->assertEmpty( $result );
		} else {
			$this->assertEquals(
				[], // @todo - update when we update the settings array in the function. 
				$result
			);
		}
	}

	/**
	 * Test Hide SSO Settings
	 * 
	 * @return void
	 */
	public function test_hide_sso_settings() { 
		global $wp_settings_sections;
		$wp_settings_sections['general'] = [ // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- override during test
			'sso_settings' => true,
		];

		$class_in_test = new SSO();
		$class_in_test->hide_sso_settings();

		$this->assertArrayNotHasKey( 'sso_settings', $wp_settings_sections['general'] );
	}
}
