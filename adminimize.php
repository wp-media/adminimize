<?php
/**
 * Plugin Name: Adminimize
 * Plugin URI:  https://wordpress.org/plugins/adminimize/
 * Text Domain: adminimize
 * Domain Path: /languages
 * Description: Visually compresses the administrative meta-boxes so that more admin page content can be initially seen. The plugin that lets you hide 'unnecessary' items from the WordPress administration menu, for all roles of your install. You can also hide post meta controls on the edit-area to simplify the interface. It is possible to simplify the admin in different for all roles.
 * Author:      Frank Bültge
 * Author URI:  http://bueltge.de/
 * Version:     1.11.2
 * License:     GPLv3+
 *
 * @package WordPress
 * @author  Frank Bültge <frank@bueltge.de>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 2016-12-04
 */

/**
 * The stylesheet and the initial idea is from Eric A. Meyer http://meyerweb.com/
 * I have written a plugin with many options on the basis idea
 * of differently user-right and a user-friendly range in admin-area via reduce areas.
 * :( grmpf i have so much wishes and hints form users, there use the plugin and
 *    it is not easy to development this on my free time.
 * Also I hate the source, old and hard to maintain, no OOP.
 */

if ( ! function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a part of plugin, not much I can do when called directly.";
	exit;
}

// plugin definitions
define( 'FB_ADMINIMIZE_BASENAME', plugin_basename( __FILE__ ) );
define( 'FB_ADMINIMIZE_BASEFOLDER', plugin_basename( dirname( __FILE__ ) ) );

/**
 * Return data from the plugin.
 *
 * @param string $value
 *
 * @return mixed
 */
function _mw_adminimize_get_plugin_data( $value = 'Version' ) {

	if ( ! function_exists( 'get_plugin_data' ) ) {
		require_once ABSPATH . '/wp-admin/includes/plugin.php';
	}

	$plugin_data = get_plugin_data( __FILE__ );

	return $plugin_data[ $value ];
}

/**
 * Load language files.
 */
function _mw_adminimize_textdomain() {

	load_plugin_textdomain(
		_mw_adminimize_get_plugin_data( 'TextDomain' ),
		FALSE,
		dirname( FB_ADMINIMIZE_BASENAME ) . _mw_adminimize_get_plugin_data( 'DomainPath' )
	);
}

/**
 * Exclude the Super Admin of Multisite.
 *
 * @return bool
 */
function _mw_adminimize_exclude_super_admin() {

	if ( ! function_exists( 'is_super_admin' ) ) {
		return FALSE;
	}

	if ( ! is_super_admin() ) {
		return FALSE;
	}

	if ( 1 === (int) _mw_adminimize_get_option_value( '_mw_adminimize_exclude_super_admin' ) ) {
		return TRUE;
	}

	return FALSE;
}

/**
 * Get the status, if is on the settings page.
 *
 * @return bool
 */
function _mw_adminimize_exclude_settings_page() {

	if ( ! is_admin() ) {
		return FALSE;
	}

	if ( defined('DOING_AJAX') && DOING_AJAX ) {
		return FALSE;
	}

	if ( ! isset( $_GET[ 'page' ] ) ) {
		$page = '';
	} else {
		$page = esc_attr( $_GET[ 'page' ] );
	}

	if ( ! function_exists( 'get_current_screen' ) ) {
		$screen = $page;
	} else {
		$screen = get_current_screen();
	}

	if ( ! isset( $screen->id ) ) {
		$screen = $page;
	} else {
		$screen = $screen->id;
	}

	// Don't filter on settings page
	return FALSE !== strpos( $screen, 'adminimize' );
}

/**
 * Get status, if the plugin active network wide.
 *
 * @return bool
 */
function _mw_adminimize_is_active_on_multisite() {

	if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	}

	if ( is_multisite() && is_plugin_active_for_network( FB_ADMINIMIZE_BASENAME ) ) {
		return TRUE;
	}

	return FALSE;
}

/**
 * Returns an array with all user roles(names) in it.
 * Inclusive self defined roles (for example with the 'Role Manager' plugin).
 *
 * @uses   $wp_roles
 * @return array $user_roles
 */
function _mw_adminimize_get_all_user_roles() {

	/** @var $wp_roles WP_Roles */
	global $wp_roles;

	$user_roles = array();

	if ( NULL !== $wp_roles->roles && is_array( $wp_roles->roles ) ) {
		foreach ( $wp_roles->roles as $role => $data ) {
			$user_roles[] = $role;
			//$data contains caps, maybe for later use..
		}
	}

	// Exclude the new bbPress roles.
	if ( ! _mw_adminimize_get_option_value( 'mw_adminimize_support_bbpress' ) ) {
		$user_roles = array_diff(
			$user_roles,
			array( 'bbp_keymaster', 'bbp_moderator', 'bbp_participant', 'bbp_spectator', 'bbp_blocked' )
		);
	}

	return $user_roles;
}

/**
 * _mw_adminimize_get_all_user_roles_names() - Returns an array with all user roles_names in it.
 * Inclusive self defined roles (for example with the 'Role Manager' plugin).
 *
 * @uses   $wp_roles
 * @return array $user_roles_names
 */
function _mw_adminimize_get_all_user_roles_names() {

	/** @var $wp_roles WP_Roles */
	global $wp_roles;
	$user_roles_names = array();

	foreach ( $wp_roles->role_names as $role_name => $data ) {

		$data               = translate_user_role( $data );
		$user_roles_names[] = $data;
	}

	// exclude the new bbPress roles
	if ( ! _mw_adminimize_get_option_value( 'mw_adminimize_support_bbpress' ) ) {
		$user_roles_names = array_diff(
			$user_roles_names,
			array(
				esc_attr__( 'Keymaster', 'bbpress' ),
				esc_attr__( 'Moderator', 'bbpress' ),
				esc_attr__( 'Participant', 'bbpress' ),
				esc_attr__( 'Spectator', 'bbpress' ),
				esc_attr__( 'Blocked', 'bbpress' ),
			)
		);
	}

	return $user_roles_names;
}

/**
 * return post type
 */
function _mw_adminimize_get_current_post_type() {

	global $post, $typenow, $current_screen;

	//we have a post so we can just get the post type from that
	if ( $post && $post->post_type ) {
		return $post->post_type;
	} //check the global $typenow - set in admin.php
	else if ( $typenow ) {
		return $typenow;
	} // check the global $current_screen object - set in sceen.php
	else if ( $current_screen && $current_screen->post_type ) {
		return $current_screen->post_type;
	} // lastly check the post_type querystring
	else if ( isset( $_REQUEST[ 'post_type' ] ) ) {
		return sanitize_key( $_REQUEST[ 'post_type' ] );
	}

	// we do not know the post type!
	return NULL;
}

/**
 * Check user-option and add new style.
 */
function _mw_adminimize_admin_init() {

	global $pagenow, $post_type, $menu, $submenu;

	if ( isset( $_GET[ 'post' ] ) && ! is_array( $_GET[ 'post' ] ) ) {
		$post_id = (int) esc_attr( $_GET[ 'post' ] );
	} elseif ( isset( $_POST[ 'post_ID' ] ) ) {
		$post_id = (int) esc_attr( $_POST[ 'post_ID' ] );
	} else {
		$post_id = 0;
	}

	$current_post_type = $post_type;
	if ( ! isset( $current_post_type ) || empty( $current_post_type ) ) {
		$current_post_type = get_post_type( $post_id );
	}
	if ( ! isset( $current_post_type ) || empty( $current_post_type ) ) {
		$current_post_type = _mw_adminimize_get_current_post_type();
	}
	if ( ! $current_post_type ) // set hard to post
	{
		$current_post_type = 'post';
	}

	// Get all user roles.
	$user_roles = _mw_adminimize_get_all_user_roles();

	// Get settings.
	$adminimizeoptions = _mw_adminimize_get_option_value();

	// pages for post type Post
	$def_post_pages              = array( 'edit.php', 'post.php', 'post-new.php' );
	$def_post_types              = array( 'post' );
	$disabled_metaboxes_post_all = array();
	// pages for post type Page
	$def_page_pages              = array_merge( $def_post_pages, array( 'page-new.php', 'page.php' ) );
	$def_page_types              = array( 'page' );
	$disabled_metaboxes_page_all = array();
	// pages for custom post types
	$def_custom_pages = $def_post_pages;
	$args             = array( 'public' => TRUE, '_builtin' => FALSE );
	$def_custom_types = get_post_types( $args );
	// pages for link pages
	$link_pages = array( 'link.php', 'link-manager.php', 'link-add.php', 'edit-link-categories.php' );
	// pages for nav menu
	$nav_menu_pages = array( 'nav-menus.php' );
	// widget pages
	$widget_pages = array( 'widgets.php' );

	foreach ( $user_roles as $role ) {
		$disabled_global_option_[ $role ]  = _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_admin_bar_' . $role . '_items'
		);
		$disabled_global_option_[ $role ]  = _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_global_option_' . $role . '_items'
		);
		$disabled_metaboxes_post_[ $role ] = _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_metaboxes_post_' . $role . '_items'
		);
		$disabled_metaboxes_page_[ $role ] = _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_metaboxes_page_' . $role . '_items'
		);
		foreach ( $def_custom_types as $post_type ) {
			$disabled_metaboxes_[ $post_type . '_' . $role ] = _mw_adminimize_get_option_value(
				'mw_adminimize_disabled_metaboxes_' . $post_type . '_' . $role . '_items'
			);
		}
		$disabled_link_option_[ $role ]     = _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_link_option_' . $role . '_items'
		);
		$disabled_nav_menu_option_[ $role ] = _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_nav_menu_option_' . $role . '_items'
		);
		$disabled_widget_option_[ $role ]   = _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_widget_option_' . $role . '_items'
		);
		$disabled_metaboxes_post_all[]      = $disabled_metaboxes_post_[ $role ];
		$disabled_metaboxes_page_all[]      = $disabled_metaboxes_page_[ $role ];
	}

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	// Backend options
	// exclude super admin
	if ( ! _mw_adminimize_exclude_super_admin() && ! _mw_adminimize_exclude_settings_page() ) {
		$_mw_adminimize_footer = (int) _mw_adminimize_get_option_value( '_mw_adminimize_footer' );
		switch ( $_mw_adminimize_footer ) {
			case 1:
				wp_enqueue_script(
					'_mw_adminimize_remove_footer',
					WP_PLUGIN_URL . '/' . FB_ADMINIMIZE_BASEFOLDER . '/js/remove_footer' . $suffix . '.js',
					array( 'jquery' )
				);
				break;
		}

		$_mw_adminimize_header = (int) _mw_adminimize_get_option_value( '_mw_adminimize_header' );
		switch ( $_mw_adminimize_header ) {
			case 1:
				wp_enqueue_script(
					'_mw_adminimize_remove_header',
					WP_PLUGIN_URL . '/' . FB_ADMINIMIZE_BASEFOLDER . '/js/remove_header' . $suffix . '.js',
					array( 'jquery' )
				);
				break;
		}

		//post-page options
		if ( in_array( $pagenow, $def_post_pages, FALSE ) ) {

			$_mw_adminimize_tb_window = (int) _mw_adminimize_get_option_value( '_mw_adminimize_tb_window' );
			switch ( $_mw_adminimize_tb_window ) {
				case 1:
					wp_deregister_script( 'media-upload' );
					wp_enqueue_script(
						'media-upload',
						WP_PLUGIN_URL . '/' . FB_ADMINIMIZE_BASEFOLDER . '/js/tb_window' . $suffix . '.js',
						array( 'thickbox' )
					);
					break;
			}
			$_mw_adminimize_timestamp = (int) _mw_adminimize_get_option_value( '_mw_adminimize_timestamp' );
			switch ( $_mw_adminimize_timestamp ) {
				case 1:
					wp_enqueue_script(
						'_mw_adminimize_timestamp',
						WP_PLUGIN_URL . '/' . FB_ADMINIMIZE_BASEFOLDER . '/js/timestamp' . $suffix . '.js',
						array( 'jquery' )
					);
					break;
			}

			//category options
			$_mw_adminimize_cat_full = (int) _mw_adminimize_get_option_value( '_mw_adminimize_cat_full' );
			switch ( $_mw_adminimize_cat_full ) {
				case 1:
					wp_enqueue_style(
						'adminimize-full-category',
						WP_PLUGIN_URL . '/' . FB_ADMINIMIZE_BASEFOLDER . '/css/mw_cat_full' . $suffix . '.css'
					);
					break;
			}

			// set default editor tinymce
			if ( _mw_adminimize_recursive_in_array(
					'#editor-toolbar #edButtonHTML, #quicktags, #content-html',
					$disabled_metaboxes_page_all
				)
				|| _mw_adminimize_recursive_in_array(
					'#editor-toolbar #edButtonHTML, #quicktags, #content-html',
					$disabled_metaboxes_post_all
				)
			) {
				add_filter( 'wp_default_editor', create_function( '', 'return "tinymce";' ) );
			}

			// remove media bottons
			if ( _mw_adminimize_recursive_in_array( 'media_buttons', $disabled_metaboxes_page_all )
				|| _mw_adminimize_recursive_in_array( 'media_buttons', $disabled_metaboxes_post_all )
			) {
				remove_action( 'media_buttons', 'media_buttons' );
			}
		}

	}

	// set meta-box post option
	if ( in_array( $pagenow, $def_post_pages, FALSE ) && in_array( $current_post_type, $def_post_types, FALSE ) ) {
		add_action( 'admin_head', '_mw_adminimize_set_metabox_post_option', 1 );
	}
	// set meta-box page option
	if ( in_array( $pagenow, $def_page_pages, FALSE ) && in_array( $current_post_type, $def_page_types, FALSE ) ) {
		add_action( 'admin_head', '_mw_adminimize_set_metabox_page_option', 1 );
	}
	// set custom post type options
	if ( function_exists( 'get_post_types' ) && in_array( $pagenow, $def_custom_pages, FALSE )
		&& in_array(
			$current_post_type, $def_custom_types, FALSE
		)
	) {
		add_action( 'admin_head', '_mw_adminimize_set_metabox_cp_option', 1 );
	}
	// set link option
	if ( in_array( $pagenow, $link_pages, FALSE ) ) {
		add_action( 'admin_head', '_mw_adminimize_set_link_option', 1 );
	}
	// set wp nav menu options
	if ( in_array( $pagenow, $nav_menu_pages, FALSE ) ) {
		add_action( 'admin_head', '_mw_adminimize_set_nav_menu_option', 1 );
	}
	// set widget options
	if ( in_array( $pagenow, $widget_pages, FALSE ) ) {
		add_action( 'admin_head', '_mw_adminimize_set_widget_option', 1 );
	}
}

// Alternative run the hok admin_head later
add_action( 'admin_menu', '_mw_adminimize_set_menu_option', 99999 );
// global_options
add_action( 'admin_head', '_mw_adminimize_set_global_option', 1 );
// on admin init
if ( is_admin() ) {
	add_action( 'admin_init', '_mw_adminimize_textdomain' );
	add_action( 'admin_init', '_mw_adminimize_admin_init' );
	add_action( 'admin_menu', '_mw_adminimize_add_settings_page' );
	add_action( 'admin_menu', '_mw_adminimize_remove_dashboard' );
}
add_action( 'init', '_mw_adminimize_set_logout_menu', 2 );

register_activation_hook( __FILE__, '_mw_adminimize_install' );
register_uninstall_hook( __FILE__, '_mw_adminimize_uninstall' );
//register_deactivation_hook(__FILE__, '_mw_adminimize_uninstall' );

/**
 * Remove the dashboard
 */
function _mw_adminimize_remove_dashboard() {

	// exclude super admin
	if ( _mw_adminimize_exclude_super_admin() ) {
		return;
	}

	// Leave the settings screen from Adminimize to see all areas on settings.
	if ( _mw_adminimize_exclude_settings_page() ) {
		return;
	}

	global $menu, $user_ID;

	$disabled_menu_    = array();
	$disabled_submenu_ = array();
	$user_roles        = _mw_adminimize_get_all_user_roles();

	foreach ( $user_roles as $role ) {
		$disabled_menu_[ $role ]    = (array) _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_menu_' . $role . '_items'
		);
		$disabled_submenu_[ $role ] = (array) _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_submenu_' . $role . '_items'
		);
	}

	$disabled_menu_all    = array();
	$disabled_submenu_all = array();

	foreach ( $user_roles as $role ) {
		$disabled_menu_all[]    = $disabled_menu_[ $role ];
		$disabled_submenu_all[] = $disabled_submenu_[ $role ];
	}

	// remove dashboard
	if ( $disabled_menu_all !== '' || $disabled_submenu_all !== '' ) {

		$redirect = FALSE;
		foreach ( $user_roles as $role ) {
			if ( _mw_adminimize_current_user_has_role( $role ) ) {
				if ( _mw_adminimize_recursive_in_array( 'index.php', $disabled_menu_[ $role ] )
					|| _mw_adminimize_recursive_in_array( 'index.php', $disabled_submenu_[ $role ] )
				) {
					$redirect = TRUE;
				}
			}
		}

		// redirect option, if Dashboard is inactive
		if ( $redirect ) {
			$_mw_adminimize_db_redirect           = (int) _mw_adminimize_get_option_value(
				'_mw_adminimize_db_redirect'
			);
			$_mw_adminimize_db_redirect_admin_url = get_option( 'siteurl' ) . '/wp-admin/';
			switch ( $_mw_adminimize_db_redirect ) {
				case 0:
					$_mw_adminimize_db_redirect = $_mw_adminimize_db_redirect_admin_url . 'profile.php';
					break;
				case 1:
					$_mw_adminimize_db_redirect = $_mw_adminimize_db_redirect_admin_url . 'edit.php';
					break;
				case 2:
					$_mw_adminimize_db_redirect = $_mw_adminimize_db_redirect_admin_url . 'edit.php?post_type=page';
					break;
				case 3:
					$_mw_adminimize_db_redirect = $_mw_adminimize_db_redirect_admin_url . 'post-new.php';
					break;
				case 4:
					$_mw_adminimize_db_redirect = $_mw_adminimize_db_redirect_admin_url . 'page-new.php';
					break;
				case 5:
					$_mw_adminimize_db_redirect = $_mw_adminimize_db_redirect_admin_url . 'edit-comments.php';
					break;
				case 6:
					$_mw_adminimize_db_redirect = _mw_adminimize_get_option_value( '_mw_adminimize_db_redirect_txt' );
					break;
			}

			$the_user = new WP_User( $user_ID );
			reset( $menu );
			$page = key( $menu );

			$dashboard_core_string = esc_attr__( 'Dashboard' );
			$dashboard             = array( $menu[ $page ][ 0 ], $menu[ $page ][ 1 ] );
			while (
				! in_array( $dashboard_core_string, $dashboard, FALSE ) && next( $menu )
			) {
				$page = key( $menu );
			}

			if ( in_array( $dashboard_core_string, $dashboard, FALSE ) ) {
				unset( $menu[ $page ] );
			}
			reset( $menu );
			$page = key( $menu );

			while ( ! $the_user->has_cap( $menu[ $page ][ 1 ] ) && next( $menu ) ) {
				$page = key( $menu );
			}

			if ( preg_match( '#wp-admin/?(index.php)?$#', $_SERVER[ 'REQUEST_URI' ] ) ) {
				wp_redirect( $_mw_adminimize_db_redirect );
			}
		}

	}
}

/**
 * Set menu for settings
 */
function _mw_adminimize_set_menu_option() {

	// exclude super admin
	if ( _mw_adminimize_exclude_super_admin() ) {
		return NULL;
	}

	// Leave the settings screen from Adminimize to see all areas on settings.
	if ( _mw_adminimize_exclude_settings_page() ) {
		return;
	}

	global $menu, $submenu;

	if ( ! isset( $menu ) || empty( $menu ) ) {
		return;
	}

	_mw_adminimize_debug( $menu, 'Adminimize, WordPress Menu:' );
	_mw_adminimize_debug( $submenu, 'Adminimize, WordPress Sub-Menu:' );

	//$user_roles        = _mw_adminimize_get_all_user_roles();
	$disabled_menu_    = array();
	$disabled_submenu_ = array();
	$user              = wp_get_current_user();
	$user_roles        = $user->roles;
	_mw_adminimize_debug( $user, 'Adminimize, Current User:' );

	foreach ( $user_roles as $role ) {
		$disabled_menu_[ $role ]    = (array) _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_menu_' . $role . '_items'
		);
		$disabled_submenu_[ $role ] = (array) _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_submenu_' . $role . '_items'
		);
	}

	$mw_adminimize_menu    = array();
	$mw_adminimize_submenu = array();

	// Set admin-menu.
	foreach ( $user_roles as $role ) {

		if ( in_array( $role, $user->roles, FALSE )
			&& _mw_adminimize_current_user_has_role( $role )
		) {
			// Create array about all items with all affected roles.
			foreach ( $disabled_menu_[ $role ] as $menu_item ) {
				$mw_adminimize_menu[] = $menu_item;
			}
			foreach ( $disabled_submenu_[ $role ] as $submenu_item ) {
				$mw_adminimize_submenu[] = $submenu_item;
			}
		}

	}

	// Support Multiple Roles for users.
	// Leave only the items, there are active on each roles of the users.
	if ( _mw_adminimize_get_option_value( 'mw_adminimize_multiple_roles' ) && 1 < count( $user->roles ) ) {
		$mw_adminimize_menu    = _mw_adminimize_get_intersection( $disabled_menu_ );
		$mw_adminimize_submenu = _mw_adminimize_get_intersection( $disabled_submenu_ );
	} else {
		// Alternative filter the array to remove duplicates, much faster.
		$mw_adminimize_menu    = array_unique( $mw_adminimize_menu );
		$mw_adminimize_submenu = array_unique( $mw_adminimize_submenu );
	}
	_mw_adminimize_debug( $mw_adminimize_menu, 'Adminimize, Menu Slugs to hide after Filter.' );
	_mw_adminimize_debug( $mw_adminimize_menu, 'Adminimize, Sub-Menu Slugs to hide after Filter.' );

	// Fallback on users.php on all user roles smaller admin.
	if ( in_array( 'users.php', $mw_adminimize_menu, FALSE ) ) {
		$mw_adminimize_menu[] = 'profile.php';
	}
	foreach ( $menu as $key => $item ) {

		// Menu
		if ( isset( $item[ 2 ] ) ) {
			$menu_slug = $item[ 2 ];
			// Check, if the Menu item in the current user role settings?
			if ( in_array( $menu_slug, $mw_adminimize_menu, FALSE )
			) {
				remove_menu_page( $menu_slug );
				// Prevent access to the page with the slug, there was inactive.
				_mw_adminimize_check_page_access( $menu_slug );
			}

			// Sub Menu Settings.
			if ( isset( $submenu ) && ! empty( $submenu[ $menu_slug ] ) ) {
				foreach ( $submenu[ $menu_slug ] as $subindex => $subitem ) {
					// Check, if is Sub Menu item in the user role settings?
					if (
						isset( $mw_adminimize_submenu )
						&& _mw_adminimize_in_arrays(
							array( $subitem[ 2 ], $menu_slug . '__' . $subindex ),
							$mw_adminimize_submenu
						)
					) {
						remove_submenu_page( $menu_slug, $subitem[ 2 ] );
						//unset( $submenu[ $menu_slug ][ $subindex ] );
						// Prevent access to the page with the slug, there was inactive.
						_mw_adminimize_check_page_access( $subitem[ 2 ] );
					}
				}
			}
		}
	}

}

/**
 * Set global options in backend in all areas.
 */
function _mw_adminimize_set_global_option() {

	// exclude super admin
	if ( _mw_adminimize_exclude_super_admin() ) {
		return NULL;
	}

	// Leave the settings screen from Adminimize to see all areas on settings.
	if ( _mw_adminimize_exclude_settings_page() ) {
		return;
	}

	$user_roles                = _mw_adminimize_get_all_user_roles();
	$_mw_adminimize_admin_head = '';
	$disabled_global_option    = array();
	$disabled_global_option_   = array();
	$user                      = wp_get_current_user();

	// Get settings for each role.
	foreach ( $user_roles as $role ) {
		$disabled_global_option_[ $role ] = (array) _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_global_option_' . $role . '_items'
		);
	}

	// Write global options in an var.
	foreach ( $user_roles as $role ) {
		if ( in_array( $role, $user->roles, FALSE ) && _mw_adminimize_current_user_has_role( $role ) ) {

			// Create array about all items with all affected roles, important for multiple roles.
			foreach ( $disabled_global_option_[ $role ] as $global_item ) {
				$disabled_global_option[] = $global_item;
			}
		}
	}

	// Support Multiple Roles for users.
	if ( _mw_adminimize_get_option_value( 'mw_adminimize_multiple_roles' ) && 1 < count( $user->roles ) ) {
		$disabled_global_option = _mw_adminimize_get_duplicate( $disabled_global_option );
	}
	$global_options = implode( ', ', $disabled_global_option );

	if ( 0 === strpos( $global_options, '#your-profile .form-table fieldset' ) ) {
		global $_wp_admin_css_colors;
		$_wp_admin_css_colors = 0;
	}
	$_mw_adminimize_admin_head .= '<!-- Set Adminimize global options -->' . "\n";
	$_mw_adminimize_admin_head .= '<style type="text/css">' . $global_options . ' {display:none !important;}</style>' . "\n";

	if ( '' !== $global_options ) {
		echo $_mw_adminimize_admin_head;
	}
}

/**
 * set metabox options from database an area post
 */
function _mw_adminimize_set_metabox_post_option() {

	// exclude super admin
	if ( _mw_adminimize_exclude_super_admin() ) {
		return NULL;
	}

	// Leave the settings screen from Adminimize to see all areas on settings.
	if ( _mw_adminimize_exclude_settings_page() ) {
		return;
	}

	$user_roles                = _mw_adminimize_get_all_user_roles();
	$_mw_adminimize_admin_head = '';
	$metaboxes                 = '';

	foreach ( $user_roles as $role ) {
		$disabled_metaboxes_post_[ $role ] = _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_metaboxes_post_' . $role . '_items'
		);

		if ( ! isset( $disabled_metaboxes_post_[ $role ][ '0' ] ) ) {
			$disabled_metaboxes_post_[ $role ][ '0' ] = '';
		}

		// new 1.7.8
		$user = wp_get_current_user();
		if ( is_array( $user->roles ) && in_array( $role, $user->roles, FALSE ) ) {
			if ( _mw_adminimize_current_user_has_role( $role ) && isset( $disabled_metaboxes_post_[ $role ] )
				&& is_array(
					$disabled_metaboxes_post_[ $role ]
				)
			) {
				$metaboxes = implode( ',', $disabled_metaboxes_post_[ $role ] );
			}
		}
	}

	$_mw_adminimize_admin_head .= '<!-- Set Adminimize metabox post options -->' . "\n";
	$_mw_adminimize_admin_head .= '<style type="text/css">' .
		$metaboxes . ' {display:none !important;}</style>' . "\n";

	if ( ! empty( $metaboxes ) ) {
		echo $_mw_adminimize_admin_head;
	}
}

/**
 * set metabox options from database an area page
 */
function _mw_adminimize_set_metabox_page_option() {

	// exclude super admin
	if ( _mw_adminimize_exclude_super_admin() ) {
		return NULL;
	}

	// Leave the settings screen from Adminimize to see all areas on settings.
	if ( _mw_adminimize_exclude_settings_page() ) {
		return;
	}

	$user_roles                = _mw_adminimize_get_all_user_roles();
	$_mw_adminimize_admin_head = '';
	$metaboxes                 = '';

	foreach ( $user_roles as $role ) {
		$disabled_metaboxes_page_[ $role ] = _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_metaboxes_page_' . $role . '_items'
		);

		if ( ! isset( $disabled_metaboxes_page_[ $role ][ '0' ] ) ) {
			$disabled_metaboxes_page_[ $role ][ '0' ] = '';
		}

		// new 1.7.8
		$user = wp_get_current_user();
		if ( is_array( $user->roles ) && in_array( $role, $user->roles, FALSE ) ) {
			if ( _mw_adminimize_current_user_has_role( $role )
				&& isset( $disabled_metaboxes_page_[ $role ] )
				&& is_array( $disabled_metaboxes_page_[ $role ] )
			) {
				$metaboxes = implode( ',', $disabled_metaboxes_page_[ $role ] );
			}
		}
	}

	$_mw_adminimize_admin_head .= '<!-- Set Adminimize metabox page options -->' . "\n";
	$_mw_adminimize_admin_head .= '<style type="text/css">' .
		$metaboxes . ' {display:none !important;}</style>' . "\n";

	if ( ! empty( $metaboxes ) ) {
		echo $_mw_adminimize_admin_head;
	}
}

/**
 * set metabox options from database an area post
 */
function _mw_adminimize_set_metabox_cp_option() {

	// exclude super admin
	if ( _mw_adminimize_exclude_super_admin() ) {
		return NULL;
	}

	// Leave the settings screen from Adminimize to see all areas on settings.
	if ( _mw_adminimize_exclude_settings_page() ) {
		return;
	}

	if ( isset( $_GET[ 'post' ] ) ) {
		$post_id = (int) $_GET[ 'post' ];
	} elseif ( isset( $_POST[ 'post_ID' ] ) ) {
		$post_id = (int) $_POST[ 'post_ID' ];
	} else {
		$post_id = 0;
	}

	$current_post_type = $GLOBALS[ 'post_type' ];
	if ( ! isset( $current_post_type ) ) {
		$current_post_type = get_post_type( $post_id );
	}

	if ( ! isset( $current_post_type ) || ! $current_post_type ) {
		$current_post_type = str_replace( 'post_type=', '', esc_attr( $_SERVER[ 'QUERY_STRING' ] ) );
	}

	// set hard to post
	if ( ! $current_post_type ) {
		$current_post_type = 'post';
	}

	$user_roles                = _mw_adminimize_get_all_user_roles();
	$_mw_adminimize_admin_head = '';
	$metaboxes                 = '';

	foreach ( $user_roles as $role ) {
		$disabled_metaboxes_[ $current_post_type . '_' . $role ] = _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_metaboxes_' . $current_post_type . '_' . $role . '_items'
		);

		if ( ! isset( $disabled_metaboxes_[ $current_post_type . '_' . $role ][ '0' ] ) ) {
			$disabled_metaboxes_[ $current_post_type . '_' . $role ][ '0' ] = '';
		}

		$user = wp_get_current_user();
		if ( is_array( $user->roles ) && in_array( $role, $user->roles, FALSE ) ) {
			if ( _mw_adminimize_current_user_has_role( $role )
				&& isset( $disabled_metaboxes_[ $current_post_type . '_' . $role ] )
				&& is_array( $disabled_metaboxes_[ $current_post_type . '_' . $role ] )
			) {
				$metaboxes = implode( ',', $disabled_metaboxes_[ $current_post_type . '_' . $role ] );
			}
		}
	}

	$_mw_adminimize_admin_head .= '<!-- Set Adminimize post options -->' . "\n";
	$_mw_adminimize_admin_head .= '<style type="text/css">' .
		$metaboxes . ' {display:none !important;}</style>' . "\n";

	if ( ! empty( $metaboxes ) ) {
		echo $_mw_adminimize_admin_head;
	}
}

/**
 * set link options in area Links of Backend
 */
function _mw_adminimize_set_link_option() {

	// exclude super admin
	if ( _mw_adminimize_exclude_super_admin() ) {
		return NULL;
	}

	// Leave the settings screen from Adminimize to see all areas on settings.
	if ( _mw_adminimize_exclude_settings_page() ) {
		return;
	}

	$user_roles                = _mw_adminimize_get_all_user_roles();
	$_mw_adminimize_admin_head = '';

	foreach ( $user_roles as $role ) {
		$disabled_link_option_[ $role ] = _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_link_option_' . $role . '_items'
		);
	}

	foreach ( $user_roles as $role ) {
		if ( ! isset( $disabled_link_option_[ $role ][ '0' ] ) ) {
			$disabled_link_option_[ $role ][ '0' ] = '';
		}
	}

	$link_options = '';
	// new 1.7.8
	foreach ( $user_roles as $role ) {
		$user = wp_get_current_user();
		if ( is_array( $user->roles ) && in_array( $role, $user->roles, FALSE ) ) {
			if ( _mw_adminimize_current_user_has_role( $role )
				&& isset( $disabled_link_option_[ $role ] )
				&& is_array( $disabled_link_option_[ $role ] )
			) {
				$link_options = implode( ',', $disabled_link_option_[ $role ] );
			}
		}
	}

	$_mw_adminimize_admin_head .= '<!-- Set Adminimize links options -->' . "\n";
	$_mw_adminimize_admin_head .= '<style type="text/css">' .
		$link_options . ' {display:none !important;}</style>' . "\n";

	if ( ! empty( $link_options ) ) {
		echo $_mw_adminimize_admin_head;
	}
}

/**
 * remove objects on wp nav menu
 */
function _mw_adminimize_set_nav_menu_option() {

	// exclude super admin
	if ( _mw_adminimize_exclude_super_admin() ) {
		return NULL;
	}

	// Leave the settings screen from Adminimize to see all areas on settings.
	if ( _mw_adminimize_exclude_settings_page() ) {
		return;
	}

	$user_roles                = _mw_adminimize_get_all_user_roles();
	$_mw_adminimize_admin_head = '';

	foreach ( $user_roles as $role ) {
		$disabled_nav_menu_option_[ $role ] = _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_nav_menu_option_' . $role . '_items'
		);
	}

	foreach ( $user_roles as $role ) {
		if ( ! isset( $disabled_nav_menu_option_[ $role ][ '0' ] ) ) {
			$disabled_nav_menu_option_[ $role ][ '0' ] = '';
		}
	}

	$nav_menu_options = '';
	// new 1.7.8
	foreach ( $user_roles as $role ) {
		$user = wp_get_current_user();
		if ( is_array( $user->roles ) && in_array( $role, $user->roles, FALSE ) ) {
			if ( _mw_adminimize_current_user_has_role( $role )
				&& isset( $disabled_nav_menu_option_[ $role ] )
				&& is_array( $disabled_nav_menu_option_[ $role ] )
			) {
				$nav_menu_options = implode( ',', $disabled_nav_menu_option_[ $role ] );
			}
		}
	}
	//remove_meta_box( $id, 'nav-menus', 'side' );

	$_mw_adminimize_admin_head .= '<!-- Set Adminimize WP Nav Menu options -->' . "\n";
	$_mw_adminimize_admin_head .= '<style type="text/css">' .
		$nav_menu_options . ' {display: none !important;}</style>' . "\n";

	if ( $nav_menu_options ) {
		echo $_mw_adminimize_admin_head;
	}
}

/**
 * Remove areas in Widget Settings
 */
function _mw_adminimize_set_widget_option() {

	// exclude super admin
	if ( _mw_adminimize_exclude_super_admin() ) {
		return NULL;
	}

	// Leave the settings screen from Adminimize to see all areas on settings.
	if ( _mw_adminimize_exclude_settings_page() ) {
		return;
	}

	$user_roles                = _mw_adminimize_get_all_user_roles();
	$_mw_adminimize_admin_head = '';

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

	$widget_options = '';
	// new 1.7.8
	foreach ( $user_roles as $role ) {
		$user = wp_get_current_user();
		if ( is_array( $user->roles ) && in_array( $role, $user->roles, FALSE ) ) {
			if ( _mw_adminimize_current_user_has_role( $role )
				&& isset( $disabled_widget_option_[ $role ] )
				&& is_array( $disabled_widget_option_[ $role ] )
			) {
				$widget_options = implode( ',', $disabled_widget_option_[ $role ] );
			}
		}
	}
	//remove_meta_box( $id, 'nav-menus', 'side' );

	$_mw_adminimize_admin_head .= '<!-- Set Adminimize Widget options -->' . "\n";
	$_mw_adminimize_admin_head .= '<style type="text/css">' .
		$widget_options . ' {display: none !important;}</style>' . "\n";

	if ( $widget_options ) {
		echo $_mw_adminimize_admin_head;
	}
}

/**
 * small user-info
 */
function _mw_adminimize_small_user_info() {

	?>
	<div id="small_user_info">
		<p>
			<a href="<?php echo wp_nonce_url(
				site_url( 'wp-login.php?action=logout' ),
				'log-out'
			) ?>"
				title="<?php esc_attr_e( 'Log Out' ) ?>"><?php esc_attr_e( 'Log Out' ); ?></a>
		</p>
	</div>
	<?php
}

// Include message class.
require_once 'inc-setup/messages.php';

// include helping functions
require_once 'inc-setup/helping_hands.php';

// inc. settings page
require_once 'adminimize_page.php';

// dashboard options
require_once 'inc-setup/dashboard.php';

// widget options
require_once 'inc-setup/widget.php';
require_once 'inc-setup/admin-footer.php';

// global settings
// @TODO Testing, not ready for productive
//require_once( 'inc-options/settings_notice.php' );

// remove admin bar
require_once 'inc-setup/remove-admin-bar.php';

// admin bar helper, setup
// work always in frontend
require_once 'inc-setup/admin-bar-items.php';

// meta boxes helper, setup
// @TODO Meta Boxes: not ready for productive systems.
//require_once( 'inc-setup/meta-boxes.php' );

// Remove Admin Notices.
require_once 'inc-setup/remove-admin-notices.php';

/**
 * Add action link(s) to plugins page
 *
 * @param $links , $file
 * @param $file
 *
 * @return  $links
 */
function _mw_adminimize_filter_plugin_meta( $links, $file ) {

	/* create link */
	if ( FB_ADMINIMIZE_BASENAME === $file ) {
		array_unshift(
			$links,
			sprintf( '<a href="options-general.php?page=%s">%s</a>', FB_ADMINIMIZE_BASENAME, esc_attr__( 'Settings' ) )
		);
	}

	return $links;
}

/**
 * settings in plugin-admin-page
 */
function _mw_adminimize_add_settings_page() {

	$pagehook = add_options_page(
		esc_attr__( 'Adminimize Options', 'adminimize' ),
		esc_attr__( 'Adminimize', 'adminimize' ),
		'manage_options',
		__FILE__,
		'_mw_adminimize_options'
	);
	if ( ! is_network_admin() ) {
		add_filter( 'plugin_action_links', '_mw_adminimize_filter_plugin_meta', 10, 2 );
	}
	add_action( 'load-' . $pagehook, '_mw_adminimize_on_load_page' );
}

function _mw_adminimize_on_load_page() {

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_register_style( 'adminimize-style', plugins_url( 'css/style' . $suffix . '.css', __FILE__ ) );
	wp_enqueue_style( 'adminimize-style' );

	wp_register_script(
		'adminimize-settings-script',
		plugins_url( 'js/adminimize' . $suffix . '.js', __FILE__ ),
		array( 'jquery' ),
		'',
		TRUE
	);
	wp_enqueue_script( 'adminimize-settings-script' );
}

/**
 * Set theme for users
 * Kill with version 1.7.18
 */
function _mw_adminimize_set_theme() {

	if ( ! current_user_can( 'edit_users' ) ) {
		wp_die( esc_attr__( 'Cheatin&#8217; uh?' ) );
	}

	$user_ids    = $_POST[ 'mw_adminimize_theme_items' ];
	$admin_color = htmlspecialchars( stripslashes( $_POST[ '_mw_adminimize_set_theme' ] ) );

	if ( ! $user_ids ) {
		return FALSE;
	}

	foreach ( $user_ids as $user_id ) {
		update_user_meta( $user_id, 'admin_color', $admin_color );
	}

	return TRUE;
}

/**
 * Get setting value for each options key.
 *
 * @param string|bool $key
 *
 * @return array
 */
function _mw_adminimize_get_option_value( $key = FALSE ) {

	$adminimizeoptions = FALSE;
	if ( ! _mw_adminimize_exclude_settings_page() ) {
		$adminimizeoptions = wp_cache_get( 'mw_adminimize' );
	}

	if ( FALSE === $adminimizeoptions ) {
		// check for use on multisite
		if ( _mw_adminimize_is_active_on_multisite() ) {
			$adminimizeoptions = (array) get_site_option( 'mw_adminimize', array() );
		} else {
			$adminimizeoptions = (array) get_option( 'mw_adminimize', array() );
		}
		wp_cache_set( 'mw_adminimize', $adminimizeoptions );
	}

	if ( ! $key ) {
		return $adminimizeoptions;
	}

	return array_key_exists( $key, $adminimizeoptions ) ? $adminimizeoptions[ $key ] : NULL;
}

/**
 * Update options.
 *
 * @param array $options
 *
 * @return bool
 */
function _mw_adminimize_update_option( $options ) {

	if ( ! current_user_can( 'manage_options' ) ) {
		return FALSE;
	}

	// Kill the cache for the settings page.
	wp_cache_delete( 'mw_adminimize' );
	if ( _mw_adminimize_is_active_on_multisite() ) {
		update_site_option( 'mw_adminimize', $options );
	} else {
		update_option( 'mw_adminimize', $options );
	}
	wp_cache_add( 'mw_adminimize', $options );

	return TRUE;
}

/**
 * Update options in database
 */
function _mw_adminimize_update() {

	$user_roles = _mw_adminimize_get_all_user_roles();
	$args       = array( 'public' => TRUE, '_builtin' => FALSE );
	$post_types = get_post_types( $args );

	// Admin Bar Back end settings
	$adminimizeoptions[ 'mw_adminimize_admin_bar_nodes' ] = _mw_adminimize_get_option_value(
		'mw_adminimize_admin_bar_nodes'
	);
	// admin bar back end options
	foreach ( $user_roles as $role ) {
		// admin bar back end options
		if ( isset( $_POST[ 'mw_adminimize_disabled_admin_bar_' . $role . '_items' ] ) ) {
			$adminimizeoptions[ 'mw_adminimize_disabled_admin_bar_' . $role . '_items' ] = $_POST[ 'mw_adminimize_disabled_admin_bar_' . $role . '_items' ];
		} else {
			$adminimizeoptions[ 'mw_adminimize_disabled_admin_bar_' . $role . '_items' ] = array();
		}
	}

	// Plugin Self Settings.
	if ( isset( $_POST[ 'mw_adminimize_debug' ] ) ) {
		$adminimizeoptions[ 'mw_adminimize_debug' ] = (int) $_POST[ 'mw_adminimize_debug' ];
	} else {
		$adminimizeoptions[ 'mw_adminimize_debug' ] = 0;
	}

	if ( isset( $_POST[ 'mw_adminimize_multiple_roles' ] ) ) {
		$adminimizeoptions[ 'mw_adminimize_multiple_roles' ] = (int) $_POST[ 'mw_adminimize_multiple_roles' ];
	} else {
		$adminimizeoptions[ 'mw_adminimize_multiple_roles' ] = 0;
	}

	if ( isset( $_POST[ 'mw_adminimize_support_bbpress' ] ) ) {
		$adminimizeoptions[ 'mw_adminimize_support_bbpress' ] = (int) $_POST[ 'mw_adminimize_support_bbpress' ];
	} else {
		$adminimizeoptions[ 'mw_adminimize_support_bbpress' ] = 0;
	}

	if ( isset( $_POST[ 'mw_adminimize_prevent_page_access' ] ) ) {
		$adminimizeoptions[ 'mw_adminimize_prevent_page_access' ] = (int) $_POST[ 'mw_adminimize_prevent_page_access' ];
	} else {
		$adminimizeoptions[ 'mw_adminimize_prevent_page_access' ] = 0;
	}

	// Admin Bar Front end settings
	$adminimizeoptions[ 'mw_adminimize_admin_bar_frontend_nodes' ] = _mw_adminimize_get_option_value(
		'mw_adminimize_admin_bar_frontend_nodes'
	);
	// admin bar front end options
	foreach ( $user_roles as $role ) {
		// admin bar front-end options
		if ( isset( $_POST[ 'mw_adminimize_disabled_admin_bar_frontend_' . $role . '_items' ] ) ) {
			$adminimizeoptions[ 'mw_adminimize_disabled_admin_bar_frontend_' . $role . '_items' ] = $_POST[ 'mw_adminimize_disabled_admin_bar_frontend_' . $role . '_items' ];
		} else {
			$adminimizeoptions[ 'mw_adminimize_disabled_admin_bar_frontend_' . $role . '_items' ] = array();
		}
	}

	if ( isset( $_POST[ '_mw_adminimize_user_info' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_user_info' ] = (int) $_POST[ '_mw_adminimize_user_info' ];
	} else {
		$adminimizeoptions[ '_mw_adminimize_user_info' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_footer' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_footer' ] = (int) $_POST[ '_mw_adminimize_footer' ];
	} else {
		$adminimizeoptions[ '_mw_adminimize_footer' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_header' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_header' ] = (int) $_POST[ '_mw_adminimize_header' ];
	} else {
		$adminimizeoptions[ '_mw_adminimize_header' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_exclude_super_admin' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_exclude_super_admin' ] = (int) $_POST[ '_mw_adminimize_exclude_super_admin' ];
	} else {
		$adminimizeoptions[ '_mw_adminimize_exclude_super_admin' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_tb_window' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_tb_window' ] = (int) $_POST[ '_mw_adminimize_tb_window' ];
	} else {
		$adminimizeoptions[ '_mw_adminimize_tb_window' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_cat_full' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_cat_full' ] = (int) $_POST[ '_mw_adminimize_cat_full' ];
	} else {
		$adminimizeoptions[ '_mw_adminimize_cat_full' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_db_redirect' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_db_redirect' ] = (int) $_POST[ '_mw_adminimize_db_redirect' ];
	} else {
		$adminimizeoptions[ '_mw_adminimize_db_redirect' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_ui_redirect' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_ui_redirect' ] = (int) $_POST[ '_mw_adminimize_ui_redirect' ];
	} else {
		$adminimizeoptions[ '_mw_adminimize_ui_redirect' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_advice' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_advice' ] = (int) $_POST[ '_mw_adminimize_advice' ];
	} else {
		$adminimizeoptions[ '_mw_adminimize_advice' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_advice_txt' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_advice_txt' ] = wp_kses(
			$_POST[ '_mw_adminimize_advice_txt' ],
			array(
				'a' => array(
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			)
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_advice_txt' ] = '';
	}

	if ( isset( $_POST[ '_mw_adminimize_timestamp' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_timestamp' ] = (int) $_POST[ '_mw_adminimize_timestamp' ];
	} else {
		$adminimizeoptions[ '_mw_adminimize_timestamp' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_db_redirect_txt' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_db_redirect_txt' ] = esc_url( $_POST[ '_mw_adminimize_db_redirect_txt' ] );
	} else {
		$adminimizeoptions[ '_mw_adminimize_db_redirect_txt' ] = '';
	}

	// menu update
	foreach ( $user_roles as $role ) {
		if ( isset( $_POST[ 'mw_adminimize_disabled_menu_' . $role . '_items' ] ) ) {
			$adminimizeoptions[ 'mw_adminimize_disabled_menu_' . $role . '_items' ] =
				$_POST[ 'mw_adminimize_disabled_menu_' . $role . '_items' ];
		} else {
			$adminimizeoptions[ 'mw_adminimize_disabled_menu_' . $role . '_items' ] = array();
		}

		if ( isset( $_POST[ 'mw_adminimize_disabled_submenu_' . $role . '_items' ] ) ) {
			$adminimizeoptions[ 'mw_adminimize_disabled_submenu_' . $role . '_items' ] =
				$_POST[ 'mw_adminimize_disabled_submenu_' . $role . '_items' ];
		} else {
			$adminimizeoptions[ 'mw_adminimize_disabled_submenu_' . $role . '_items' ] = array();
		}
	}

	// @ToDo After release of WP 4.7, switch to sanitize_textarea_field()

	// own menu slug
	if ( isset( $_POST[ '_mw_adminimize_own_menu_slug' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_menu_slug' ] = wp_strip_all_tags(
			$_POST[ '_mw_adminimize_own_menu_slug' ]
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_menu_slug' ] = '';
	}
	// own custom menu slug
	if ( isset( $_POST[ '_mw_adminimize_own_menu_custom_slug' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_menu_custom_slug' ] = wp_strip_all_tags(
			$_POST[ '_mw_adminimize_own_menu_custom_slug' ]
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_menu_custom_slug' ] = '';
	}

	// global_options, metaboxes update
	foreach ( $user_roles as $role ) {

		// global options
		if ( isset( $_POST[ 'mw_adminimize_disabled_global_option_' . $role . '_items' ] ) ) {
			$adminimizeoptions[ 'mw_adminimize_disabled_global_option_' . $role . '_items' ] =
				$_POST[ 'mw_adminimize_disabled_global_option_' . $role . '_items' ];
		} else {
			$adminimizeoptions[ 'mw_adminimize_disabled_global_option_' . $role . '_items' ] = array();
		}

		if ( isset( $_POST[ 'mw_adminimize_disabled_metaboxes_post_' . $role . '_items' ] ) ) {
			$adminimizeoptions[ 'mw_adminimize_disabled_metaboxes_post_' . $role . '_items' ] =
				$_POST[ 'mw_adminimize_disabled_metaboxes_post_' . $role . '_items' ];
		} else {
			$adminimizeoptions[ 'mw_adminimize_disabled_metaboxes_post_' . $role . '_items' ] = array();
		}

		if ( isset( $_POST[ 'mw_adminimize_disabled_metaboxes_page_' . $role . '_items' ] ) ) {
			$adminimizeoptions[ 'mw_adminimize_disabled_metaboxes_page_' . $role . '_items' ] =
				$_POST[ 'mw_adminimize_disabled_metaboxes_page_' . $role . '_items' ];
		} else {
			$adminimizeoptions[ 'mw_adminimize_disabled_metaboxes_page_' . $role . '_items' ] = array();
		}

		foreach ( $post_types as $post_type ) {
			if ( isset( $_POST[ 'mw_adminimize_disabled_metaboxes_' . $post_type . '_' . $role . '_items' ] ) ) {
				$adminimizeoptions[ 'mw_adminimize_disabled_metaboxes_' . $post_type . '_' . $role . '_items' ] =
					$_POST[ 'mw_adminimize_disabled_metaboxes_' . $post_type . '_' . $role . '_items' ];
			} else {
				$adminimizeoptions[ 'mw_adminimize_disabled_metaboxes_' . $post_type . '_' . $role . '_items' ] = array();
			}
		}

		if ( isset( $_POST[ 'mw_adminimize_disabled_link_option_' . $role . '_items' ] ) ) {
			$adminimizeoptions[ 'mw_adminimize_disabled_link_option_' . $role . '_items' ] =
				$_POST[ 'mw_adminimize_disabled_link_option_' . $role . '_items' ];
		} else {
			$adminimizeoptions[ 'mw_adminimize_disabled_link_option_' . $role . '_items' ] = array();
		}

		// wp nav menu options
		if ( isset( $_POST[ 'mw_adminimize_disabled_nav_menu_option_' . $role . '_items' ] ) ) {
			$adminimizeoptions[ 'mw_adminimize_disabled_nav_menu_option_' . $role . '_items' ] =
				$_POST[ 'mw_adminimize_disabled_nav_menu_option_' . $role . '_items' ];
		} else {
			$adminimizeoptions[ 'mw_adminimize_disabled_nav_menu_option_' . $role . '_items' ] = array();
		}

		// widget options
		if ( isset( $_POST[ 'mw_adminimize_disabled_widget_option_' . $role . '_items' ] ) ) {
			$adminimizeoptions[ 'mw_adminimize_disabled_widget_option_' . $role . '_items' ] =
				$_POST[ 'mw_adminimize_disabled_widget_option_' . $role . '_items' ];
		} else {
			$adminimizeoptions[ 'mw_adminimize_disabled_widget_option_' . $role . '_items' ] = array();
		}

		// wp dashboard option
		if ( isset( $_POST[ 'mw_adminimize_disabled_dashboard_option_' . $role . '_items' ] ) ) {
			$adminimizeoptions[ 'mw_adminimize_disabled_dashboard_option_' . $role . '_items' ] =
				$_POST[ 'mw_adminimize_disabled_dashboard_option_' . $role . '_items' ];
		} else {
			$adminimizeoptions[ 'mw_adminimize_disabled_dashboard_option_' . $role . '_items' ] = array();
		}
	}

	// own options
	if ( isset( $_POST[ '_mw_adminimize_own_values' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_values' ] = wp_strip_all_tags( $_POST[ '_mw_adminimize_own_values' ] );
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_values' ] = '';
	}

	if ( isset( $_POST[ '_mw_adminimize_own_options' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_options' ] = wp_strip_all_tags( $_POST[ '_mw_adminimize_own_options' ] );
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_options' ] = '';
	}

	// own post options
	if ( isset( $_POST[ '_mw_adminimize_own_post_values' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_post_values' ] = wp_strip_all_tags(
			$_POST[ '_mw_adminimize_own_post_values' ]
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_post_values' ] = '';
	}

	if ( isset( $_POST[ '_mw_adminimize_own_post_options' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_post_options' ] = wp_strip_all_tags(
			$_POST[ '_mw_adminimize_own_post_options' ]
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_post_options' ] = '';
	}

	// own page options
	if ( isset( $_POST[ '_mw_adminimize_own_page_values' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_page_values' ] = wp_strip_all_tags(
			$_POST[ '_mw_adminimize_own_page_values' ]
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_page_values' ] = '';
	}

	if ( isset( $_POST[ '_mw_adminimize_own_page_options' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_page_options' ] = wp_strip_all_tags(
			$_POST[ '_mw_adminimize_own_page_options' ]
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_page_options' ] = '';
	}

	// own custom  post options
	foreach ( $post_types as $post_type ) {
		if ( isset( $_POST[ '_mw_adminimize_own_values_' . $post_type ] ) ) {
			$adminimizeoptions[ '_mw_adminimize_own_values_' . $post_type ] = wp_strip_all_tags(
				$_POST[ '_mw_adminimize_own_values_' . $post_type ]
			);
		} else {
			$adminimizeoptions[ '_mw_adminimize_own_values_' . $post_type ] = '';
		}

		if ( isset( $_POST[ '_mw_adminimize_own_options_' . $post_type ] ) ) {
			$adminimizeoptions[ '_mw_adminimize_own_options_' . $post_type ] = wp_strip_all_tags(
				$_POST[ '_mw_adminimize_own_options_' . $post_type ]
			);
		} else {
			$adminimizeoptions[ '_mw_adminimize_own_options_' . $post_type ] = '';
		}
	}

	// own link options
	if ( isset( $_POST[ '_mw_adminimize_own_link_values' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_link_values' ] = wp_strip_all_tags(
			$_POST[ '_mw_adminimize_own_link_values' ]
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_link_values' ] = '';
	}

	if ( isset( $_POST[ '_mw_adminimize_own_link_options' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_link_options' ] = wp_strip_all_tags(
			$_POST[ '_mw_adminimize_own_link_options' ]
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_link_options' ] = '';
	}

	// wp nav menu options
	if ( isset( $_POST[ '_mw_adminimize_own_nav_menu_values' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_nav_menu_values' ] = wp_strip_all_tags(
			$_POST[ '_mw_adminimize_own_nav_menu_values' ]
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_nav_menu_values' ] = '';
	}

	if ( isset( $_POST[ '_mw_adminimize_own_nav_menu_options' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_nav_menu_options' ] = wp_strip_all_tags(
			$_POST[ '_mw_adminimize_own_nav_menu_options' ]
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_nav_menu_options' ] = '';
	}

	// widget options
	if ( isset( $_POST[ '_mw_adminimize_own_widget_values' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_widget_values' ] = wp_strip_all_tags(
			$_POST[ '_mw_adminimize_own_widget_values' ]
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_widget_values' ] = '';
	}

	if ( isset( $_POST[ '_mw_adminimize_own_widget_options' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_widget_options' ] = wp_strip_all_tags(
			$_POST[ '_mw_adminimize_own_widget_options' ]
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_widget_options' ] = '';
	}

	// own dashboard options
	if ( isset( $_POST[ '_mw_adminimize_own_dashboard_values' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_dashboard_values' ] = wp_strip_all_tags(
			$_POST[ '_mw_adminimize_own_dashboard_values' ]
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_dashboard_values' ] = '';
	}

	if ( isset( $_POST[ '_mw_adminimize_own_dashboard_options' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_dashboard_options' ] = wp_strip_all_tags(
			$_POST[ '_mw_adminimize_own_dashboard_options' ]
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_dashboard_options' ] = '';
	}

	$adminimizeoptions[ 'mw_adminimize_dashboard_widgets' ] = _mw_adminimize_get_option_value(
		'mw_adminimize_dashboard_widgets'
	);

	if ( isset( $GLOBALS[ 'menu' ] ) ) {
		$adminimizeoptions[ 'mw_adminimize_default_menu' ] = $GLOBALS[ 'menu' ];
	}
	if ( isset( $GLOBALS[ 'submenu' ] ) ) {
		$adminimizeoptions[ 'mw_adminimize_default_submenu' ] = $GLOBALS[ 'submenu' ];
	}

	// update
	$update_status = _mw_adminimize_update_option( $adminimizeoptions );

	$myErrors = new _mw_adminimize_message_class();
	if ( $update_status ) {
		$message = $myErrors->get_error(
			'_mw_adminimize_update'
		);
	} else {
		$message = $myErrors->get_error(
			'_mw_adminimize_access_denied'
		);
	}
	echo '<div id="message" class="notice notice-success"><p>' . $message . '</p></div>';

	return TRUE;
}

/**
 * Delete options in database
 */
function _mw_adminimize_uninstall() {

	wp_cache_delete( 'mw_adminimize' );
	delete_site_option( 'mw_adminimize' );
	delete_option( 'mw_adminimize' );
}

/**
 * Install options in database
 */
function _mw_adminimize_install() {

	if ( ! is_admin() ) {
		return;
	}

	// If is AJAX Call.
	if ( defined('DOING_AJAX') && DOING_AJAX ) {
		return;
	}

	global $menu, $submenu;

	$user_roles        = _mw_adminimize_get_all_user_roles();
	$adminimizeoptions = array();

	foreach ( $user_roles as $role ) {
		$adminimizeoptions[ 'mw_adminimize_disabled_menu_' . $role . '_items' ]           = array();
		$adminimizeoptions[ 'mw_adminimize_disabled_submenu_' . $role . '_items' ]        = array();
		$adminimizeoptions[ 'mw_adminimize_disabled_admin_bar_' . $role . '_items' ]      = array();
		$adminimizeoptions[ 'mw_adminimize_disabled_global_option_' . $role . '_items' ]  = array();
		$adminimizeoptions[ 'mw_adminimize_disabled_metaboxes_post_' . $role . '_items' ] = array();
		$adminimizeoptions[ 'mw_adminimize_disabled_metaboxes_page_' . $role . '_items' ] = array();
		$args                                                                             = array(
			'public'   => TRUE,
			'_builtin' => FALSE,
		);
		foreach ( get_post_types( $args ) as $post_type ) {
			$adminimizeoptions[ 'mw_adminimize_disabled_metaboxes_' . $post_type . '_' . $role . '_items' ] = array();
		}
	}

	$adminimizeoptions[ 'mw_adminimize_default_menu' ]    = $menu;
	$adminimizeoptions[ 'mw_adminimize_default_submenu' ] = $submenu;

	if ( _mw_adminimize_is_active_on_multisite() ) {
		add_site_option( 'mw_adminimize', $adminimizeoptions );
	} else {
		add_option( 'mw_adminimize', $adminimizeoptions );
	}
	wp_cache_add( 'mw_adminimize', $adminimizeoptions );
}

/**
 * Process a settings export that generates a .json file of the shop settings
 */
function _mw_adminimize_export_json() {

	if ( empty( $_GET[ '_mw_adminimize_export' ] ) || 'true' !== $_GET[ '_mw_adminimize_export' ] ) {
		return;
	}

	require_once ABSPATH . 'wp-includes/pluggable.php';
	if ( ! wp_verify_nonce( $_GET[ 'mw_adminimize_export_nonce' ], 'mw_adminimize_export_nonce' ) ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$settings = _mw_adminimize_get_option_value();

	ignore_user_abort( TRUE );

	nocache_headers();
	header( 'Content-Type: application/json; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=mw_adminimize-settings-export-' . date( 'm-d-Y' ) . '.json' );
	header( 'Expires: 0' );

	echo json_encode( $settings );
	exit();
}

/**
 * Process a settings import from a json file
 */
function _mw_adminimize_import_json() {

	if ( empty( $_POST[ '_mw_adminimize_action' ] ) || '_mw_adminimize_import' !== $_POST[ '_mw_adminimize_action' ] ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST[ 'mw_adminimize_import_nonce' ], 'mw_adminimize_import_nonce' ) ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$extension = pathinfo( $_FILES[ 'import_file' ][ 'name' ], PATHINFO_EXTENSION );
	if ( $extension !== 'json' ) {
		wp_die( esc_attr__( 'Please upload a valid .json file' ) );
	}

	$import_file = $_FILES[ 'import_file' ][ 'tmp_name' ];

	if ( empty( $import_file ) ) {
		wp_die( esc_attr__( 'Please upload a file to import' ) );
	}

	// Retrieve the settings from the file and convert the json object to an array.
	$settings = (array) json_decode( file_get_contents( $import_file ) );
	unlink( $import_file );

	_mw_adminimize_update_option( $settings );
}
