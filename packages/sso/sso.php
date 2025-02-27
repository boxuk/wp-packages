<?php
/**
 * Plugin Name: BoxUK SSO
 * Description: A login method for SSO for Box Staff. 
 * Version: 1.0.0
 * Author: BoxUK
 * Author URI: https://boxuk.com
 * 
 * @package Boxuk\Sso
 */

declare( strict_types=1 );

namespace Boxuk\Sso;

$sso = new SSO();
$sso->init();
