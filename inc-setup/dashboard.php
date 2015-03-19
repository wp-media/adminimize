<?php
/**
 * @package     Adminimize
 * @subpackage  Dashboard Setup
 * @author      Frank Bültge
 */
if ( ! function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a part of plugin, not much I can do when called directly.";
	exit;
}

if ( ! is_admin() ) {
	return NULL;
}

// retrun registered widgets; only on page index/dashboard :(
add_action( 'wp_dashboard_setup', '_mw_adminimize_dashboard_setup', 99 );

function _mw_adminimize_dashboard_setup() {

	if ( is_multisite() && is_plugin_active_for_network( MW_ADMIN_FILE ) ) {
		$adminimizeoptions = get_site_option( 'mw_adminimize' );
	} else {
		$adminimizeoptions = get_option( 'mw_adminimize' );
	}

	$widgets                                                = _mw_adminimize_get_dashboard_widgets();
	$adminimizeoptions[ 'mw_adminimize_dashboard_widgets' ] = $widgets;

	if ( current_user_can( 'manage_options' ) ) {

		if ( is_multisite() && is_plugin_active_for_network( MW_ADMIN_FILE ) ) {
			update_site_option( 'mw_adminimize', $adminimizeoptions );
		} else {
			update_option( 'mw_adminimize', $adminimizeoptions );
		}
	}

	// exclude super admin
	if ( _mw_adminimize_exclude_super_admin() ) {
		return NULL;
	}

	$user_roles = _mw_adminimize_get_all_user_roles();

	foreach ( $user_roles as $role ) {
		$disabled_dashboard_option_[ $role ] = _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_dashboard_option_' . $role . '_items'
		);
	}

	foreach ( $user_roles as $role ) {
		if ( ! isset( $disabled_dashboard_option_[ $role ][ '0' ] ) ) {
			$disabled_dashboard_option_[ $role ][ '0' ] = '';
		}
	}

	foreach ( $user_roles as $role ) {
		$user = wp_get_current_user();

		if ( is_array( $user->roles ) && in_array( $role, $user->roles ) ) {
			if ( current_user_can( $role ) && is_array( $disabled_dashboard_option_[ $role ] ) ) {
				foreach ( $disabled_dashboard_option_[ $role ] as $widget ) {
					if ( isset( $widgets[ $widget ][ 'context' ] ) ) {
						remove_meta_box( $widget, 'dashboard', $widgets[ $widget ][ 'context' ] );
					}
				}
			}
		}
	}

}

function _mw_adminimize_get_dashboard_widgets() {

	global $wp_meta_boxes;

	$widgets = array();
	if ( isset( $wp_meta_boxes[ 'dashboard' ] ) ) {

		foreach ( $wp_meta_boxes[ 'dashboard' ] as $context => $datas ) {
			foreach ( $datas as $priority => $data ) {
				foreach ( $data as $widget => $value ) {
					$widgets[ $widget ] = array(
						'id'       => $widget,
						'title'    => strip_tags(
							preg_replace( '/( |)<span.*span>/im', '', $value[ 'title' ] )
						),
						'context'  => $context,
						'priority' => $priority
					);
				}
			}
		}

	}

	return $widgets;
}
