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

//add_action( 'wp_before_admin_bar_render', '_mw_adminimize_get_admin_bar_nodes', 9999 );
add_action( 'admin_bar_menu', '_mw_adminimize_get_admin_bar_nodes', 99999 );
/**
 * Get all admin bar items in back end and write in a options of Adminimize settings array
 *
 * @since   1.8.1  01/10/2013
 *
 * @param $wp_admin_bar
 */
function _mw_adminimize_get_admin_bar_nodes( $wp_admin_bar ) {

	if ( ! is_admin() ) {
		return;
	}

	if ( _mw_adminimize_exclude_settings_page() ) {
		return;
	}

	// @see: http://codex.wordpress.org/Function_Reference/get_nodes
	$all_toolbar_nodes = $wp_admin_bar->get_nodes();

	if ( $all_toolbar_nodes ) {
		// get all options
		$adminimizeoptions = _mw_adminimize_get_option_value();

		// add admin bar array
		$adminimizeoptions[ 'mw_adminimize_admin_bar_nodes' ] = $all_toolbar_nodes;

		// update options
		_mw_adminimize_update_option( $adminimizeoptions );
	}
}

//add_action( 'wp_before_admin_bar_render', '_mw_adminimize_get_admin_bar_frontend_nodes', 9999 );
add_action( 'admin_bar_menu', '_mw_adminimize_get_admin_bar_frontend_nodes', 99999 );
/**
 * Get admin bar items from frontend view.
 *
 * @since 2015-07-03
 *
 * @param $wp_admin_bar
 *
 * @return null
 */
function _mw_adminimize_get_admin_bar_frontend_nodes( $wp_admin_bar ) {

	if ( ! is_user_logged_in() ) {
		return;
	}

	if ( is_admin() ) {
		return;
	}

	// Only the right capability allow to get all items and update settings.
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	// @see: http://codex.wordpress.org/Function_Reference/get_nodes
	$all_toolbar_nodes = $wp_admin_bar->get_nodes();

	if ( $all_toolbar_nodes ) {
		// get all options
		$adminimizeoptions = _mw_adminimize_get_option_value();

		// add admin bar array
		$adminimizeoptions[ 'mw_adminimize_admin_bar_frontend_nodes' ] = $all_toolbar_nodes;

		// update options
		_mw_adminimize_update_option( $adminimizeoptions );
	}
}

add_action( 'admin_bar_menu', '_mw_adminimize_change_admin_bar', 99999 );
/**
 * Remove items in Admin Bar for current role of current active user in back end area
 * Exclude Super Admin, if active
 * Exclude Settings page of Adminimize
 *
 * @since   1.8.1  01/10/2013
 *
 * @param $wp_admin_bar
 *
 * @return null
 */
function _mw_adminimize_change_admin_bar( $wp_admin_bar ) {

	// works only for back end admin bar
	if ( ! is_admin() ) {
		return;
	}

	if ( _mw_adminimize_exclude_settings_page() ) {
		return;
	}

	// Exclude super admin
	if ( _mw_adminimize_exclude_super_admin() ) {
		return;
	}

	$user_roles                 = _mw_adminimize_get_all_user_roles();
	$disabled_admin_bar_option_ = array();

	foreach ( $user_roles as $role ) {

		$disabled_admin_bar_option_[ $role ] = _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_admin_bar_' . $role . '_items'
		);
	}

	foreach ( $user_roles as $role ) {

		if ( ! isset( $disabled_admin_bar_option_[ $role ][ '0' ] ) ) {
			$disabled_admin_bar_option_[ $role ][ '0' ] = '';
		}
	}

	foreach ( $user_roles as $role ) {
		$user = wp_get_current_user();

		if ( is_array( $user->roles )
			&& in_array( $role, $user->roles )
			&& _mw_adminimize_current_user_has_role( $role )
			&& is_array( $disabled_admin_bar_option_[ $role ] )
		) {

			foreach ( $disabled_admin_bar_option_[ $role ] as $admin_bar_item ) {
				$wp_admin_bar->remove_node( $admin_bar_item );
			}

		} // end if user roles
	}

}

add_action( 'admin_bar_menu', '_mw_adminimize_change_admin_bar_frontend', 99999 );
/**
 * Remove items in Admin Bar for current role of current active user in front end area
 * Exclude Super Admin, if active
 * Exclude Settings page of Adminimize
 *
 * @since   1.8.1  01/10/2013
 *
 * @param $wp_admin_bar
 *
 * @return null
 */
function _mw_adminimize_change_admin_bar_frontend( $wp_admin_bar ) {

	// works only for back end admin bar
	if ( is_admin() ) {
		return;
	}

	// Exclude super admin
	if ( _mw_adminimize_exclude_super_admin() ) {
		return;
	}

	$user_roles                          = _mw_adminimize_get_all_user_roles();
	$disabled_admin_bar_frontend_option_ = '';

	foreach ( $user_roles as $role ) {

		$disabled_admin_bar_frontend_option_[ $role ] = _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_admin_bar_frontend_' . $role . '_items'
		);
	}

	foreach ( $user_roles as $role ) {

		if ( ! isset( $disabled_admin_bar_frontend_option_[ $role ][ '0' ] ) ) {
			$disabled_admin_bar_frontend_option_[ $role ][ '0' ] = '';
		}
	}

	foreach ( $user_roles as $role ) {
		$user = wp_get_current_user();

		if ( is_array( $user->roles )
			&& in_array( $role, $user->roles )
			&& _mw_adminimize_current_user_has_role( $role )
			&& is_array( $disabled_admin_bar_frontend_option_[ $role ] )
		) {

			foreach ( $disabled_admin_bar_frontend_option_[ $role ] as $admin_bar_item ) {
				$wp_admin_bar->remove_node( $admin_bar_item );
			}

		} // end if user roles
	}

}