<?php
/**
 * Plugin Name: Boxuk Editor Tools
 * Description: A collection of tools to enhance the WordPress editor.
 * Version: 1.0.0
 * Author: BoxUK
 * Author URI: https://boxuk.com
 * Requires at least: 6.4 
 *
 * @package Boxuk\BoxWpEditorTools
 */

declare( strict_types = 1 );
namespace Boxuk\BoxWpEditorTools;

( new BlockLoader() )->init();
( new Comments() )->init();
( new EditorCleanup() )->init();
( new PostTypes() )->init();
( new TemplatePersistence() )->init();
( new Security\AuthorEnumeration() )->init();
( new Security\Headers() )->init();
( new Security\PasswordValidation() )->init();
( new Security\UserSessions() )->init();
( new Security\RestrictHTTPRequestMethods() )->init();
( new Security\RSS() )->init();
( new Security\SessionTimeoutModifier() )->init();
( new Security\UserLogin() )->init();
