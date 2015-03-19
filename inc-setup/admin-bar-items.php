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

add_action( 'wp_before_admin_bar_render', '_mw_adminimize_get_admin_bar_nodes' );
/**
 * Get all admin bar items and write in a options of Adminimize settings array
 *
 * @since   1.8.1  01/10/2013
 * @return  void
 */
function _mw_adminimize_get_admin_bar_nodes() {

	// On Fron end
	if ( ! function_exists( 'get_current_screen' ) ) {
		return NULL;
	}

	// Get admin page
	$screen = get_current_screen();
	if ( ! isset( $screen->id ) ) {
		return NULL;
	}

	// Update only on Adminimize Settings page
	if ( FALSE === strpos( $screen->id, 'adminimize' ) ) {
		return NULL;
	}

	if ( ! is_admin() ) {
		return NULL;
	}

	global $wp_admin_bar;
	// @see: http://codex.wordpress.org/Function_Reference/get_nodes 
	$all_toolbar_nodes = $wp_admin_bar->get_nodes();

	if ( $all_toolbar_nodes ) {
		// get all options
		if ( is_multisite() && is_plugin_active_for_network( MW_ADMIN_FILE ) ) {
			$adminimizeoptions = get_site_option( 'mw_adminimize' );
		} else {
			$adminimizeoptions = get_option( 'mw_adminimize' );
		}

		// add admin bar array
		$adminimizeoptions[ 'mw_adminimize_admin_bar_nodes' ] = $all_toolbar_nodes;

		// update options
		if ( is_multisite() && is_plugin_active_for_network( MW_ADMIN_FILE ) ) {
			update_site_option( 'mw_adminimize', $adminimizeoptions );
		} else {
			update_option( 'mw_adminimize', $adminimizeoptions );
		}
	}
}

/**
 * Get all admin bar items from settings
 *
 * @since   1.8.1  01/10/2013
 * @return  Array | String
 */
function _mw_adminimize_get_admin_bar_items() {

	$admin_bar_items = _mw_adminimize_get_option_value( 'mw_adminimize_admin_bar_nodes' );

	return $admin_bar_items;
}

add_action( 'admin_bar_menu', '_mw_adminimize_change_admin_bar', 99999 );
/**
 * Remove items in Admin Bar for current role of current active user
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

	// Don't filter on settings page
	if ( isset( $GLOBALS[ 'current_screen' ]->base )
		&& 'settings_page_adminimize/adminimize' == $GLOBALS[ 'current_screen' ]->base
	) {
		return NULL;
	}

	// Exclude super admin
	if ( _mw_adminimize_exclude_super_admin() ) {
		return NULL;
	}

	$user_roles = _mw_adminimize_get_all_user_roles();
	$disabled_admin_bar_option_ = '';

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

		if ( is_array( $user->roles ) && in_array( $role, $user->roles ) ) {

			if ( current_user_can( $role ) && is_array( $disabled_admin_bar_option_[ $role ] ) ) {

				foreach ( $disabled_admin_bar_option_[ $role ] as $admin_bar_item ) {
					$wp_admin_bar->remove_node( $admin_bar_item );
				}

			} // end if

		} // end if user roles
	}

}