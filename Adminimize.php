<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*-

/**
 * Plugin Name: Adminimize
 * Plugin URI:  https://wordpress.org/plugins/adminimize/
 * Text Domain: adminimize
 * Description: Visually compresses the administrative meta-boxes so that more admin page content can be initially seen. The plugin that lets you hide 'unnecessary' items from the WordPress administration menu, for all roles of your install. You can also hide post meta controls on the edit-area to simplify the interface. It is possible to simplify the admin in different for all roles.
 * Author:      Frank Bültge
 * Author URI:  https://bueltge.de/
 * Version:     2.0.0-alpha
 * License:     GPLv3+
 */

namespace Adminimize;

! \defined( 'ABSPATH' ) && exit;

add_action( 'plugins_loaded', __NAMESPACE__ . '\\init' );
/**
 * Initialize in the WP circus.
 */
function init() {

	$autoload = __DIR__ . '/vendor/autoload.php';
	if ( file_exists( $autoload ) ) {
		/** @noinspection PhpIncludeInspection */
		require_once $autoload;
	}

	try {
		load_plugin_textdomain( 'adminimize' );

		$plugin = new Plugin( __FILE__ );
		$plugin->init();

	} catch ( \Exception $e ) {
		wp_die( esc_html( $e->getMessage() ) );
	}
}
