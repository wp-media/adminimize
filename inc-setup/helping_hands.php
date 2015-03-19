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

// Need only on admin area
if ( ! is_admin() )
	return NULL;

// fix some badly enqueued scripts with no sense of HTTPS
// Kudos to http://snippets.webaware.com.au/snippets/cleaning-up-wordpress-plugin-script-and-stylesheet-loads-over-ssl/
add_action( 'wp_print_scripts', 'enqueueScriptsFix', 100 );
add_action( 'wp_print_styles', 'enqueueStylesFix', 100 );

/**
 * force plugins to load scripts with SSL if page is SSL
 */
function enqueueScriptsFix() {

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
function enqueueStylesFix() {

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