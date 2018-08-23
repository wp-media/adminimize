<?php
/**
 * Functions to remove the admin bar.
 *
 * @package     Adminimize
 * @subpackage  Remove Admin Bar of > WP 3.3 Setup
 * @author      Frank Bültge
 */

if ( ! function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a part of plugin, not much I can do when called directly.";
	exit;
}

// on init of WordPress.
add_action( 'init', '_mw_adminimize_remove_admin_bar', 0 );

/**
 * Change the var of Admin Bar in WP 3.3
 *
 * @param array $admin_bar_keys
 */
function _mw_adminimize_customize_admin_bar( array $admin_bar_keys ) {

	if ( ! is_admin_bar_showing() ) {
		return;
	}

	/**
	 * Reference to the doc to know the method.
	 *
	 * @var \WP_Admin_Bar $wp_admin_bar
	 */
	global $wp_admin_bar;

	foreach ( $admin_bar_keys as $key ) {
		$wp_admin_bar->remove_menu( $key );
	}
}

/**
 * Remove my account item in admin bar >3.3
 */
function _mw_adminimize_remove_my_account() {

	_mw_adminimize_customize_admin_bar( array( 'my-account' ) );
}

/**
 * Add Logout link to admin abr in wp 3.3
 *
 * @param $wp_admin_bar WP_Admin_Bar
 */
function _mw_adminimize_add_logout( $wp_admin_bar ) {

	$user_id                    = get_current_user_id();
	$_mw_adminimize_ui_redirect = (int) _mw_adminimize_get_option_value( '_mw_adminimize_ui_redirect' );
	$redirect                   = '';
	if ( 1 === $_mw_adminimize_ui_redirect ) {
		$redirect = '&amp;redirect_to=' . get_option( 'siteurl' );
	}

	if ( ! $user_id ) {
		return;
	}

	$wp_admin_bar->add_menu(
		array(
			'id'     => 'mw-account',
			'parent' => 'top-secondary',
			'title'  => esc_attr__( 'Log Out' ),
			'href'   => wp_logout_url() . $redirect,
		)
	);
}

/**
 * Add stylesheet for see the the admin bar item also on mobile.
 */
function _mw_adminimize_admin_bar_style() {
	?>
	<style type="text/css">
		#wpadminbar #wp-admin-bar-mw-account { display: block; }
	</style>
	<?php
}

/**
 * Add Logout link include user info.
 *
 * @param $wp_admin_bar WP_Admin_Bar
 */
function _mw_adminimize_add_user_logout( $wp_admin_bar ) {

	$user_id                    = get_current_user_id();
	$current_user               = wp_get_current_user();
	$_mw_adminimize_ui_redirect = (int) _mw_adminimize_get_option_value( '_mw_adminimize_ui_redirect' );
	$redirect                   = '';
	if ( 1 === $_mw_adminimize_ui_redirect ) {
		$redirect = '&amp;redirect_to=' . get_option( 'siteurl' );
	}

	if ( ! $user_id ) {
		return;
	}

	$user_info = $current_user->display_name;

	$wp_admin_bar->add_menu(
		array(
			'id'     => 'mw-account',
			'parent' => 'top-secondary',
			'title'  => $user_info . ' ' . esc_attr__( 'Log Out' ),
			'href'   => wp_logout_url() . $redirect,
		)
	);
}

add_action( 'init', '_mw_adminimize_set_logout_menu', 2 );
/**
 * Change logout, user info link in Admin bar.
 *
 * @return void
 */
function _mw_adminimize_set_logout_menu() {

	if ( ! is_user_logged_in() ) {
		return;
	}

	// exclude super admin.
	if ( _mw_adminimize_exclude_super_admin() ) {
		return;
	}

	// Leave the settings screen from Adminimize to see all areas on settings.
	if ( _mw_adminimize_exclude_settings_page() ) {
		return;
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

	$_mw_adminimize_user_info = (int) _mw_adminimize_get_option_value( '_mw_adminimize_user_info' );
	// change user-info.
	switch ( $_mw_adminimize_user_info ) {
		case 1:
			add_action( 'wp_before_admin_bar_render', '_mw_adminimize_remove_my_account' );
			break;
		case 2:
			add_action( 'wp_before_admin_bar_render', '_mw_adminimize_remove_my_account' );
			add_action( 'admin_bar_menu', '_mw_adminimize_add_logout', 0 );
			add_action( 'wp_head', '_mw_adminimize_admin_bar_style' );
			add_action( 'admin_head', '_mw_adminimize_admin_bar_style' );
			break;
		case 3:
			add_action( 'wp_before_admin_bar_render', '_mw_adminimize_remove_my_account' );
			add_action( 'admin_bar_menu', '_mw_adminimize_add_user_logout', 0 );
			add_action( 'wp_head', '_mw_adminimize_admin_bar_style' );
			add_action( 'admin_head', '_mw_adminimize_admin_bar_style' );
			break;
	}
}

/**
 * Remove Admin Bar
 *
 * @return void
 */
function _mw_adminimize_remove_admin_bar() {

	if ( ! is_user_logged_in() ) {
		return;
	}

	// exclude super admin.
	if ( _mw_adminimize_exclude_super_admin() ) {
		return;
	}

	// Leave the settings screen from Adminimize to see all areas on settings.
	if ( _mw_adminimize_exclude_settings_page() ) {
		return;
	}

	$user_roles              = _mw_adminimize_get_all_user_roles();
	$disabled_global_option_ = array();

	foreach ( $user_roles as $role ) {
		$disabled_global_option_[ $role ] = (array) _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_global_option_' . $role . '_items'
		);
	}

	$mw_global_options = array();
	$user              = wp_get_current_user();

	foreach ( $user_roles as $role ) {

		if ( in_array( $role, $user->roles, true )
			&& _mw_adminimize_current_user_has_role( $role )
		) {
			// Create array about all items with all affected roles, important for multiple roles.
			foreach ( $disabled_global_option_[ $role ] as $global_item ) {
				$mw_global_options[] = $global_item;
			}
		}
	}

	// Support Multiple Roles for users.
	if ( _mw_adminimize_get_option_value( 'mw_adminimize_multiple_roles' ) && 1 < count( $user->roles ) ) {
		$mw_global_options = _mw_adminimize_get_duplicate( $mw_global_options );
	}

	$remove_adminbar = false;
	// Check for admin bar selector to set to remove the Admin Bar.
	if ( _mw_adminimize_recursive_in_array( '.show-admin-bar', $mw_global_options ) ) {
		$remove_adminbar = true;
	}

	if ( $remove_adminbar ) {
		if ( ! is_admin_bar_showing() ) {
			return;
		}

		add_filter( 'show_admin_bar', '__return_false' );
		add_filter( 'wp_admin_bar_class', '__return_false' );
		add_filter( 'show_wp_pointer_admin_bar', '__return_false' );
		wp_deregister_script( 'admin-bar' );
		wp_deregister_style( 'admin-bar' );
		remove_action( 'init', '_wp_admin_bar_init' );
		remove_action( 'wp_footer', 'wp_admin_bar_render', 1000 );
		remove_action( 'admin_footer', 'wp_admin_bar_render', 1000 );

		// maybe also: 'wp_head'.
		foreach ( array( 'wp_head', 'admin_head' ) as $hook ) {
			add_action(
				$hook,
				function() {
					echo '<style>body.admin-bar, body.admin-bar #wpcontent, body.admin-bar #adminmenu {
						 padding-top: 0 !important;
					}
					html.wp-toolbar {
						padding-top: 0 !important;
					}</style>';
				}
			);
		}

		add_action( 'in_admin_header', '_mw_adminimize_restore_links' );

	} // end if $remove_adminbar TRUE
}

/**
 * Add Site Link in Menu
 */
function _mw_adminimize_restore_links() {

	$_mw_adminimize_user_info = (int) _mw_adminimize_get_option_value( '_mw_adminimize_user_info' );
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
		<?php
		echo '<a id="mw_title" href="' . home_url() . '" title="' . esc_attr__(
			get_bloginfo( 'name' )
		) . '" target="_blank">' . get_bloginfo( 'name' ) . '</a>';
		?>
		<div id="mw_adminimize_login">
			<?php
			$current_user = wp_get_current_user();
			if ( empty( $_mw_adminimize_user_info ) || 0 === $_mw_adminimize_user_info
				|| 3 === $_mw_adminimize_user_info
			) {
				if ( ! ( $current_user instanceof WP_User ) ) {
					return;
				}
				echo ' ' . $current_user->user_login . ' ';

				if ( is_multisite() && is_super_admin() ) {
					if ( ! is_network_admin() ) {
						echo '| <a href="' . network_admin_url() . '" title="' . esc_attr__(
							'Network Admin'
						) . '">' . esc_attr__( 'Network Admin' ) . '</a>';
					} else {
						echo '| <a href="' . get_dashboard_url( get_current_user_id() ) . '" title="' . esc_attr__(
							'Site Admin'
						) . '">' . esc_attr__( 'Site Admin' ) . '</a>';
					}
				}
			}

			if ( empty( $_mw_adminimize_user_info ) || 0 === $_mw_adminimize_user_info
				|| 2 === $_mw_adminimize_user_info
				|| 3 === $_mw_adminimize_user_info
			) {
				?>
				|
				<?php
					echo '<a href="' . wp_logout_url() . '" title="' . esc_attr__(
						'Log Out'
					) . '">' . esc_attr__(
						'Log Out'
					) . '</a>';
			}
			?>
		</div>
	</div>
	<?php
}

