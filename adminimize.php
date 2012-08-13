<?php
/**
 * Plugin Name: Adminimize 2
 * Description: Visually compresses the administratrive meta-boxes so that more admin page content can be initially seen. The plugin that lets you hide 'unnecessary' items from the WordPress administration menu, for alle roles of your install. You can also hide post meta controls on the edit-area to simplify the interface. It is possible to simplify the admin in different for all roles.
 * Version:     2.0
 * Author:      Inpsyde GmbH
 * Author URI:  http://inpsyde.com
 * Licence:     GPLv3
 * Text Domain: adminimize
 * Domain Path: /language
 */

namespace Inpsyde\Adminimize;

require_once 'inc/autoload.php';
require_once 'inc/api.php';
require_once 'inc/helpers.php';

if ( function_exists( 'add_filter' ) ) {
	add_filter( 'plugins_loaded' ,  function () {

		$adminimize = \Inpsyde\Adminimize\Adminimize::get_instance();
		\Inpsyde\Adminimize\Options_Page::get_instance()->set_plugin( $adminimize );

		$partials = array(
			'About',
			'Backend_Options',
			'Dashboard_Options',
			'Global_Options',
			'Links_Options',
			'Menu_Options',
			'Nav_Menu_Options',
			'Write_Page_Options',
			'Write_Post_Options'
		);
		$partials = apply_filters( 'adminimize_active_partials', $partials );

		foreach ( $partials as $partial_class ) {
			call_user_func( "\\Inpsyde\\Adminimize\\Partial\\$partial_class::get_instance" );
		}
	} );
}
