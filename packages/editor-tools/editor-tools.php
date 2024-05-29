<?php
/**
 * Plugin Name: Boxuk Editor Tools
 * Description: A collection of tools to enhance the WordPress editor.
 * Version: 1.0.0
 * Author: BoxUK
 * Author URI: https://boxuk.com
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
( new Security\Security() )->init();
