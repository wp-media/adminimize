<?php
/**
 * @package    Adminimize
 * @subpackage Widget Setup
 * @author     Frank Bültge
 * @since      1.8.1  01/10/2013
 */

if ( ! function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a part of plugin, not much I can do when called directly.";
	exit;
}

if ( ! is_admin() ) {
	return;
}

// If is AJAX Call.
if ( defined('DOING_AJAX') && DOING_AJAX ) {
	return;
}

function _mw_adminimize_get_all_widgets() {

	global $wp_widget_factory;

	if ( is_object( $wp_widget_factory ) ) {
		return $wp_widget_factory->widgets;
	}

	return FALSE;
}

function _mw_adminimize_get_registered_widgets() {

	global $wp_registered_widgets;

	return $wp_registered_widgets;
}

function _mw_adminimize_get_sidebars_widgets() {

	global $sidebars_widgets;

	return $sidebars_widgets;
}

function _mw_adminimize_get_registered_sidebars() {
	global $wp_registered_sidebars;

	return $wp_registered_sidebars;
}

/**
 * Doing on load of widgets.php
 *
 * @return  void
 */
add_action( 'after_setup_theme', '_mw_adminimize_on_widgets_init' );
function _mw_adminimize_on_widgets_init() {

	if ( is_admin() && 'widgets.php' === $GLOBALS[ 'pagenow' ] ) {
		add_action( 'widgets_init', '_mw_adminimize_unregister_widgets' );
		add_action( 'widgets_init', '_mw_adminimize_unregister_sidebars', 9999 );
	}
}

/**
 * Remove widgets, areas for different roles
 *
 * @return  void
 */
function _mw_adminimize_unregister_widgets() {

	// Get settings.
	$adminimizeoptions = _mw_adminimize_get_option_value();

	// Update settings.
	_mw_adminimize_update_option( $adminimizeoptions );

	// exclude super admin
	if ( _mw_adminimize_exclude_super_admin() ) {
		return NULL;
	}

	$user_roles = _mw_adminimize_get_all_user_roles();

	foreach ( $user_roles as $role ) {
		$disabled_widget_option_[ $role ] = _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_widget_option_' . $role . '_items'
		);
	}

	foreach ( $user_roles as $role ) {
		if ( ! isset( $disabled_widget_option_[ $role ][ '0' ] ) ) {
			$disabled_widget_option_[ $role ][ '0' ] = '';
		}
	}

	foreach ( $user_roles as $role ) {
		$user = wp_get_current_user();

		if ( is_array( $user->roles ) && in_array( $role, $user->roles ) ) {

			if ( _mw_adminimize_current_user_has_role( $role ) && is_array( $disabled_widget_option_[ $role ] ) ) {
				foreach ( $disabled_widget_option_[ $role ] as $widgets ) {
					unregister_widget( $widgets );
					$GLOBALS[ 'wp_widget_factory' ]->unregister( $widgets );
					//wp_unregister_sidebar_widget( 'Monster_Widget' );
				}
			}

		} // end if user roles
	}

}

/**
 * Remove sidebars for different roles
 *
 * @return  void
 */
function _mw_adminimize_unregister_sidebars() {

	// Get settings.
	$adminimizeoptions = _mw_adminimize_get_option_value();

	// Get settings.
	_mw_adminimize_update_option( $adminimizeoptions );

	// exclude super admin
	if ( _mw_adminimize_exclude_super_admin() ) {
		return NULL;
	}

	$user_roles = _mw_adminimize_get_all_user_roles();

	foreach ( $user_roles as $role ) {
		$disabled_widget_option_[ $role ] = _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_widget_option_' . $role . '_items'
		);
	}

	foreach ( $user_roles as $role ) {
		if ( ! isset( $disabled_widget_option_[ $role ][ '0' ] ) ) {
			$disabled_widget_option_[ $role ][ '0' ] = '';
		}
	}

	foreach ( $user_roles as $role ) {
		$user = wp_get_current_user();

		if ( is_array( $user->roles ) && in_array( $role, $user->roles ) ) {

			if ( _mw_adminimize_current_user_has_role( $role ) && is_array( $disabled_widget_option_[ $role ] ) ) {
				foreach ( $disabled_widget_option_[ $role ] as $sidebar ) {
					unregister_sidebar( $sidebar );
				}
			}

		} // end if user roles
	}

}
