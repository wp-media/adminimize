<?php
/**
 * @package    Adminimize
 * @subpackage Helping_Functions
 * @author     Frank Bültge <frank@bueltge.de
 * @since      2016-01-22
 */
if ( ! function_exists( 'add_action' ) ) {
	die( "Hi there!  I'm just a part of plugin, not much I can do when called directly." );
}

/**
 * Recursive search in array.
 *
 * @param string $needle
 * @param array  $haystack
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
 * @param array $array1
 * @param array $array2
 *
 * @return bool
 */
function _mw_adminimize_in_arrays( $array1, $array2 ) {

	return (bool) count( array_intersect( $array1, $array2 ) );
}

/**
 * Check the role with the current user data.
 *
 * @param string $role
 *
 * @return bool
 */
function _mw_adminimize_current_user_has_role( $role ) {

	$user = wp_get_current_user();
	if ( in_array( $role, (array) $user->roles, FALSE ) ) {
		return TRUE;
	}

	return FALSE;
}

/**
 * Simple helper to debug to the console of the browser.
 * Set WP_DEBUG_DISPLAY in your wp-config.php to true for view debug messages inside the console.
 *
 * @param string | array | object
 * @param string $description
 *
 * @return string|void
 */
function _mw_adminimize_debug( $data, $description = '' ) {

	if ( ! _mw_adminimize_get_option_value( 'mw_adminimize_debug' ) ) {
		return;
	}

	if ( '' === $description ) {
		$description = 'Debug in Console via Adminimize Plugin:';
	}

	// Buffering to solve problems with WP core, header() etc.
	ob_start();
	$output = 'console.info(' . json_encode( $description ) . ');';
	$output .= 'console.log(' . json_encode( $data ) . ');';
	$output = sprintf( '<script>%s</script>', $output );

	echo $output;
}

/**
 * Return duplicate items from array.
 *
 * @param $array
 *
 * @return array
 */
function _mw_adminimize_get_duplicate( $array ) {

	return array_unique(
		array_diff_assoc( $array, array_unique( $array ) )
	);
}

/**
 * Get intersection of a multiple array.
 *
 * @since 2016-06-28
 *
 * @param $array  array  Array with settings of all roles.
 *
 * @return        array  Data with only the data, there in each role active.
 */
function _mw_adminimize_get_intersection( $array ) {

	return (array) call_user_func_array( 'array_intersect', $array );
}

/**
 * Flatten a multi-dimensional array in a simple array.
 *
 * @since  2016-11-19
 *
 * @param  array $array
 *
 * @return array $flat
 */
function _mw_adminimize_array_flatten( $array ) {

	$flat = array();
	foreach ( $array as $key => $value ) {
		if ( is_array( $value ) ) {
			$flat = array_merge( $flat, _mw_adminimize_array_flatten( $value ) );
		} else {
			$flat[ $key ] = $value;
		}
	}

	return $flat;
}

/**
 * Break the access to a page.
 *
 * @param string $slug Slug of each menu item.
 *
 * @return bool If the check is true, return also true as bool.
 */
function _mw_adminimize_check_page_access( $slug ) {

	// If this default behavior is deactivated.
	if ( _mw_adminimize_get_option_value( 'mw_adminimize_prevent_page_access' ) ) {
		return FALSE;
	}

	$url = basename( esc_url_raw( $_SERVER[ 'REQUEST_URI' ] ) );
	$url = htmlspecialchars( $url );

	if ( ! isset( $url ) ) {
		return FALSE;
	}

	$uri = parse_url( $url );

	if ( ! isset( $uri[ 'path' ] ) ) {
		return FALSE;
	}

	// URI without query parameter, like WP core edit.php.
	if ( ! isset( $uri[ 'query' ] ) && strpos( $uri[ 'path' ], $slug ) !== FALSE ) {
		add_action( 'load-' . $slug, '_mw_adminimize_block_page_access' );
		return TRUE;
	}

	// URL is equal the slug of WP menu.
	if ( $slug === $url ) {
		add_action( 'load-' . basename( $uri[ 'path' ] ), '_mw_adminimize_block_page_access' );
		return TRUE;
	}
}

/**
 * Break the access to a page.
 *
 * @wp-hook load-$page_slug
 */
function _mw_adminimize_block_page_access() {

	wp_die( esc_attr__( 'Cheatin&#8217; uh? Sorry, you are not allowed to access this site.', 'adminimize' ) );
}