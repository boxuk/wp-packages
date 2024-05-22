<?php
/**
 * Tests for Security class.
 *
 * @package Security
 */

declare( strict_types=1 );
namespace Boxuk\BoxWpEditorTools\Security;

use Mockery;
use WP_Mock\Tools\TestCase;

/**
 * Security test case.
 *
 * @runTestsInSeparateProcesses -- for the hard-overloading of dependant classes.
 * @preserveGlobalState disabled
 */
class TestSecurity extends TestCase {

	/**
	 * Test init method
	 * 
	 * @param array<boolean> $args The arguments to pass to the init method.
	 * 
	 * @return void
	 * 
	 * @dataProvider init_provider
	 */
	public function testInit( array $args ): void { 
		list( 
			$author_enumeration, 
			$headers, 
			$password_validation, 
			$restricted_user_sessions, 
			$restricted_http_request_methods, 
			$restrict_rss, 
			$modify_session_timeouts, 
			$user_login_hardening
		) = $args;

		$author_enumeration_spy            = Mockery::mock( 'overload:' . AuthorEnumeration::class );
		$headers_spy                       = Mockery::mock( 'overload:' . Headers::class );
		$password_validation_spy           = Mockery::mock( 'overload:' . PasswordValidation::class );
		$user_sessions_spy                 = Mockery::mock( 'overload:' . UserSessions::class );
		$restrict_http_request_methods_spy = Mockery::mock( 'overload:' . RestrictHTTPRequestMethods::class );
		$restrict_rss_spy                  = Mockery::mock( 'overload:' . RSS::class );
		$session_timeout_modifier_spy      = Mockery::mock( 'overload:' . SessionTimeoutModifier::class );
		$user_login_hardening_spy          = Mockery::mock( 'overload:' . UserLogin::class );

		if ( $author_enumeration ) { 
			$author_enumeration_spy->shouldReceive( 'init' )->once();
		}

		if ( $headers ) { 
			$headers_spy->shouldReceive( 'init' )->once();
		}

		if ( $password_validation ) { 
			$password_validation_spy->shouldReceive( 'init' )->once();
		}

		if ( $restricted_user_sessions ) { 
			$user_sessions_spy->shouldReceive( 'init' )->once();
		}

		if ( $restricted_http_request_methods ) { 
			$restrict_http_request_methods_spy->shouldReceive( 'init' )->once();
		}

		if ( $restrict_rss ) { 
			$restrict_rss_spy->shouldReceive( 'init' )->once();
		}

		if ( $modify_session_timeouts ) { 
			$session_timeout_modifier_spy->shouldReceive( 'init' )->once();
		}

		if ( $user_login_hardening ) { 
			$user_login_hardening_spy->shouldReceive( 'init' )->once();
		}

		$security = new Security();
		$security->init( 
			$author_enumeration, 
			$headers, 
			$password_validation, 
			$restricted_user_sessions, 
			$restricted_http_request_methods, 
			$restrict_rss, 
			$modify_session_timeouts, 
			$user_login_hardening
		);

		$this->assertConditionsMet();
	}

	/**
	 * Data provider for `testInit`
	 * 
	 * @return array
	 */
	public function init_provider(): array {
		return array(
			array( array( true, true, true, true, true, true, true, true ) ),
			array( array( true, true, true, true, true, true, true, false ) ),
			array( array( true, true, true, true, true, true, false, false ) ),
			array( array( true, true, true, true, true, false, false, false ) ),
			array( array( true, true, true, true, false, false, false, false ) ),
			array( array( true, true, true, false, false, false, false, false ) ),
			array( array( true, true, false, false, false, false, false, false ) ),
			array( array( true, false, false, false, false, false, false, false ) ),
			array( array( false, false, false, false, false, false, false, false ) ),
		);
	}
}
