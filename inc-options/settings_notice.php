<?php
/**
 * @package    Adminimize
 * @subpackage Notice for settings page
 * @author     Frank Bültge
 */
if ( ! function_exists( 'add_filter' ) ) {
	echo "Hi there! I'm just a part of plugin, not much I can do when called directly.";
	exit;
}
// always visible
add_action( 'load-settings_page_adminimize/adminimize', '_mw_adminimize_add_settings_error' );
//add_action( 'admin_notices', '_mw_adminimize_on_admin_init' );

function _mw_adminimize_add_settings_error() {
	
	$settings_hint_message = '<span style="font-size: 35px; float: left; margin: 10px 3px 0 0;">&#x261D;</span>' . __( 'Please note: The Adminimize settings page ignores the Menu Options below and displays the menu with all entries.<br /><span style="font-weight: 300;">To view your changes to the menu you need to navigate away from the Adminimize settings page.</span>', FB_ADMINIMIZE_TEXTDOMAIN );
	
	add_settings_error(
		'_mw_settings_hint_message',
		'_mw_settings_hint',
		$settings_hint_message,
		'updated'
	);
	
}

function _mw_adminimize_get_admin_notices() {
	
	settings_errors( '_mw_settings_hint_message' );
}
