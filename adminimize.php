<?php
/**
 * WordPress-Plugin Adminimize OOP
 *
 * PHP version 5.2
 *
 * @category   PHP
 * @package    WordPress
 * @subpackage AdminimizeOOP
 * @author     Ralf Albert <me@neun12.de>
 * @license    GPLv3 http://www.gnu.org/licenses/gpl-3.0.txt
 * @version    0.1
 * @link       http://wordpress.com
 */

/**
 * Plugin Name: AdminimizeOOP
 * Plugin URI:  http://bueltge.de/wordpress-admin-theme-adminimize/674/
 * Text Domain: adminimize
 * Domain Path: /languages
 * Description: Visually compresses the administratrive meta-boxes so that more admin page content can be initially seen. The plugin that lets you hide 'unnecessary' items from the WordPress administration menu, for alle roles of your install. You can also hide post meta controls on the edit-area to simplify the interface. It is possible to simplify the admin in different for all roles.
 * Author:      Frank Bültge, Ralf Albert
 * Author URI:  http://bueltge.de/
 * Version:     2.0
 * License:     GPLv3
 */

!( defined( 'ABSPATH' ) ) AND die( 'Standing On The Shoulders Of Giants' );

global $wp_version;

if ( version_compare( $wp_version, "2.5alpha", "<" ) ) {
	$exit_msg = 'The plugin <em><a href="http://bueltge.de/wordpress-admin-theme-adminimize/674/">Adminimize</a></em> requires WordPress 2.5 or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Please update WordPress</a> or delete the plugin.';

	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit( $exit_msg );
}

add_action(
	'plugins_loaded',
	'adminimize_plugin_init',
	10,
	0
);

/**
 * Initialize the autoloader
 */
function adminimize_autoloader() {

	// load interfaces
// 	$interfaces = glob( plugin_dir_path( __FILE__ ) . 'interfaces/*.php' );
// 	$classes = glob( plugin_dir_path( __FILE__ ) . 'classes/*.php' );

	$files = array_merge(
			glob( plugin_dir_path( __FILE__ ) . 'interfaces/*.php' ),
			glob( plugin_dir_path( __FILE__ ) . 'classes/*.php' )
	);

	foreach( $files as $file )
		require_once $file;

}

/**
 * Initialize the plugin
 * - Init autoloader
 * - Add hooks&filters on plugins loaded
 *
 * @hook	plugins_loaded
 * @return	boolean		False on ajax, xmlrpc and iframe requests, else true
 */
function adminimize_plugin_init() {

	adminimize_autoloader();

	// setup basedirs
	$storage = new Adminimize_Storage( 'adminimize_storage' );
	$storage->set_basedirs( __FILE__ );

	$pluginheaders =  new PluginHeaderReader( 'adminimize', __FILE__ );

	if ( is_admin() ) {

		if ( ! defined( 'ADMINIMIZE_TEXTDOMAIN' ) )
			define( 'ADMINIMIZE_TEXTDOMAIN', $pluginheaders->TextDomain );

		add_action(
			'admin_init',
			'adminimize_on_admin_init',
			10,
			0
		);

		// adds the options page
		add_action(
			'init',
			'adminimize_add_options_page',
			10,
			0
		);

	}

	$do_actions = 'Adminimize_Do_Actions';

	add_action( 'wp_dashboard_setup', array( $do_actions, 'dashboard_setup' ), 99, 0 );
	add_action( 'admin_head',         array( $do_actions, 'do_global_options' ), 1, 0 );

	add_action( 'wp_before_admin_bar_render', array( $do_actions, 'get_adminbar_nodes' ), 0, 0 );

}

function adminimize_on_admin_init() {

	$storage       = new Adminimize_Storage();
	$pluginstarter = new Plugin_Starter();

	$pluginstarter->basename = $storage->basename;
	$pluginstarter->load_textdomain( new PluginHeaderReader( 'adminimize' ) );
	$pluginstarter->load_styles( array( 'adminimize-style' => array( 'file' => '/css/style.css', 'enqueue' => false ) ) );

}

function adminimize_add_options_page() {

	if ( ! is_admin() )
		return false;

	$storage = new Adminimize_Storage( 'adminimize_storage' );

	$opt_page = new Adminimize_Options_Page();
	$storage->options_page_object = $opt_page;

}
