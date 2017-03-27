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
	return;
}

// If is AJAX Call.
if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
	return;
}

add_action( 'wp_dashboard_setup', '_mw_adminimize_update_dashboard_widgets', 9998 );
/**
 * Write dashboard widgets in settings.
 *
 * @return bool
 */
function _mw_adminimize_update_dashboard_widgets() {

	// Only manage options users have the chance to update the settings.
	if ( ! current_user_can( 'manage_options' ) ) {
		return FALSE;
	}

	$adminimizeoptions                                      = _mw_adminimize_get_option_value();
	$adminimizeoptions[ 'mw_adminimize_dashboard_widgets' ] = _mw_adminimize_get_dashboard_widgets();

	return _mw_adminimize_update_option( $adminimizeoptions );
}

// Return registered widgets; only on page index/dashboard :(
add_action( 'wp_dashboard_setup', '_mw_adminimize_dashboard_setup', 9999 );
/**
 * Set dashboard widget options.
 */
function _mw_adminimize_dashboard_setup() {

	// exclude super admin
	if ( _mw_adminimize_exclude_super_admin() ) {
		return;
	}

	$user_roles = _mw_adminimize_get_all_user_roles();

	$disabled_dashboard_option_ = array();
	foreach ( $user_roles as $role ) {
		$disabled_dashboard_option_[ $role ] = _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_dashboard_option_' . $role . '_items'
		);
	}

	$user              = wp_get_current_user();
	// Support Multiple Roles for users.
	if ( _mw_adminimize_get_option_value( 'mw_adminimize_multiple_roles' ) && 1 < count( $user->roles ) ) {
		$disabled_dashboard_option_ = _mw_adminimize_get_duplicate( $disabled_dashboard_option_ );
	}

	foreach ( $user_roles as $role ) {
		if ( ! isset( $disabled_dashboard_option_[ $role ][ '0' ] ) ) {
			$disabled_dashboard_option_[ $role ][ '0' ] = '';
		}
	}

	// Get all widgets.
	$widgets = _mw_adminimize_get_dashboard_widgets();
	// Get current user data.
	$user = wp_get_current_user();
	// If the current user is not inside the roles, abort.
	if ( ! $user->roles ) {
		return;
	}

	foreach ( $user_roles as $role ) {

		if ( is_array( $user->roles )
		     && is_array( $disabled_dashboard_option_[ $role ] )
		     && in_array( $role, $user->roles, TRUE )
		     && _mw_adminimize_current_user_has_role( $role )
		) {
			foreach ( (array) $disabled_dashboard_option_[ $role ] as $widget ) {
				if ( isset( $widgets[ $widget ][ 'context' ] ) ) {
					remove_meta_box( $widget, 'dashboard', $widgets[ $widget ][ 'context' ] );
				}
			}
		}
	}

}

add_action( 'admin_head-index.php', '_mw_adminimize_remove_custom_panels', 99 );
/**
 * Add custom options to the head head to hide it via css.
 *
 * @since 2017-01-05
 */
function _mw_adminimize_remove_custom_panels() {

	// exclude super admin
	if ( _mw_adminimize_exclude_super_admin() ) {
		return;
	}

	$options = _mw_adminimize_get_option_value( '_mw_adminimize_own_dashboard_values' );

	if ( empty( $options ) ) {
		return;
	}

	// Get current user data.
	$user = wp_get_current_user();
	if ( ! $user->roles ) {
		return;
	}

	// Get settings for the roles.
	$disabled_dashboard_option_ = array();
	foreach ( $user->roles as $role ) {
		$disabled_dashboard_option_[] = _mw_adminimize_get_option_value( 'mw_adminimize_disabled_dashboard_option_' . $role . '_items' );
	}

	// Support Multiple Roles for users.
	if ( _mw_adminimize_get_option_value( 'mw_adminimize_multiple_roles' ) && 1 < count( $user->roles ) ) {
		$disabled_dashboard_option_ = _mw_adminimize_get_duplicate( $disabled_dashboard_option_ );
	}

	if ( empty( $disabled_dashboard_option_[ 0 ] ) ) {
		return;
	}

	$selectors = implode( ', ', $disabled_dashboard_option_[ 0 ] );
	echo '<!-- Set Adminimize dashboard options -->' . "\n";
	echo '<style type="text/css">' . esc_attr( $selectors ) . ' {display:none !important;}</style>' . "\n";
}

/**
 * Get all registered dashboard widgets.
 *
 * @return array
 */
function _mw_adminimize_get_dashboard_widgets() {

	global $wp_meta_boxes;

	$widgets = array();
	if ( ! isset( $wp_meta_boxes[ 'dashboard' ] ) ) {
		return $widgets;
	}

	foreach ( (array) $wp_meta_boxes[ 'dashboard' ] as $context => $datas ) {
		foreach ( (array) $datas as $priority => $data ) {
			foreach ( (array) $data as $widget => $value ) {
				$widgets[ $widget ] = array(
					'id'       => $widget,
					'title'    => strip_tags(
						preg_replace( '/( |)<span.*span>/im', '', $value[ 'title' ] )
					),
					'context'  => $context,
					'priority' => $priority,
				);
			}
		}
	}

	return $widgets;
}
