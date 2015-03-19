<?php
/**
 * @package     Adminimize
 * @subpackage  Remove Admin Bar of WP 3.3 Setup
 * @author      Frank Bültge
 */
if ( ! function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a part of plugin, not much I can do when called directly.";
	exit;
}

// on init of WordPress
add_action( 'init', '_mw_adminimize_remove_admin_bar', 0 );

/**
 * Change the var of Admin Bar in WP 3.3
 *
 * @param array $admin_bar_keys
 */
function _mw_adminimize_customize_admin_bar( $admin_bar_keys = array() ) {

	if ( ! is_admin_bar_showing() ) {
		return;
	}

	global $wp_admin_bar;

	foreach ( $admin_bar_keys as $key ) {
		$wp_admin_bar->remove_menu( $key );
	}
}

/*
 * Remove my account item in admin bar >3.3
 */
function _mw_adminimize_remove_my_account() {

	_mw_adminimize_customize_admin_bar( array( 'my-account' ) );
}

/**
 * Add Logout link to admin abr in wp 3.3
 *
 * @param $wp_admin_bar
 */
function _mw_adminimize_add_logout( $wp_admin_bar ) {

	$user_id                    = get_current_user_id();
	$_mw_adminimize_ui_redirect = _mw_adminimize_get_option_value( '_mw_adminimize_ui_redirect' );
	if ( '1' === $_mw_adminimize_ui_redirect ) {
		$redirect = '&amp;redirect_to=' . get_option( 'siteurl' );
	} else {
		$redirect = '';
	}

	if ( ! $user_id ) {
		return;
	}

	$wp_admin_bar->add_menu(
		array(
			'id'     => 'mw-account',
			'parent' => 'top-secondary',
			'title'  => __( 'Log Out' ),
			'href'   => wp_logout_url() . $redirect,
		)
	);
}

function _mw_adminimize_add_user_logout( $wp_admin_bar ) {

	$user_id                    = get_current_user_id();
	$current_user               = wp_get_current_user();
	$_mw_adminimize_ui_redirect = _mw_adminimize_get_option_value( '_mw_adminimize_ui_redirect' );
	if ( '1' === $_mw_adminimize_ui_redirect ) {
		$redirect = '&amp;redirect_to=' . get_option( 'siteurl' );
	} else {
		$redirect = '';
	}

	if ( ! $user_id ) {
		return;
	}

	$user_info = $current_user->display_name;

	$wp_admin_bar->add_menu(
		array(
			'id'     => 'mw-account',
			'parent' => 'top-secondary',
			'title'  => $user_info . ' ' . __( 'Log Out' ),
			'href'   => wp_logout_url() . $redirect,
		)
	);
}

function _mw_adminimize_set_menu_option_33() {

	// exclude super admin
	if ( _mw_adminimize_exclude_super_admin() ) {
		return NULL;
	}

	$user_roles = _mw_adminimize_get_all_user_roles();

	foreach ( $user_roles as $role ) {
		$disabled_menu_[ $role ]    = _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_menu_' . $role . '_items'
		);
		$disabled_submenu_[ $role ] = _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_submenu_' . $role . '_items'
		);
	}

	$_mw_adminimize_user_info   = _mw_adminimize_get_option_value( '_mw_adminimize_user_info' );
	// change user-info
	switch ( $_mw_adminimize_user_info ) {
		case 1:
			add_action( 'wp_before_admin_bar_render', '_mw_adminimize_remove_my_account' );
			break;
		case 2:
			add_action( 'wp_before_admin_bar_render', '_mw_adminimize_remove_my_account' );
			add_action( 'admin_bar_menu', '_mw_adminimize_add_logout', 0 );
			break;
		case 3:
			add_action( 'wp_before_admin_bar_render', '_mw_adminimize_remove_my_account' );
			add_action( 'admin_bar_menu', '_mw_adminimize_add_user_logout', 0 );
			break;
	}
}

/**
 * Remove Admin Bar
 */
function _mw_adminimize_remove_admin_bar() {

	// exclude super admin
	if ( _mw_adminimize_exclude_super_admin() ) {
		return NULL;
	}

	global $wp_version;

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

	$remove_adminbar = FALSE;
	// new 1.7.8
	foreach ( $user_roles as $role ) {
		$user = wp_get_current_user();
		if ( is_array( $user->roles ) && in_array( $role, $user->roles ) ) {
			if ( current_user_can( $role )
				&& isset( $disabled_global_option_[ $role ] )
				&& is_array( $disabled_global_option_[ $role ] )
			) {
				if ( _mw_adminimize_recursive_in_array( '.show-admin-bar', $disabled_global_option_[ $role ] ) ) {
					$remove_adminbar = TRUE;
				}
			}
		}
	}

	if ( $remove_adminbar ) {
		// for deactivate admin bar in WP smaller WP 3.3
		if ( version_compare( $wp_version, '3.3alpha', '<=' ) ) {
			add_filter( 'show_admin_bar', '__return_false' );
			wp_deregister_script( 'admin-bar' );
			wp_deregister_style( 'admin-bar' );
			remove_action( 'wp_footer', 'wp_admin_bar_render', 1000 );
			remove_action( 'wp_head', '_admin_bar_bump_cb' );
		} else {
			if ( ! is_admin_bar_showing() ) {
				return FALSE;
			}

			add_filter( 'show_admin_bar', '__return_false' );
			add_filter( 'wp_admin_bar_class', '__return_false' );
			add_filter( 'show_wp_pointer_admin_bar', '__return_false' );
			wp_deregister_script( 'admin-bar' );
			wp_deregister_style( 'admin-bar' );
			remove_action( 'init', '_wp_admin_bar_init' );
			remove_action( 'wp_footer', 'wp_admin_bar_render', 1000 );
			remove_action( 'admin_footer', 'wp_admin_bar_render', 1000 );

			// maybe also: 'wp_head'
			foreach ( array( 'wp_head', 'admin_head' ) as $hook ) {
				add_action(
					$hook,
					create_function(
						'',
						"echo '<style>body.admin-bar, body.admin-bar #wpcontent, body.admin-bar #adminmenu {
							 padding-top: 0 !important;
						}
						html.wp-toolbar {
							padding-top: 0 !important;
						}</style>';"
					)
				);
			}

			add_action( 'in_admin_header', '_mw_adminimize_restore_links' );
		} // end else version 3.3
	} // end if $remove_adminbar TRUE

	return NULL;
}

/**
 * Add Site Link in Menu
 */
function _mw_adminimize_restore_links() {

	$_mw_adminimize_user_info = _mw_adminimize_get_option_value( '_mw_adminimize_user_info' );
	?>
	<style type="text/css">
		#mw_adminimize_admin_bar {
			left: 0;
			right: 0;
			height: 33px;
			z-index: 999;
			border-bottom: 1px solid #dfdfdf;
		}

		#mw_adminimize_admin_bar #mw_title {
			font-family: Georgia, "Times New Roman", Times, serif;
			font-size: 16px;
			color: #464646;
			text-decoration: none;
			padding-top: 8px;
			display: block;
			float: left;
		}

		#mw_adminimize_admin_bar #mw_title:hover {
			text-decoration: underline;
		}

		#mw_adminimize_admin_bar #mw_adminimize_login {
			padding: 8px 15px 0 0;
			display: block;
			float: right;
		}
	</style>
	<div id="mw_adminimize_admin_bar">
		<?php echo '<a id="mw_title" href="' . home_url() . '" title="' . __(
				get_bloginfo( 'name' )
			) . '" target="_blank">' . get_bloginfo( 'name' ) . '</a>';
		?>
		<div id="mw_adminimize_login">
			<?php
			wp_get_current_user();
			$current_user = wp_get_current_user();
			if ( empty( $_mw_adminimize_user_info ) || 0 == $_mw_adminimize_user_info || 3 == $_mw_adminimize_user_info ) {
				if ( ! ( $current_user instanceof WP_User ) ) {
					return;
				}
				echo ' ' . $current_user->user_login . ' ';

				if ( is_multisite() && is_super_admin() ) {
					if ( ! is_network_admin() ) {
						echo '| <a href="' . network_admin_url() . '" title="' . esc_attr__(
								'Network Admin'
							) . '">' . __( 'Network Admin' ) . '</a>';
					} else {
						echo '| <a href="' . get_dashboard_url( get_current_user_id() ) . '" title="' . esc_attr__(
								'Site Admin'
							) . '">' . __( 'Site Admin' ) . '</a>';
					}
				}
			}

			if ( empty( $_mw_adminimize_user_info ) || 0 == $_mw_adminimize_user_info || 2 == $_mw_adminimize_user_info || 3 == $_mw_adminimize_user_info ) {
				?>  | <?php echo '<a href="' . wp_logout_url() . '" title="' . esc_attr__( 'Log Out' ) . '">' . __(
						'Log Out'
					) . '</a>';
			}
			?>
		</div>
	</div>
<?php
}

