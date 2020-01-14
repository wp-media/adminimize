<?php
/**
 * Remove the admin notices from global options settings
 *
 * @since  2015-12-09
 */
if ( ! function_exists( 'add_action' ) ) {
	die( "Hi there!  I'm just a part of plugin, not much I can do when called directly." );
}

// Need only on admin area
if ( ! is_admin() ) {
	return;
}

// If is AJAX Call.
if ( defined('DOING_AJAX') && DOING_AJAX ) {
	return;
}

// If is AJAX Call.
if ( defined('DOING_AJAX') && DOING_AJAX ) {
	return;
}

add_action( 'admin_init', '_mw_adminimize_init_to_remove_admin_notices' );
/**
 * Fire all hooks to remove admin notices.
 */
function _mw_adminimize_init_to_remove_admin_notices() {

	if ( _mw_adminimize_check_to_remove_admin_notices() ) {
		add_action( 'admin_head', '_mw_adminimize_remove_admin_notices', PHP_INT_MAX + 1 );
	}
}

/**
 * Remove Admin Notices.
 *
 * @return boolean
 */
function _mw_adminimize_check_to_remove_admin_notices() {

	// Exclude super admin.
	if ( _mw_adminimize_exclude_super_admin() ) {
		return false;
	}

	$user_roles = _mw_adminimize_get_all_user_roles();

	foreach ( $user_roles as $role ) {
		$disabled_global_option_[ $role ] = _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_global_option_' . $role . '_items'
		);
	}

	foreach ( $user_roles as $role ) {
		if ( ! isset( $disabled_global_option_[ $role ][ '0' ] ) ) {
			$disabled_global_option_[ $role ][ '0' ] = '';
		}
	}

	$remove_admin_notices = false;
	foreach ( $user_roles as $role ) {

		$user = wp_get_current_user();

		if ( is_array( $user->roles ) && in_array( $role, $user->roles ) ) {
			if ( _mw_adminimize_current_user_has_role( $role )
				&& isset( $disabled_global_option_[ $role ] )
				&& is_array( $disabled_global_option_[ $role ] )
			) {
				$remove_admin_notices = _mw_adminimize_recursive_in_array(
					'.admin-notices', $disabled_global_option_[ $role ]
				);
			}
		}
	}

	if ( $remove_admin_notices ) {
		return true;
	}

	return false;
}

/**
 * Remove different admin notices.
 */
function _mw_adminimize_remove_admin_notices() {

	remove_action( 'admin_notices', 'update_nag', 3 );
	remove_action( 'admin_notices', 'maintenance_nag', 10 );
	remove_action( 'admin_notices', 'new_user_email_admin_notice' );
	remove_action( 'admin_notices', 'site_admin_notice' );

	// @ToDo, if we will use this.
	// Catch all admin notices.
	/*
	add_action( 'admin_notices', function () {
		ob_start();
	}, PHP_INT_MAX + 1 );
	$adm_notices = trim( ob_get_clean() );
	$adm_notices = preg_replace(
		'/(\sclass=["\'][^"\']*?notice)(["\'\s])/',
		'$1 inline$2',
		$adm_notices
	);
	*/
}