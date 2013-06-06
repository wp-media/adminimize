<?php
/**
 * @package    Adminimize
 * @subpackage Add Hint in Admin Footer
 * @author     Frank Bültge
 */
if ( ! function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a part of plugin, not much I can do when called directly.";
	exit;
}

if ( ! is_admin() )
	return NULL;

// on init of WordPress
add_action( 'admin_init', '_mw_adminimize_init_admin_footer' );

function _mw_adminimize_init_admin_footer() {
	
	if ( (int) _mw_adminimize_get_option_value( '_mw_adminimize_advice' ) === 1 )
		add_action( 'in_admin_footer', '_mw_adminimize_add_admin_footer' );
}

/**
 * Hint in wp-footer
 */
function _mw_adminimize_add_admin_footer() {
	
	echo stripslashes( _mw_adminimize_get_option_value( '_mw_adminimize_advice_txt' ) ) . '<br />';
}