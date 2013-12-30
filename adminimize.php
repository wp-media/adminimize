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
 * Version:     1.8.4
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
	// load classes
	$classes = glob( plugin_dir_path( __FILE__ ) . 'classes/*.php' );

	foreach( $classes as $class ) {
		require_once $class;
	}

	// load interfaces
	$interfaces = glob( plugin_dir_path( __FILE__ ) . 'interfaces/*.php' );

	foreach( $interfaces as $interface ) {
		require_once $interface;
	}

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

	global $datacontainer;

	if ( ! defined( 'ADMINIMIZE_TEXTDOMAIN' ) )
		define( 'ADMINIMIZE_TEXTDOMAIN', 'adminimize' );

	adminimize_autoloader();

	// setup basedirs
	$datacontainer = new Adminimize_Data_Container();
	$datacontainer->set_basedirs( __FILE__ );

	// setup the plugin header data
	// initialize the PluginHeader_Reader
	// save a copy (instance) of the PluginHeader_Reader for later use
	PluginHeader_Reader::init( __FILE__ );
	$datacontainer->set( 'pluginheader', PluginHeader_Reader::get_instance() );


	if ( is_admin() ) {

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

	global $datacontainer;

	Plugin_Starter::$basename = $datacontainer->get( 'basename' );
	Plugin_Starter::loadtextdomain( PluginHeader_Reader::get_instance() );

	Plugin_Starter::load_styles( __FILE__, array( 'adminimize-style' => '/css/style.css' ) );

}

function adminimize_add_options_page() {

	global $datacontainer;

	if ( ! is_admin() )
		return false;

	$datacontainer->set( 'options_page_object', new Adminimize_Options_Page() );

}