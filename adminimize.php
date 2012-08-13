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

/**
 * Plugin Class Autoloader.
 *
 * @todo  move into separate file (watch out: location sensitive!)
 * 
 * @param  string $class_name
 * @return bool
 */
function inpsyde_adminimize_autoloader( $class_name ) {

	$file_name = '';
	$namespace = '';
	$namespace_prefix = 'Inpsyde';

	$prefix_pos = stripos( $class_name, $namespace_prefix );
	if ( $prefix_pos !== false ) {
		$class_name = substr( $class_name, $prefix_pos + strlen( $namespace_prefix ) );
	}

	// get rid of leading backslash
	$class_name = ltrim( $class_name, '\\' );

	// check if class has namespace
	if ( $last_namespace_pos = strripos( $class_name, '\\' ) ) {
		
		// separate namespace from class name
		$namespace  = substr( $class_name, 0, $last_namespace_pos );
		$class_name = substr( $class_name, $last_namespace_pos + 1 );

		// generate path without file
		$namespace  = strtolower( $namespace );
		$file_name  = str_replace( '\\', DIRECTORY_SEPARATOR, $namespace ) . DIRECTORY_SEPARATOR;
	}

	// finish file path with class name
	$class_name = strtolower( $class_name );
	$class_name = str_replace( '_', '-', $class_name );
	$file_name .= "class-" . $class_name . '.php';
	$file_name  = dirname( __FILE__ ) . '/inc/' . $file_name;

	if ( file_exists( $file_name ) ) {
		require $file_name;
		return true;
	}
	
	return false;
}
spl_autoload_register( '\Inpsyde\Adminimize\inpsyde_adminimize_autoloader' );

if ( function_exists( 'add_filter' ) ) {
	add_filter( 'plugins_loaded' ,  function () {
		$adminimize = \Inpsyde\Adminimize\Adminimize::get_instance();
		\Inpsyde\Adminimize\Options_Page::get_instance()->set_plugin( $adminimize );
	} );
}
