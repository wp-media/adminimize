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

// always visible.
add_action( 'load-settings_page_adminimize/adminimize', '_mw_adminimize_add_settings_error' );

/**
 * Add custom errer messages for the error notes.
 */
function _mw_adminimize_add_settings_error() {

	$settings_hint_message = '<span style="font-size: 35px; float: left; margin: 10px 3px 0 0;">&#x261D;</span>'
		. esc_attr__(
			'Please note: The Adminimize settings page ignores the Menu Options below and displays the menu with all entries.',
			'adminimize'
		)
		. ' '
		. esc_attr__(
			'To view your changes to the menu you need to navigate away from the Adminimize settings page.',
			'adminimize'
		);

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
