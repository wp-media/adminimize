<?php
/**
 * @package    Adminimize
 * @subpackage Admin Bar Items
 * @author     Frank Bültge
 */

if ( ! function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a part of plugin, not much I can do when called directly.";
	exit;
}

// Get all Admin Bar items, different between front- and backend.
add_action( 'wp_before_admin_bar_render', '_mw_adminimize_get_admin_bar_nodes', 99999 );
// Render the Admin bar new, different between front- and backend.
add_action( 'wp_before_admin_bar_render', '_mw_adminimize_change_admin_bar', 99999 );

/**
 * Get all admin bar items in back end and write in a options of Adminimize settings array
 *
 * @since    1.8.1  01/10/2013
 */
function _mw_adminimize_get_admin_bar_nodes() {

	// Only Administrator get all items.
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( _mw_adminimize_exclude_settings_page() ) {
		return;
	}

	/** @var $wp_admin_bar WP_Admin_Bar */
	global $wp_admin_bar;

	// @see: http://codex.wordpress.org/Function_Reference/get_nodes
	$all_toolbar_nodes = $wp_admin_bar->get_nodes();

	$settings = 'mw_adminimize_admin_bar_frontend_nodes';
	// Set string on settings for Admin Area.
	if ( is_admin() ) {
		$settings = 'mw_adminimize_admin_bar_nodes';
	}

	if ( $all_toolbar_nodes ) {

		// get all options
		$adminimizeoptions = _mw_adminimize_get_option_value();

		// add admin bar array
		$adminimizeoptions[ $settings ] = $all_toolbar_nodes;

		// update options
		_mw_adminimize_update_option( $adminimizeoptions );
	}
}

/**
 * Remove items in Admin Bar for current role of current active user in front end area
 * Exclude Super Admin, if active
 * Exclude Settings page of Adminimize
 *
 * @since   1.8.1  01/10/2013
 */
function _mw_adminimize_change_admin_bar() {

	// Only for users, there logged in.
	if ( ! is_user_logged_in() ) {
		return;
	}

	// Exclude super admin.
	if ( _mw_adminimize_exclude_super_admin() ) {
		return;
	}

	// Exclude the new settings of the Admin Bar on settings page of Adminimize.
	if ( _mw_adminimize_exclude_settings_page() ) {
		return;
	}

	// If the admin bar is not active, filtering is not necessary.
	if ( ! is_admin_bar_showing() ) {
		return;
	}

	/** @var $wp_admin_bar WP_Admin_Bar */
	global $wp_admin_bar;

	// Get current user data.
	$user      = wp_get_current_user();
	if ( ! $user->roles ) {
		return;
	}

	// Get all roles of logged in user.
	$user_roles = $user->roles;
	//$user_roles = _mw_adminimize_get_all_user_roles();
	$disabled_admin_bar_option_ = array();

	$role_prefix = is_admin() ? 'mw_adminimize_disabled_admin_bar_' : 'mw_adminimize_disabled_admin_bar_frontend_';

	foreach ( $user_roles as $role ) {
		$disabled_admin_bar_option_[] = _mw_adminimize_get_option_value( $role_prefix . $role . '_items' );
	}

	// Merge multidimensional array in to one, flat.
	$disabled_admin_bar_option_ = _mw_adminimize_array_flatten( $disabled_admin_bar_option_ );

	// Support Multiple Roles for users.
	if ( _mw_adminimize_get_option_value( 'mw_adminimize_multiple_roles' ) && 1 < count( $user->roles ) ) {
		$disabled_admin_bar_option_ = _mw_adminimize_get_duplicate( $disabled_admin_bar_option_ );
	}

	// No settings for this role, exit.
	if ( ! $disabled_admin_bar_option_ ) {
		return;
	}

	foreach ( (array) $disabled_admin_bar_option_ as $admin_bar_item ) {
		$wp_admin_bar->remove_node( $admin_bar_item );
	}
}
