<?php
/**
 * Plugin Name: Boxuk Editor Tools
 * Plugin URI: https://boxuk.com
 * Description: A collection of tools to enhance the WordPress editor.
 * 
 * @package Boxuk\BoxWpEditorTools
 */

declare( strict_types = 1 );

( new \Boxuk\BoxWpEditorTools\BlockLoader() )->init();
( new \Boxuk\BoxWpEditorTools\Comments() )->init();
( new \Boxuk\BoxWpEditorTools\EditorCleanup() )->init();
( new \Boxuk\BoxWpEditorTools\PostTypes() )->init();
( new \Boxuk\BoxWpEditorTools\TemplatePersistence() )->init(); 
( new \Boxuk\BoxWpEditorTools\Security\Security() )->init();
