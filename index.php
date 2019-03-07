<?php
/**
 * @package Zeta
 * @version 1.0.0
 */
/*
Plugin Name: Zeta II
Plugin URI: http://mmalekirad.ir
Description: Zeta II, Personal Plugin For My Personal WebSite, This Plugin Remove Un Necessary Codes And Ready WP's Website
For Publish On Real Environments.
Author: MMalekiRad
Version: 1.0.0
Author URI: http://mmalekirad.ir/
*/

# Exit If Called File Directly.
defined( 'ABSPATH' ) || exit();

final class ZetaSingle {
	private static $instance;

	public static function getInstance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new static;
		}
	}

	private function __construct() {
		$this->define_const();
		$this->init();
		$this->inc();
	}

	private static function define_const() {
		define( 'ZETA_DIR_PATH', plugin_dir_path( __FILE__ ) );
		define( 'ZETA_DIR_URL', plugin_dir_url( __FILE__ ) );
	}

	private static function init() {
		# Register Activation Hook, When Plugin Activate.
		register_activation_hook( __FILE__, 'ZetaSingle::activate' );

		# Register Deactivate Hook, When Plugin Deactivate.
		register_deactivation_hook( __FILE__, 'ZetaSingle::deactivate' );
	}

	public static function activate() {

	}

	public static function deactivate() {

	}

	private static function inc() {
		require_once 'zeta-II.php';
	}
}

ZetaSingle::getInstance();









