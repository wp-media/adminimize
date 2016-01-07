<?php
/**
 * @package    Adminimize
 * @subpackage Helping_Functions
 * @author     Frank Bültge <frank@bueltge.de
 * @since      2015-03-19
 */
if ( ! function_exists( 'add_action' ) ) {
	die( "Hi there!  I'm just a part of plugin, not much I can do when called directly." );
}

/**
 * Recursive search in array.
 *
 * @param $needle
 * @param $haystack
 *
 * @return bool
 */
function _mw_adminimize_recursive_in_array( $needle, $haystack ) {

	if ( '' === $haystack ) {
		return FALSE;
	}

	if ( ! $haystack ) {
		return FALSE;
	}

	foreach ( $haystack as $stalk ) {
		if ( $needle === $stalk
			|| ( is_array( $stalk )
				&& _mw_adminimize_recursive_in_array( $needle, $stalk )
			)
		) {
			return TRUE;
		}
	}

	return FALSE;
}

/**
 * Check if array contains all array values from another array.
 *
 * @param $array1
 * @param $array2
 *
 * @return bool
 */
function _mw_adminimize_in_arrays( $array1, $array2 ) {

	return (bool) count( array_intersect( $array1, $array2 ) );
}

// fix some badly enqueued scripts with no sense of HTTPS
// Kudos to http://snippets.webaware.com.au/snippets/cleaning-up-wordpress-plugin-script-and-stylesheet-loads-over-ssl/
add_action( 'wp_print_scripts', '_mw_adminimize_enqueueScriptsFix', 100 );
add_action( 'wp_print_styles', '_mw_adminimize_enqueueStylesFix', 100 );

/**
 * force plugins to load scripts with SSL if page is SSL
 */
function _mw_adminimize_enqueueScriptsFix() {

	if ( ! is_admin() ) {
		if ( ! empty( $_SERVER[ 'HTTPS' ] ) ) {
			global $wp_scripts;
			foreach ( (array) $wp_scripts->registered as $script ) {
				if ( stripos( $script->src, 'http://', 0 ) !== FALSE ) {
					$script->src = str_replace( 'http://', 'https://', $script->src );
				}
			}
		}
	}
}

/**
 * force plugins to load styles with SSL if page is SSL
 */
function _mw_adminimize_enqueueStylesFix() {

	if ( ! is_admin() ) {
		if ( ! empty( $_SERVER[ 'HTTPS' ] ) ) {
			global $wp_styles;
			foreach ( (array) $wp_styles->registered as $script ) {
				if ( stripos( $script->src, 'http://', 0 ) !== FALSE ) {
					$script->src = str_replace( 'http://', 'https://', $script->src );
				}
			}
		}
	}
}