<?php
/**
 * Add Hints in Admin Footer.
 *
 * @package    Adminimize
 * @subpackage Add Hints in Admin Footer
 * @author     Frank Bültge
 */

if ( ! function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a part of plugin, not much I can do when called directly.";
	exit;
}

if ( ! is_admin() ) {
	return;
}

// If is an AJAX Call.
if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
	return;
}

add_action( 'admin_init', '_mw_adminimize_init_admin_footer' );
/**
 * Hook in to admin footer to print message.
 */
function _mw_adminimize_init_admin_footer() {

	if ( (int) _mw_adminimize_get_option_value( '_mw_adminimize_advice' ) === 1 ) {
		add_action( 'in_admin_footer', '_mw_adminimize_add_admin_footer' );
	}
}

/**
 * Print hint in wp-footer
 */
function _mw_adminimize_add_admin_footer() {

	// Filtered via post save with wp_kses()
	echo _mw_adminimize_get_option_value( '_mw_adminimize_advice_txt' ) . '<br />';
}
