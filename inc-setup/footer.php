<?php
/**
 * @package    Adminimize
 * @subpackage Remove the footer area of the back end.
 * @author     Frank Bültge
 */

if ( ! function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a part of plugin, not much I can do when called directly.";
	exit;
}

add_action( 'init', '_mw_adminimize_remove_footer' );
/**
 * Check settings for enqueue scripts.
 */
function _mw_adminimize_remove_footer() {

	// Exclude super admin.
	if ( _mw_adminimize_exclude_super_admin() ) {
		return;
	}

	// Leave the settings screen from Adminimize to see all areas on settings, also on AJAX requests.
	if ( _mw_adminimize_exclude_settings_page() ) {
		return;
	}

	if ( 1 !== (int) _mw_adminimize_get_option_value( '_mw_adminimize_footer' ) ) {
		return;
	}

	add_action( 'admin_init', '_mw_adminimize_enqueue_remove_footer' );
}

/**
 * Enqueue script to remove admin footer area.
 */
function _mw_adminimize_enqueue_remove_footer() {

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_script(
		'_mw_adminimize_remove_footer',
		WP_PLUGIN_URL . '/' . FB_ADMINIMIZE_BASEFOLDER . '/js/remove_footer' . $suffix . '.js',
		array( 'jquery' )
	);
}
