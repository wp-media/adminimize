<?php
namespace Inpsyde\Adminimize;

/**
 * Plugin Class Autoloader.
 *
 * @param  string $class_name
 * @return bool
 */
function autoloader( $class_name ) {

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
	$file_name  = dirname( dirname( __FILE__ ) ) . '/inc/' . $file_name;

	if ( file_exists( $file_name ) ) {
		require $file_name;
		return true;
	}
	
	return false;
}

spl_autoload_register( '\Inpsyde\Adminimize\autoloader' );
