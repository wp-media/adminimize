<?php
/**
 * Plugin Name: Adminimize
 * Plugin URI:  https://wordpress.org/plugins/adminimize/
 * Text Domain: adminimize
 * Domain Path: /languages
 * Description: Visually compresses the administrative meta-boxes so that more admin page content can be initially
 * seen. The plugin that lets you hide 'unnecessary' items from the WordPress administration menu, for all roles of
 * your install. You can also hide post meta controls on the edit-area to simplify the interface. It is possible to
 * simplify the admin in different for all roles.
 * Author:      Frank Bültge
 * Author URI:  http://bueltge.de/
 * Version:     1.8.5
 * License:     GPLv2+
 *
 * Php Version 5.3
 *
 * @package WordPress
 * @author  Frank Bültge <f.bueltge@inpsyde.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 2015-03-19
 */

/**
 * The stylesheet and the initial idea is from Eric A. Meyer, http://meyerweb.com/
 * and i have written a plugin with many options on the basis
 * of differently user-right and a user-friendly range in admin-area.
 *
 * :( grmpf i have so much wishes and hints form users, do use the plugin and
 *    it is not possible to development this on my free time
 */

if ( ! function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a part of plugin, not much I can do when called directly.";
	exit;
}

// plugin definitions
define( 'FB_ADMINIMIZE_BASENAME', plugin_basename( __FILE__ ) );
define( 'FB_ADMINIMIZE_BASEFOLDER', plugin_basename( dirname( __FILE__ ) ) );
define( 'FB_ADMINIMIZE_TEXTDOMAIN', _mw_adminimize_get_plugin_data( 'TextDomain' ) );

function _mw_adminimize_get_plugin_data( $value = 'Version' ) {

	if ( ! function_exists( 'get_plugin_data' ) ) {
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	}

	$plugin_data  = get_plugin_data( __FILE__ );
	$plugin_value = $plugin_data[ $value ];

	return $plugin_value;
}

function _mw_adminimize_textdomain() {

	load_plugin_textdomain(
		_mw_adminimize_get_plugin_data( 'TextDomain' ),
		FALSE,
		dirname( FB_ADMINIMIZE_BASENAME ) . _mw_adminimize_get_plugin_data( 'DomainPath' )
	);
}

function _mw_adminimize_recursive_in_array( $needle, $haystack ) {

	if ( '' != $haystack ) {
		foreach ( $haystack as $stalk ) {
			if ( $needle == $stalk
				|| ( is_array( $stalk ) && _mw_adminimize_recursive_in_array( $needle, $stalk )
				)
			) {
				return TRUE;
			}
		}

		return FALSE;
	}

	return FALSE;
}

/**
 * some basics for message
 */
class _mw_adminimize_message_class {

	/**
	 * constructor
	 */
	function _mw_adminimize_message_class() {

		$this->localizion_name = FB_ADMINIMIZE_TEXTDOMAIN;
		$this->errors          = new WP_Error();
		$this->initialize_errors();
	}

	/**
	 * get_error - Returns an error message based on the passed code
	 * Parameters - $code (the error code as a string)
	 *
	 * @param string $code
	 *
	 * @return string $errorMessage
	 */
	function get_error( $code = '' ) {

		$errorMessage = $this->errors->get_error_message( $code );

		if ( NULL == $errorMessage ) {
			return __( 'Unknown error.', $this->localizion_name );
		}

		return $errorMessage;
	}

	/**
	 * Initializes all the error messages
	 */
	function initialize_errors() {

		$this->errors->add( '_mw_adminimize_update', __( 'The updates were saved.', $this->localizion_name ) );
		$this->errors->add(
			'_mw_adminimize_access_denied',
			__( 'You have not enough rights to edit entries in the database.', $this->localizion_name )
		);
		$this->errors->add(
			'_mw_adminimize_import', __( 'All entries in the database were imported.', $this->localizion_name )
		);
		$this->errors->add(
			'_mw_adminimize_deinstall', __( 'All entries in the database were deleted.', $this->localizion_name )
		);
		$this->errors->add(
			'_mw_adminimize_deinstall_yes', __( 'Set the checkbox on deinstall-button.', $this->localizion_name )
		);
		$this->errors->add(
			'_mw_adminimize_get_option', __( 'Can\'t load menu and submenu.', $this->localizion_name )
		);
		$this->errors->add( '_mw_adminimize_set_theme', __( 'Backend-Theme was activated!', $this->localizion_name ) );
		$this->errors->add(
			'_mw_adminimize_load_theme', __( 'Load user data to themes was successful.', $this->localizion_name )
		);
	}

} // end class

function _mw_adminimize_exclude_super_admin() {

	// exclude super admin
	if ( function_exists( 'is_super_admin' )
		&& is_super_admin()
		&& 1 == _mw_adminimize_get_option_value( '_mw_adminimize_exclude_super_admin' )
	) {
		return TRUE;
	}

	return FALSE;
}

/**
 * _mw_adminimize_get_all_user_roles() - Returns an array with all user roles(names) in it.
 * Inclusive self defined roles (for example with the 'Role Manager' plugin).
 * code by Vincent Weber, www.webRtistik.nl
 *
 * @uses   $wp_roles
 * @return array $user_roles
 */
function _mw_adminimize_get_all_user_roles() {

	global $wp_roles;

	$user_roles = array();

	if ( isset( $wp_roles->roles ) && is_array( $wp_roles->roles ) ) {
		foreach ( $wp_roles->roles as $role => $data ) {
			array_push( $user_roles, $role );
			//$data contains caps, maybe for later use..
		}
	}

	// exclude the new bbPress roles
	$user_roles = array_diff(
		$user_roles,
		array( 'bbp_keymaster', 'bbp_moderator', 'bbp_participant', 'bbp_spectator', 'bbp_blocked' )
	);

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

	global $wp_roles;

	$user_roles_names = array();

	foreach ( $wp_roles->role_names as $role_name => $data ) {
		if ( function_exists( 'translate_user_role' ) ) {
			$data = translate_user_role( $data );
		} else {
			$data = _x( $data, 'Translate each user role.' );
		}

		array_push( $user_roles_names, $data );
	}

	// exclude the new bbPress roles
	$user_roles_names = array_diff(
		$user_roles_names,
		array(
			__( 'Keymaster', 'bbpress' ),
			__( 'Moderator', 'bbpress' ),
			__( 'Participant', 'bbpress' ),
			__( 'Spectator', 'bbpress' ),
			__( 'Blocked', 'bbpress' )
		)
	);

	return $user_roles_names;
}

/**
 * Control Flash Uploader
 *
 * @return boolean
 */
function _mw_adminimize_control_flashloader() {

	$_mw_adminimize_control_flashloader = _mw_adminimize_get_option_value( '_mw_adminimize_control_flashloader' );

	if ( $_mw_adminimize_control_flashloader == '1' ) {
		return FALSE;
	} else {
		return TRUE;
	}
}

/**
 * return post type
 */
function _mw_get_current_post_type() {

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
 * check user-option and add new style
 *
 * @uses $pagenow
 */
function _mw_adminimize_admin_init() {

	global $pagenow, $post_type, $menu, $submenu;

	if ( isset( $_GET[ 'post' ] ) ) {
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
		$current_post_type = _mw_get_current_post_type();
	}
	if ( ! $current_post_type ) // set hard to post
	{
		$current_post_type = 'post';
	}

	$user_roles = _mw_adminimize_get_all_user_roles();

	// check for use on multisite
	if ( is_multisite() && is_plugin_active_for_network( MW_ADMIN_FILE ) ) {
		$adminimizeoptions = get_site_option( 'mw_adminimize' );
	} else {
		$adminimizeoptions = get_option( 'mw_adminimize' );
	}

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
		array_push( $disabled_metaboxes_post_all, $disabled_metaboxes_post_[ $role ] );
		array_push( $disabled_metaboxes_page_all, $disabled_metaboxes_page_[ $role ] );
	}

	// global options
	// exclude super admin
	if ( ! _mw_adminimize_exclude_super_admin() ) {
		$_mw_adminimize_footer = _mw_adminimize_get_option_value( '_mw_adminimize_footer' );
		switch ( $_mw_adminimize_footer ) {
			case 1:
				wp_enqueue_script(
					'_mw_adminimize_remove_footer',
					WP_PLUGIN_URL . '/' . FB_ADMINIMIZE_BASEFOLDER . '/js/remove_footer.js',
					array( 'jquery' )
				);
				break;
		}

		$_mw_adminimize_header = _mw_adminimize_get_option_value( '_mw_adminimize_header' );
		switch ( $_mw_adminimize_header ) {
			case 1:
				wp_enqueue_script(
					'_mw_adminimize_remove_header',
					WP_PLUGIN_URL . '/' . FB_ADMINIMIZE_BASEFOLDER . '/js/remove_header.js',
					array( 'jquery' )
				);
				break;
		}

		//post-page options
		if ( in_array( $pagenow, $def_post_pages ) ) {

			$_mw_adminimize_tb_window = _mw_adminimize_get_option_value( '_mw_adminimize_tb_window' );
			switch ( $_mw_adminimize_tb_window ) {
				case 1:
					wp_deregister_script( 'media-upload' );
					wp_enqueue_script(
						'media-upload',
						WP_PLUGIN_URL . '/' . FB_ADMINIMIZE_BASEFOLDER . '/js/tb_window.js',
						array( 'thickbox' )
					);
					break;
			}
			$_mw_adminimize_timestamp = _mw_adminimize_get_option_value( '_mw_adminimize_timestamp' );
			switch ( $_mw_adminimize_timestamp ) {
				case 1:
					wp_enqueue_script(
						'_mw_adminimize_timestamp',
						WP_PLUGIN_URL . '/' . FB_ADMINIMIZE_BASEFOLDER . '/js/timestamp.js',
						array( 'jquery' )
					);
					break;
			}

			//category options
			$_mw_adminimize_cat_full = _mw_adminimize_get_option_value( '_mw_adminimize_cat_full' );
			switch ( $_mw_adminimize_cat_full ) {
				case 1:
					wp_enqueue_style(
						'adminimize-ful-category',
						WP_PLUGIN_URL . '/' . FB_ADMINIMIZE_BASEFOLDER . '/css/mw_cat_full.css'
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

		$_mw_adminimize_control_flashloader = _mw_adminimize_get_option_value( '_mw_adminimize_control_flashloader' );
		switch ( $_mw_adminimize_control_flashloader ) {
			case 1:
				add_filter( 'flash_uploader', '_mw_adminimize_control_flashloader', 1 );
				break;
		}
	}

	// set menu option
	add_action( 'admin_head', '_mw_adminimize_set_menu_option', 1 );
	// global_options
	add_action( 'admin_head', '_mw_adminimize_set_global_option', 1 );

	// set metabox post option
	if ( in_array( $pagenow, $def_post_pages ) && in_array( $current_post_type, $def_post_types ) ) {
		add_action( 'admin_head', '_mw_adminimize_set_metabox_post_option', 1 );
	}
	// set metabox page option
	if ( in_array( $pagenow, $def_page_pages ) && in_array( $current_post_type, $def_page_types ) ) {
		add_action( 'admin_head', '_mw_adminimize_set_metabox_page_option', 1 );
	}
	// set custom post type options
	if ( function_exists( 'get_post_types' ) && in_array( $pagenow, $def_custom_pages )
		&& in_array(
			$current_post_type, $def_custom_types
		)
	) {
		add_action( 'admin_head', '_mw_adminimize_set_metabox_cp_option', 1 );
	}
	// set link option
	if ( in_array( $pagenow, $link_pages ) ) {
		add_action( 'admin_head', '_mw_adminimize_set_link_option', 1 );
	}
	// set wp nav menu options
	if ( in_array( $pagenow, $nav_menu_pages ) ) {
		add_action( 'admin_head', '_mw_adminimize_set_nav_menu_option', 1 );
	}
	// set widget options
	if ( in_array( $pagenow, $widget_pages ) ) {
		add_action( 'admin_head', '_mw_adminimize_set_widget_option', 1 );
	}

	$adminimizeoptions[ 'mw_adminimize_default_menu' ]    = $menu;
	$adminimizeoptions[ 'mw_adminimize_default_submenu' ] = $submenu;

}

/**
 * Init always with WP
 */
function _mw_adminimize_init() {

	// change Admin Bar and user Info
	if ( version_compare( $GLOBALS[ 'wp_version' ], '3.3alpha', '>=' ) ) {
		_mw_adminimize_set_menu_option_33();
	} else {
		add_action( 'admin_head', '_mw_adminimize_set_user_info' );
		add_action( 'wp_head', '_mw_adminimize_set_user_info' );
	}

}

// on admin init
define( 'MW_ADMIN_FILE', plugin_basename( __FILE__ ) );
if ( is_admin() ) {
	add_action( 'admin_init', '_mw_adminimize_textdomain' );
	add_action( 'admin_init', '_mw_adminimize_admin_init', 2 );
	/* maybe later
	if ( is_multisite() && is_plugin_active_for_network( MW_ADMIN_FILE ) )
		add_action( 'network_admin_menu', '_mw_adminimize_add_settings_page' );
	*/
	add_action( 'admin_menu', '_mw_adminimize_add_settings_page' );
	add_action( 'admin_menu', '_mw_adminimize_remove_dashboard' );
}
add_action( 'init', '_mw_adminimize_init', 2 );

register_activation_hook( __FILE__, '_mw_adminimize_install' );
register_uninstall_hook( __FILE__, '_mw_adminimize_deinstall' );
//register_deactivation_hook(__FILE__, '_mw_adminimize_deinstall' );

/**
 * list category-box in sidebar
 *
 * @uses $post_ID
 */
function _mw_adminimize_sidecat_list_category_box() {

	global $post_ID;
	?>

	<div class="inside" id="categorydivsb">
		<p><strong><?php _e( "Categories" ); ?></strong></p>
		<ul id="categorychecklist" class="list:category categorychecklist form-no-clear">
			<?php wp_category_checklist( $post_ID ); ?>
		</ul>
		<?php if ( ! defined( 'WP_PLUGIN_DIR' ) ) { // for wp <2.6 ?>
			<div id="category-adder" class="wp-hidden-children">
				<h4><a id="category-add-toggle" href="#category-add" class="hide-if-no-js" tabindex="3"><?php _e(
							'+ Add New Category'
						); ?></a></h4>

				<p id="category-add" class="wp-hidden-child">
					<input type="text" name="newcat" id="newcat" class="form-required form-input-tip" value="<?php _e(
						'New category name'
					); ?>" tabindex="3" />
					<?php wp_dropdown_categories(
						array(
							'hide_empty'       => 0,
							'name'             => 'newcat_parent',
							'orderby'          => 'name',
							'hierarchical'     => 1,
							'show_option_none' => __( 'Parent category' ),
							'tab_index'        => 3
						)
					); ?>
					<input type="button" id="category-add-sumbit" class="add:categorychecklist:category-add button" value="<?php _e(
						'Add'
					); ?>" tabindex="3" />
					<?php wp_nonce_field( 'add-category', '_ajax_nonce', FALSE ); ?>
					<span id="category-ajax-response"></span>
				</p>
			</div>
		<?php } else { ?>
			<div id="category-adder" class="wp-hidden-children">
				<h4><a id="category-add-toggle" href="#category-add" class="hide-if-no-js" tabindex="3"><?php _e(
							'+ Add New Category'
						); ?></a></h4>

				<p id="category-add" class="wp-hidden-child">
					<label class="hidden" for="newcat"><?php _e(
							'Add New Category'
						); ?></label><input type="text" name="newcat" id="newcat" class="form-required form-input-tip" value="<?php _e(
						'New category name'
					); ?>" tabindex="3" aria-required="TRUE" />
					<br />
					<label class="hidden" for="newcat_parent"><?php _e(
							'Parent category'
						); ?>:</label><?php wp_dropdown_categories(
						array(
							'hide_empty'       => 0,
							'name'             => 'newcat_parent',
							'orderby'          => 'name',
							'hierarchical'     => 1,
							'show_option_none' => __( 'Parent category' ),
							'tab_index'        => 3
						)
					); ?>
					<input type="button" id="category-add-sumbit" class="add:categorychecklist:category-add button" value="<?php _e(
						'Add'
					); ?>" tabindex="3" />
					<?php wp_nonce_field( 'add-category', '_ajax_nonce', FALSE ); ?>
					<span id="category-ajax-response"></span>
				</p>
			</div>
		<?php } ?>
	</div>
<?php
}

/**
 * Remove the dashboard
 *
 * @author Basic Austin Matzko
 * @see    http://www.ilfilosofo.com/blog/2006/05/24/plugin-remove-the-wordpress-dashboard/
 */
function _mw_adminimize_remove_dashboard() {

	global $menu, $user_ID, $wp_version;

	$disabled_menu_    = '';
	$disabled_submenu_ = '';
	$user_roles        = _mw_adminimize_get_all_user_roles();

	foreach ( $user_roles as $role ) {
		$disabled_menu_[ $role ]    = _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_menu_' . $role . '_items'
		);
		$disabled_submenu_[ $role ] = _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_submenu_' . $role . '_items'
		);
	}

	$disabled_menu_all    = array();
	$disabled_submenu_all = array();

	foreach ( $user_roles as $role ) {
		array_push( $disabled_menu_all, $disabled_menu_[ $role ] );
		array_push( $disabled_submenu_all, $disabled_submenu_[ $role ] );
	}

	// remove dashboard
	if ( $disabled_menu_all != '' || $disabled_submenu_all != '' ) {

		foreach ( $user_roles as $role ) {

			if ( current_user_can( $role ) ) {
				if ( _mw_adminimize_recursive_in_array(
						'index.php', $disabled_menu_[ $role ]
					)
					|| _mw_adminimize_recursive_in_array( 'index.php', $disabled_submenu_[ $role ] )
				) {
					$redirect = TRUE;
				} else {
					$redirect = FALSE;
				}
			}
		}

		// redirect option, if Dashboard is inactive
		if ( isset( $redirect ) && $redirect ) {
			$_mw_adminimize_db_redirect           = _mw_adminimize_get_option_value( '_mw_adminimize_db_redirect' );
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

			// fallback for WP smaller 3.0
			if ( version_compare(
					$wp_version, "3.0alpha", "<"
				)
				&& 'edit.php?post_type=page' == $_mw_adminimize_db_redirect
			) {
				$_mw_adminimize_db_redirect = 'edit-pages.php';
			}

			$the_user = new WP_User( $user_ID );
			reset( $menu );
			$page = key( $menu );

			while ( ( __( 'Dashboard' ) != $menu[ $page ][ 0 ] ) && next( $menu )
				|| ( __(
						'Dashboard'
					) != $menu[ $page ][ 1 ] )
				&& next( $menu ) ) {
				$page = key( $menu );
			}

			if ( __( 'Dashboard' ) == $menu[ $page ][ 0 ] || __( 'Dashboard' ) == $menu[ $page ][ 1 ] ) {
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
 * set menu options from database
 */
function _mw_adminimize_set_user_info() {

	global $user_identity, $wp_version;

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

	$_mw_adminimize_admin_head  = "\n";
	$_mw_adminimize_user_info   = _mw_adminimize_get_option_value( '_mw_adminimize_user_info' );
	$_mw_adminimize_ui_redirect = _mw_adminimize_get_option_value( '_mw_adminimize_ui_redirect' );

	// change user-info
	switch ( $_mw_adminimize_user_info ) {
		case 1:
			$_mw_adminimize_admin_head .= '<script type="text/javascript">' . "\n";
			$_mw_adminimize_admin_head .= "\t" . 'jQuery(document).ready(function() { jQuery(\'#user_info\' ).remove(); });' . "\n";
			$_mw_adminimize_admin_head .= '</script>' . "\n";
			break;
		case 2:
			if ( version_compare( $wp_version, "3.2alpha", ">=" ) ) {
				if ( function_exists( 'is_admin_bar_showing' ) && is_admin_bar_showing() ) {
					$_mw_adminimize_admin_head .= '<link rel="stylesheet" href="' . WP_PLUGIN_URL . '/' . plugin_basename(
							dirname( __FILE__ )
						) . '/css/mw_small_user_info31.css" type="text/css" />' . "\n";
				}
				$_mw_adminimize_admin_head .= '<link rel="stylesheet" href="' . WP_PLUGIN_URL . '/' . plugin_basename(
						dirname( __FILE__ )
					) . '/css/mw_small_user_info32.css" type="text/css" />' . "\n";
			} elseif ( version_compare( $wp_version, "3.0alpha", ">=" ) ) {
				if ( function_exists( 'is_admin_bar_showing' ) && is_admin_bar_showing() ) {
					$_mw_adminimize_admin_head .= '<link rel="stylesheet" href="' . WP_PLUGIN_URL . '/' . plugin_basename(
							dirname( __FILE__ )
						) . '/css/mw_small_user_info31.css" type="text/css" />' . "\n";
				}
				$_mw_adminimize_admin_head .= '<link rel="stylesheet" href="' . WP_PLUGIN_URL . '/' . plugin_basename(
						dirname( __FILE__ )
					) . '/css/mw_small_user_info30.css" type="text/css" />' . "\n";
			} elseif ( version_compare( substr( $wp_version, 0, 3 ), '2.7', '>=' ) ) {
				$_mw_adminimize_admin_head .= '<link rel="stylesheet" href="' . WP_PLUGIN_URL . '/' . plugin_basename(
						dirname( __FILE__ )
					) . '/css/mw_small_user_info27.css" type="text/css" />' . "\n";
			} else {
				$_mw_adminimize_admin_head .= '<link rel="stylesheet" href="' . WP_PLUGIN_URL . '/' . plugin_basename(
						dirname( __FILE__ )
					) . '/css/mw_small_user_info.css" type="text/css" />' . "\n";
			}
			$_mw_adminimize_admin_head .= '<script type="text/javascript">' . "\n";
			$_mw_adminimize_admin_head .= "\t" . 'jQuery(document).ready(function() { jQuery(\'#user_info\' ).remove();';
			if ( $_mw_adminimize_ui_redirect == '1' ) {
				$_mw_adminimize_admin_head .= 'jQuery(\'div#wpcontent\' ).after(\'<div id="small_user_info"><p><a href="' . get_option(
						'siteurl'
					) . wp_nonce_url(
						( '/wp-login.php?action=logout&amp;redirect_to=' ) . get_option( 'siteurl' ), 'log-out'
					) . '" title="' . __( 'Log Out' ) . '">' . __( 'Log Out' ) . '</a></p></div>\' ) });' . "\n";
			} else {
				$_mw_adminimize_admin_head .= 'jQuery(\'div#wpcontent\' ).after(\'<div id="small_user_info"><p><a href="' . get_option(
						'siteurl'
					) . wp_nonce_url( ( '/wp-login.php?action=logout' ), 'log-out' ) . '" title="' . __(
						'Log Out'
					) . '">' . __( 'Log Out' ) . '</a></p></div>\' ) });' . "\n";
			}
			$_mw_adminimize_admin_head .= '</script>' . "\n";
			break;
		case 3:
			if ( version_compare( $wp_version, "3.2alpha", ">=" ) ) {
				if ( function_exists( 'is_admin_bar_showing' ) && is_admin_bar_showing() ) {
					$_mw_adminimize_admin_head .= '<link rel="stylesheet" href="' . WP_PLUGIN_URL . '/' . plugin_basename(
							dirname( __FILE__ )
						) . '/css/mw_small_user_info31.css" type="text/css" />' . "\n";
				}
				$_mw_adminimize_admin_head .= '<link rel="stylesheet" href="' . WP_PLUGIN_URL . '/' . plugin_basename(
						dirname( __FILE__ )
					) . '/css/mw_small_user_info32.css" type="text/css" />' . "\n";
			} elseif ( version_compare( $wp_version, "3.0alpha", ">=" ) ) {
				if ( function_exists( 'is_admin_bar_showing' ) && is_admin_bar_showing() ) {
					$_mw_adminimize_admin_head .= '<link rel="stylesheet" href="' . WP_PLUGIN_URL . '/' . plugin_basename(
							dirname( __FILE__ )
						) . '/css/mw_small_user_info31.css" type="text/css" />' . "\n";
				}
				$_mw_adminimize_admin_head .= '<link rel="stylesheet" href="' . WP_PLUGIN_URL . '/' . plugin_basename(
						dirname( __FILE__ )
					) . '/css/mw_small_user_info30.css" type="text/css" />' . "\n";
			} elseif ( version_compare( substr( $wp_version, 0, 3 ), '2.7', '>=' ) ) {
				$_mw_adminimize_admin_head .= '<link rel="stylesheet" href="' . WP_PLUGIN_URL . '/' . plugin_basename(
						dirname( __FILE__ )
					) . '/css/mw_small_user_info27.css" type="text/css" />' . "\n";
			} else {
				$_mw_adminimize_admin_head .= '<link rel="stylesheet" href="' . WP_PLUGIN_URL . '/' . plugin_basename(
						dirname( __FILE__ )
					) . '/css/mw_small_user_info.css" type="text/css" />' . "\n";
			}
			$_mw_adminimize_admin_head .= '<script type="text/javascript">' . "\n";
			$_mw_adminimize_admin_head .= "\t" . 'jQuery(document).ready(function() { jQuery(\'#user_info\' ).remove();';
			if ( $_mw_adminimize_ui_redirect == '1' ) {
				$_mw_adminimize_admin_head .= 'jQuery(\'div#wpcontent\' ).after(\'<div id="small_user_info"><p><a href="' . get_option(
						'siteurl'
					) . ( '/wp-admin/profile.php' ) . '">' . $user_identity . '</a> | <a href="' . get_option(
						'siteurl'
					) . wp_nonce_url(
						( '/wp-login.php?action=logout&amp;redirect_to=' ) . get_option( 'siteurl' ), 'log-out'
					) . '" title="' . __( 'Log Out' ) . '">' . __( 'Log Out' ) . '</a></p></div>\' ) });' . "\n";
			} else {
				$_mw_adminimize_admin_head .= 'jQuery(\'div#wpcontent\' ).after(\'<div id="small_user_info"><p><a href="' . get_option(
						'siteurl'
					) . ( '/wp-admin/profile.php' ) . '">' . $user_identity . '</a> | <a href="' . get_option(
						'siteurl'
					) . wp_nonce_url( ( '/wp-login.php?action=logout' ), 'log-out' ) . '" title="' . __(
						'Log Out'
					) . '">' . __( 'Log Out' ) . '</a></p></div>\' ) });' . "\n";
			}
			$_mw_adminimize_admin_head .= '</script>' . "\n";
			break;
	}

	echo $_mw_adminimize_admin_head;
}

/**
 * Set menu for settings
 */
function _mw_adminimize_set_menu_option() {

	global $menu, $submenu, $current_screen;

	// exclude super admin
	if ( _mw_adminimize_exclude_super_admin() ) {
		return NULL;
	}

	if ( 'settings_page_adminimize/adminimize' === $current_screen->id ) {
		return NULL;
	}

	$user_roles        = _mw_adminimize_get_all_user_roles();
	$disabled_menu_    = '';
	$disabled_submenu_ = '';

	foreach ( $user_roles as $role ) {
		$disabled_menu_[ $role ]    = _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_menu_' . $role . '_items'
		);
		$disabled_submenu_[ $role ] = _mw_adminimize_get_option_value(
			'mw_adminimize_disabled_submenu_' . $role . '_items'
		);
	}

	$mw_adminimize_menu = '';
	// set menu
	if ( isset( $disabled_menu_[ 'editor' ] ) && '' != $disabled_menu_[ 'editor' ] ) {

		// set admin-menu
		foreach ( $user_roles as $role ) {
			$user = wp_get_current_user();
			if ( is_array( $user->roles ) && in_array( $role, $user->roles ) ) {
				if ( current_user_can( $role ) ) {
					$mw_adminimize_menu    = $disabled_menu_[ $role ];
					$mw_adminimize_submenu = $disabled_submenu_[ $role ];
				}
			}
		}

		// fallback on users.php on all userroles smaller admin
		if ( is_array( $mw_adminimize_menu ) && in_array( 'users.php', $mw_adminimize_menu ) ) {
			$mw_adminimize_menu[ ] = 'profile.php';
		}

		if ( isset( $menu ) && ! empty( $menu ) ) {
			foreach ( $menu as $index => $item ) {
				if ( 'index.php' === $item ) {
					continue;
				}

				if ( isset( $mw_adminimize_menu ) && in_array( $item[ 2 ], $mw_adminimize_menu ) ) {
					unset( $menu[ $index ] );
				}

				if ( isset( $submenu ) && ! empty( $submenu[ $item[ 2 ] ] ) ) {
					foreach ( $submenu[ $item[ 2 ] ] as $subindex => $subitem ) {
						if ( isset( $mw_adminimize_submenu ) && in_array( $subitem[ 2 ], $mw_adminimize_submenu ) )
							//if ( 'profile.php' === $subitem[2] )
							//	unset( $menu[70] );
						{
							unset( $submenu[ $item[ 2 ] ][ $subindex ] );
						}
					}
				}
			}
		}

	}

}

/**
 * set global options in backend in all areas
 */
function _mw_adminimize_set_global_option() {

	global $_wp_admin_css_colors;

	// exclude super admin
	if ( _mw_adminimize_exclude_super_admin() ) {
		return NULL;
	}

	$user_roles = _mw_adminimize_get_all_user_roles();

	$_mw_adminimize_admin_head = '';

	// remove_action( 'admin_head', 'index_js' );

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

	$global_options = '';
	// new 1.7.8
	foreach ( $user_roles as $role ) {
		$user = wp_get_current_user();
		if ( is_array( $user->roles ) && in_array( $role, $user->roles ) ) {
			if ( current_user_can( $role )
				&& isset( $disabled_global_option_[ $role ] )
				&& is_array( $disabled_global_option_[ $role ] )
			) {
				$global_options = implode( ', ', $disabled_global_option_[ $role ] );
			}
		}
	}
	if ( isset( $global_options ) && 0 != strpos( $global_options, '#your-profile .form-table fieldset' ) ) {
		$_wp_admin_css_colors = 0;
	}
	$_mw_adminimize_admin_head .= '<!-- global options -->' . "\n";
	$_mw_adminimize_admin_head .= '<style type="text/css">' . $global_options . ' {display: none !important;}</style>' . "\n";

	if ( ! empty( $global_options ) ) {
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
		if ( is_array( $user->roles ) && in_array( $role, $user->roles ) ) {
			if ( current_user_can( $role ) && isset( $disabled_metaboxes_post_[ $role ] )
				&& is_array(
					$disabled_metaboxes_post_[ $role ]
				)
			) {
				$metaboxes = implode( ',', $disabled_metaboxes_post_[ $role ] );
			}
		}
	}

	$_mw_adminimize_admin_head .= '<style type="text/css">' .
		$metaboxes . ' {display: none !important;}</style>' . "\n";

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
		if ( is_array( $user->roles ) && in_array( $role, $user->roles ) ) {
			if ( current_user_can( $role )
				&& isset( $disabled_metaboxes_page_[ $role ] )
				&& is_array( $disabled_metaboxes_page_[ $role ] )
			) {
				$metaboxes = implode( ',', $disabled_metaboxes_page_[ $role ] );
			}
		}
	}

	$_mw_adminimize_admin_head .= '<style type="text/css">' .
		$metaboxes . ' {display: none !important;}</style>' . "\n";

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
		if ( is_array( $user->roles ) && in_array( $role, $user->roles ) ) {
			if ( current_user_can( $role )
				&& isset( $disabled_metaboxes_[ $current_post_type . '_' . $role ] )
				&& is_array( $disabled_metaboxes_[ $current_post_type . '_' . $role ] )
			) {
				$metaboxes = implode( ',', $disabled_metaboxes_[ $current_post_type . '_' . $role ] );
			}
		}
	}

	$_mw_adminimize_admin_head .= '<style type="text/css">' .
		$metaboxes . ' {display: none !important;}</style>' . "\n";

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

	$user_roles = _mw_adminimize_get_all_user_roles();

	$_mw_adminimize_admin_head = '';

	// remove_action( 'admin_head', 'index_js' );

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
		if ( is_array( $user->roles ) && in_array( $role, $user->roles ) ) {
			if ( current_user_can( $role )
				&& isset( $disabled_link_option_[ $role ] )
				&& is_array( $disabled_link_option_[ $role ] )
			) {
				$link_options = implode( ',', $disabled_link_option_[ $role ] );
			}
		}
	}
	$_mw_adminimize_admin_head .= '<style type="text/css">' .
		$link_options . ' {display: none !important;}</style>' . "\n";

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

	$user_roles = _mw_adminimize_get_all_user_roles();

	$_mw_adminimize_admin_head = '';

	// remove_action( 'admin_head', 'index_js' );

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
		if ( is_array( $user->roles ) && in_array( $role, $user->roles ) ) {
			if ( current_user_can( $role )
				&& isset( $disabled_nav_menu_option_[ $role ] )
				&& is_array( $disabled_nav_menu_option_[ $role ] )
			) {
				$nav_menu_options = implode( ',', $disabled_nav_menu_option_[ $role ] );
			}
		}
	}
	//remove_meta_box( $id, 'nav-menus', 'side' );
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

	$user_roles = _mw_adminimize_get_all_user_roles();

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
		if ( is_array( $user->roles ) && in_array( $role, $user->roles ) ) {
			if ( current_user_can( $role )
				&& isset( $disabled_widget_option_[ $role ] )
				&& is_array( $disabled_widget_option_[ $role ] )
			) {
				$widget_options = implode( ',', $disabled_widget_option_[ $role ] );
			}
		}
	}
	//remove_meta_box( $id, 'nav-menus', 'side' );
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
				title="<?php _e( 'Log Out' ) ?>"><?php _e( 'Log Out' ); ?></a>
		</p>
	</div>
<?php
}

/**
 * include options-page in wp-admin
 */
// include helping functions
require_once( 'inc-setup/helping_hands.php' );

// inc. settings page
require_once( 'adminimize_page.php' );
// @ToDO release XML Ex-Import
//require_once( 'inc-options/class-eximport.php' );

// dashboard options
require_once( 'inc-setup/dashboard.php' );

// widget options
require_once( 'inc-setup/widget.php' );
require_once( 'inc-setup/admin-footer.php' );

// global settings
//require_once( 'inc-options/settings_notice.php' );
// remove admin bar
require_once( 'inc-setup/remove-admin-bar.php' );

// admin bar helper, setup
// work always in frontend
require_once( 'inc-setup/admin-bar-items.php' );
// meta boxes helper, setup
//require_once( 'inc-setup/meta-boxes.php' );

/**
 * @version WP 2.8
 * Add action link(s) to plugins page
 *
 * @param $links , $file
 *
 * @param $file
 *
 * @return  $links
 */
function _mw_adminimize_filter_plugin_meta( $links, $file ) {

	/* create link */
	if ( FB_ADMINIMIZE_BASENAME == $file ) {
		array_unshift(
			$links,
			sprintf( '<a href="options-general.php?page=%s">%s</a>', FB_ADMINIMIZE_BASENAME, __( 'Settings' ) )
		);
	}

	return $links;
}

/**
 * settings in plugin-admin-page
 */
function _mw_adminimize_add_settings_page() {

	/*
	 * Maybe later
	if ( is_multisite() && is_plugin_active_for_network( plugin_basename( __FILE__ ) ) ) {
		$pagehook = add_submenu_page(
			'settings.php',
			__( 'Adminimize Network Options', FB_ADMINIMIZE_TEXTDOMAIN ),
			__( 'Adminimize', FB_ADMINIMIZE_TEXTDOMAIN ),
			'manage_options',
			plugin_basename( __FILE__ ),
			'_mw_adminimize_options'
		);
	}
	*/
	$pagehook = add_options_page(
		__( 'Adminimize Options', FB_ADMINIMIZE_TEXTDOMAIN ),
		__( 'Adminimize', FB_ADMINIMIZE_TEXTDOMAIN ),
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

	wp_register_style( 'adminimize-style', plugins_url( 'css/style.css', __FILE__ ) );
	wp_enqueue_style( 'adminimize-style' );

	wp_register_script(
		'adminimize-settings-script',
		plugins_url( 'js/adminimize.js', __FILE__ ),
		array( 'jquery' ),
		'05/02/2013',
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
		wp_die( __( 'Cheatin&#8217; uh?' ) );
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
 * read options
 *
 * @param $key
 *
 * @return null
 */
function _mw_adminimize_get_option_value( $key ) {

	// check for use on multisite
	if ( is_multisite() && is_plugin_active_for_network( MW_ADMIN_FILE ) ) {
		$adminimizeoptions = get_site_option( 'mw_adminimize' );
	} else {
		$adminimizeoptions = get_option( 'mw_adminimize' );
	}

	return ( isset( $adminimizeoptions[ $key ] ) ) ?
		( $adminimizeoptions[ $key ] ) : NULL;
}

/**
 * Update options in database
 */
function _mw_adminimize_update() {

	$user_roles = _mw_adminimize_get_all_user_roles();
	$args       = array( 'public' => TRUE, '_builtin' => FALSE );
	$post_types = get_post_types( $args );

	$adminimizeoptions[ 'mw_adminimize_admin_bar_nodes' ] = _mw_adminimize_get_option_value(
		'mw_adminimize_admin_bar_nodes'
	);
	// admin bar options
	foreach ( $user_roles as $role ) {
		// admin abr options
		if ( isset( $_POST[ 'mw_adminimize_disabled_admin_bar_' . $role . '_items' ] ) ) {
			$adminimizeoptions[ 'mw_adminimize_disabled_admin_bar_' . $role . '_items' ] = $_POST[ 'mw_adminimize_disabled_admin_bar_' . $role . '_items' ];
		} else {
			$adminimizeoptions[ 'mw_adminimize_disabled_admin_bar_' . $role . '_items' ] = array();
		}
	}

	if ( isset( $_POST[ '_mw_adminimize_user_info' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_user_info' ] = strip_tags(
			stripslashes( $_POST[ '_mw_adminimize_user_info' ] )
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_user_info' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_dashmenu' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_dashmenu' ] = strip_tags(
			stripslashes( $_POST[ '_mw_adminimize_dashmenu' ] )
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_dashmenu' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_footer' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_footer' ] = strip_tags( stripslashes( $_POST[ '_mw_adminimize_footer' ] ) );
	} else {
		$adminimizeoptions[ '_mw_adminimize_footer' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_header' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_header' ] = strip_tags( stripslashes( $_POST[ '_mw_adminimize_header' ] ) );
	} else {
		$adminimizeoptions[ '_mw_adminimize_header' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_exclude_super_admin' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_exclude_super_admin' ] = strip_tags(
			stripslashes( $_POST[ '_mw_adminimize_exclude_super_admin' ] )
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_exclude_super_admin' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_tb_window' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_tb_window' ] = strip_tags(
			stripslashes( $_POST[ '_mw_adminimize_tb_window' ] )
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_tb_window' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_cat_full' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_cat_full' ] = strip_tags(
			stripslashes( $_POST[ '_mw_adminimize_cat_full' ] )
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_cat_full' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_db_redirect' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_db_redirect' ] = strip_tags(
			stripslashes( $_POST[ '_mw_adminimize_db_redirect' ] )
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_db_redirect' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_ui_redirect' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_ui_redirect' ] = strip_tags(
			stripslashes( $_POST[ '_mw_adminimize_ui_redirect' ] )
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_ui_redirect' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_advice' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_advice' ] = strip_tags( stripslashes( $_POST[ '_mw_adminimize_advice' ] ) );
	} else {
		$adminimizeoptions[ '_mw_adminimize_advice' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_advice_txt' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_advice_txt' ] = stripslashes( $_POST[ '_mw_adminimize_advice_txt' ] );
	} else {
		$adminimizeoptions[ '_mw_adminimize_advice_txt' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_timestamp' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_timestamp' ] = strip_tags(
			stripslashes( $_POST[ '_mw_adminimize_timestamp' ] )
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_timestamp' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_control_flashloader' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_control_flashloader' ] = strip_tags(
			stripslashes( $_POST[ '_mw_adminimize_control_flashloader' ] )
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_control_flashloader' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_db_redirect_txt' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_db_redirect_txt' ] = stripslashes(
			$_POST[ '_mw_adminimize_db_redirect_txt' ]
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_db_redirect_txt' ] = 0;
	}

	// menu update
	foreach ( $user_roles as $role ) {
		if ( isset( $_POST[ 'mw_adminimize_disabled_menu_' . $role . '_items' ] ) ) {
			$adminimizeoptions[ 'mw_adminimize_disabled_menu_' . $role . '_items' ] = $_POST[ 'mw_adminimize_disabled_menu_' . $role . '_items' ];
		} else {
			$adminimizeoptions[ 'mw_adminimize_disabled_menu_' . $role . '_items' ] = array();
		}

		if ( isset( $_POST[ 'mw_adminimize_disabled_submenu_' . $role . '_items' ] ) ) {
			$adminimizeoptions[ 'mw_adminimize_disabled_submenu_' . $role . '_items' ] = $_POST[ 'mw_adminimize_disabled_submenu_' . $role . '_items' ];
		} else {
			$adminimizeoptions[ 'mw_adminimize_disabled_submenu_' . $role . '_items' ] = array();
		}
	}

	// global_options, metaboxes update
	foreach ( $user_roles as $role ) {

		// global options
		if ( isset( $_POST[ 'mw_adminimize_disabled_global_option_' . $role . '_items' ] ) ) {
			$adminimizeoptions[ 'mw_adminimize_disabled_global_option_' . $role . '_items' ] = $_POST[ 'mw_adminimize_disabled_global_option_' . $role . '_items' ];
		} else {
			$adminimizeoptions[ 'mw_adminimize_disabled_global_option_' . $role . '_items' ] = array();
		}

		if ( isset( $_POST[ 'mw_adminimize_disabled_metaboxes_post_' . $role . '_items' ] ) ) {
			$adminimizeoptions[ 'mw_adminimize_disabled_metaboxes_post_' . $role . '_items' ] = $_POST[ 'mw_adminimize_disabled_metaboxes_post_' . $role . '_items' ];
		} else {
			$adminimizeoptions[ 'mw_adminimize_disabled_metaboxes_post_' . $role . '_items' ] = array();
		}

		if ( isset( $_POST[ 'mw_adminimize_disabled_metaboxes_page_' . $role . '_items' ] ) ) {
			$adminimizeoptions[ 'mw_adminimize_disabled_metaboxes_page_' . $role . '_items' ] = $_POST[ 'mw_adminimize_disabled_metaboxes_page_' . $role . '_items' ];
		} else {
			$adminimizeoptions[ 'mw_adminimize_disabled_metaboxes_page_' . $role . '_items' ] = array();
		}

		foreach ( $post_types as $post_type ) {
			if ( isset( $_POST[ 'mw_adminimize_disabled_metaboxes_' . $post_type . '_' . $role . '_items' ] ) ) {
				$adminimizeoptions[ 'mw_adminimize_disabled_metaboxes_' . $post_type . '_' . $role . '_items' ] = $_POST[ 'mw_adminimize_disabled_metaboxes_' . $post_type . '_' . $role . '_items' ];
			} else {
				$adminimizeoptions[ 'mw_adminimize_disabled_metaboxes_' . $post_type . '_' . $role . '_items' ] = array();
			}
		}

		if ( isset( $_POST[ 'mw_adminimize_disabled_link_option_' . $role . '_items' ] ) ) {
			$adminimizeoptions[ 'mw_adminimize_disabled_link_option_' . $role . '_items' ] = $_POST[ 'mw_adminimize_disabled_link_option_' . $role . '_items' ];
		} else {
			$adminimizeoptions[ 'mw_adminimize_disabled_link_option_' . $role . '_items' ] = array();
		}

		// wp nav menu options
		if ( isset( $_POST[ 'mw_adminimize_disabled_nav_menu_option_' . $role . '_items' ] ) ) {
			$adminimizeoptions[ 'mw_adminimize_disabled_nav_menu_option_' . $role . '_items' ] = $_POST[ 'mw_adminimize_disabled_nav_menu_option_' . $role . '_items' ];
		} else {
			$adminimizeoptions[ 'mw_adminimize_disabled_nav_menu_option_' . $role . '_items' ] = array();
		}

		// widget options
		if ( isset( $_POST[ 'mw_adminimize_disabled_widget_option_' . $role . '_items' ] ) ) {
			$adminimizeoptions[ 'mw_adminimize_disabled_widget_option_' . $role . '_items' ] = $_POST[ 'mw_adminimize_disabled_widget_option_' . $role . '_items' ];
		} else {
			$adminimizeoptions[ 'mw_adminimize_disabled_widget_option_' . $role . '_items' ] = array();
		}

		// wp dashboard option
		if ( isset( $_POST[ 'mw_adminimize_disabled_dashboard_option_' . $role . '_items' ] ) ) {
			$adminimizeoptions[ 'mw_adminimize_disabled_dashboard_option_' . $role . '_items' ] = $_POST[ 'mw_adminimize_disabled_dashboard_option_' . $role . '_items' ];
		} else {
			$adminimizeoptions[ 'mw_adminimize_disabled_dashboard_option_' . $role . '_items' ] = array();
		}
	}

	// own options
	if ( isset( $_POST[ '_mw_adminimize_own_values' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_values' ] = stripslashes( $_POST[ '_mw_adminimize_own_values' ] );
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_values' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_own_options' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_options' ] = stripslashes( $_POST[ '_mw_adminimize_own_options' ] );
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_options' ] = 0;
	}

	// own post options
	if ( isset( $_POST[ '_mw_adminimize_own_post_values' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_post_values' ] = stripslashes(
			$_POST[ '_mw_adminimize_own_post_values' ]
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_post_values' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_own_post_options' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_post_options' ] = stripslashes(
			$_POST[ '_mw_adminimize_own_post_options' ]
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_post_options' ] = 0;
	}

	// own page options
	if ( isset( $_POST[ '_mw_adminimize_own_page_values' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_page_values' ] = stripslashes(
			$_POST[ '_mw_adminimize_own_page_values' ]
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_page_values' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_own_page_options' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_page_options' ] = stripslashes(
			$_POST[ '_mw_adminimize_own_page_options' ]
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_page_options' ] = 0;
	}

	// own custom  post options
	foreach ( $post_types as $post_type ) {
		if ( isset( $_POST[ '_mw_adminimize_own_values_' . $post_type ] ) ) {
			$adminimizeoptions[ '_mw_adminimize_own_values_' . $post_type ] = stripslashes(
				$_POST[ '_mw_adminimize_own_values_' . $post_type ]
			);
		} else {
			$adminimizeoptions[ '_mw_adminimize_own_values_' . $post_type ] = 0;
		}

		if ( isset( $_POST[ '_mw_adminimize_own_options_' . $post_type ] ) ) {
			$adminimizeoptions[ '_mw_adminimize_own_options_' . $post_type ] = stripslashes(
				$_POST[ '_mw_adminimize_own_options_' . $post_type ]
			);
		} else {
			$adminimizeoptions[ '_mw_adminimize_own_options_' . $post_type ] = 0;
		}
	}

	// own link options
	if ( isset( $_POST[ '_mw_adminimize_own_link_values' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_link_values' ] = stripslashes(
			$_POST[ '_mw_adminimize_own_link_values' ]
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_link_values' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_own_link_options' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_link_options' ] = stripslashes(
			$_POST[ '_mw_adminimize_own_link_options' ]
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_link_options' ] = 0;
	}

	// wp nav menu options
	if ( isset( $_POST[ '_mw_adminimize_own_nav_menu_values' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_nav_menu_values' ] = stripslashes(
			$_POST[ '_mw_adminimize_own_nav_menu_values' ]
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_nav_menu_values' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_own_nav_menu_options' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_nav_menu_options' ] = stripslashes(
			$_POST[ '_mw_adminimize_own_nav_menu_options' ]
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_nav_menu_options' ] = 0;
	}

	// widget options
	if ( isset( $_POST[ '_mw_adminimize_own_widget_values' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_widget_values' ] = stripslashes(
			$_POST[ '_mw_adminimize_own_widget_values' ]
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_widget_values' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_own_widget_options' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_widget_options' ] = stripslashes(
			$_POST[ '_mw_adminimize_own_widget_options' ]
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_widget_options' ] = 0;
	}

	// own dashboard options
	if ( isset( $_POST[ '_mw_adminimize_own_dashboard_values' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_dashboard_values' ] = stripslashes(
			$_POST[ '_mw_adminimize_own_dashboard_values' ]
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_dashboard_values' ] = 0;
	}

	if ( isset( $_POST[ '_mw_adminimize_own_dashboard_options' ] ) ) {
		$adminimizeoptions[ '_mw_adminimize_own_dashboard_options' ] = stripslashes(
			$_POST[ '_mw_adminimize_own_dashboard_options' ]
		);
	} else {
		$adminimizeoptions[ '_mw_adminimize_own_dashboard_options' ] = 0;
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

	//update_option( 'mw_adminimize1', $adminimizeoptions['mw_adminimize_disabled_admin_bar_administrator_items'] );
	//update_site_option( 'mw_adminimize1', $adminimizeoptions['mw_adminimize_disabled_admin_bar_administrator_items'] );
	// update
	if ( is_multisite() && is_plugin_active_for_network( MW_ADMIN_FILE ) ) {
		update_site_option( 'mw_adminimize', $adminimizeoptions );
	} else {
		update_option( 'mw_adminimize', $adminimizeoptions );
	}

	$myErrors = new _mw_adminimize_message_class();
	$myErrors = '<div id="message" class="updated fade"><p>' . $myErrors->get_error(
			'_mw_adminimize_update'
		) . '</p></div>';
	echo $myErrors;
}

/**
 * Delete options in database
 */
function _mw_adminimize_deinstall() {

	delete_site_option( 'mw_adminimize' );
	delete_option( 'mw_adminimize' );
}

/**
 * Install options in database
 */
function _mw_adminimize_install() {

	if ( ! is_admin() ) {
		return NULL;
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
			'_builtin' => FALSE
		);
		foreach ( get_post_types( $args ) as $post_type ) {
			$adminimizeoptions[ 'mw_adminimize_disabled_metaboxes_' . $post_type . '_' . $role . '_items' ] = array();
		}
	}

	$adminimizeoptions[ 'mw_adminimize_default_menu' ]    = $menu;
	$adminimizeoptions[ 'mw_adminimize_default_submenu' ] = $submenu;

	if ( is_multisite() && is_plugin_active_for_network( MW_ADMIN_FILE ) ) {
		add_site_option( 'mw_adminimize', $adminimizeoptions );
	} else {
		add_option( 'mw_adminimize', $adminimizeoptions );
	}
}

/**
 * export options in file
 */
function _mw_adminimize_export() {

	global $wpdb;

	$filename = 'adminimize_export-' . date( 'Y-m-d_G-i-s' ) . '.seq';

	header( "Content-Description: File Transfer" );
	header( "Content-Disposition: attachment; filename=" . urlencode( $filename ) );
	header( "Content-Type: application/force-download" );
	header( "Content-Type: application/octet-stream" );
	header( "Content-Type: application/download" );
	header( 'Content-Type: text/seq; charset=' . get_option( 'blog_charset' ), TRUE );
	flush();

	$export_data = mysql_query( "SELECT option_value FROM $wpdb->options WHERE option_name = 'mw_adminimize'" );
	$export_data = mysql_result( $export_data, 0 );
	echo $export_data;
	flush();
}

/**
 * import options in table _options
 */
function _mw_adminimize_import() {

	// check file extension
	$str_file_name = $_FILES[ 'datei' ][ 'name' ];
	$str_file_ext  = explode( ".", $str_file_name );

	if ( $str_file_ext[ 1 ] != 'seq' ) {
		wp_die( 'No exist.' );
	} elseif ( file_exists( $_FILES[ 'datei' ][ 'name' ] ) ) {
		wp_die( 'Exist.' );
	} else {
		// path for file
		$str_ziel = WP_CONTENT_DIR . '/' . $_FILES[ 'datei' ][ 'name' ];
		// transfer
		move_uploaded_file( $_FILES[ 'datei' ][ 'tmp_name' ], $str_ziel );
		// access authorization
		chmod( $str_ziel, 0644 );
		// SQL import
		ini_set( 'default_socket_timeout', 120 );
		$import_file = file_get_contents( $str_ziel );

		_mw_adminimize_deinstall();
		$import_file = unserialize( $import_file );

		if ( file_exists( $str_ziel ) ) {
			unlink( $str_ziel );
		}

		if ( is_multisite() && is_plugin_active_for_network( MW_ADMIN_FILE ) ) {
			update_site_option( 'mw_adminimize', $import_file );
		} else {
			update_option( 'mw_adminimize', $import_file );
		}

		if ( file_exists( $str_ziel ) ) {
			unlink( $str_ziel );
		}

	}

	$myErrors = new _mw_adminimize_message_class();
	$myErrors = '<div id="message" class="updated fade"><p>' .
		$myErrors->get_error( '_mw_adminimize_import' ) . '</p></div>';

	echo $myErrors;
}
