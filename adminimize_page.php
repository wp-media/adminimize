<?php
/**
 * @package    Adminimize
 * @subpackage Settings page
 * @author     Frank Bültge
 */

// A rather more popular way to check if the file is being accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( "Hi there!  I'm just a part of plugin, not much I can do when called directly." );
}
/*
// Export the options to local client.
if ( array_key_exists( '_mw_adminimize_export', $_GET ) ) {
	add_action( 'admin_init', '_mw_adminimize_export_json' );
	//_mw_adminimize_export_json();
	die();
}*/

function _mw_adminimize_options() {

	// The removed data is never used.

	// update options
	// Some indenting cleanups
	if (
	        ( array_key_exists( '_mw_adminimize_action', $_POST )
			&& $_POST[ '_mw_adminimize_action' ] === '_mw_adminimize_insert' )
		    && $_POST[ '_mw_adminimize_save' ]
	) {

		if ( function_exists( 'current_user_can' ) && current_user_can( 'manage_options' ) ) {
			check_admin_referer( 'mw_adminimize_nonce' );

			_mw_adminimize_update();
		} else {
			$myErrors = new _mw_adminimize_message_class();
			$myErrors = '<div id="message" class="error"><p>' .
                        $myErrors->get_error(
                            '_mw_adminimize_access_denied'
				        ) .
                        '</p></div>';
			wp_die( $myErrors );

			// Some indenting cleanups
		}
	}

	// import options
	// Some indenting cleanups
	if (
	        ( array_key_exists( '_mw_adminimize_action', $_POST )
			&& $_POST[ '_mw_adminimize_action' ] === '_mw_adminimize_import' )
		    && $_POST[ '_mw_adminimize_save' ]
	) {

		_mw_adminimize_import_json();
	}

	// Uninstall options
	// Some indenting cleanups
	if (
	        ( array_key_exists( '_mw_adminimize_action', $_POST )
		    && $_POST[ '_mw_adminimize_action' ] === '_mw_adminimize_uninstall' )
		    && ! array_key_exists( '_mw_adminimize_uninstall_yes', $_POST )
	) {
		$myErrors = new _mw_adminimize_message_class();
		$myErrors = '<div id="message" class="error"><p>' . $myErrors->get_error(
				'_mw_adminimize_uninstall_yes'
			) . '</p></div>';
		wp_die( $myErrors );
	}

	// Some indenting cleanups
	if (
	        ( array_key_exists( '_mw_adminimize_action', $_POST )
		    && array_key_exists( '_mw_adminimize_uninstall_yes', $_POST )
		    && $_POST[ '_mw_adminimize_action' ] === '_mw_adminimize_uninstall' )
		    && $_POST[ '_mw_adminimize_uninstall' ]
		    && $_POST[ '_mw_adminimize_uninstall_yes' ] === '_mw_adminimize_uninstall'
	) {
		if ( function_exists( 'current_user_can' ) && current_user_can( 'manage_options' ) ) {
			check_admin_referer( 'mw_adminimize_nonce' );

			_mw_adminimize_uninstall();

			$myErrors = new _mw_adminimize_message_class();
			$myErrors = '<div id="message" class="updated fade"><p>' . $myErrors->get_error(
					'_mw_adminimize_uninstall'
				) . '</p></div>';
			echo $myErrors;
		} else {
			$myErrors = new _mw_adminimize_message_class();
			$myErrors = '<div id="message" class="error"><p>' . $myErrors->get_error(
					'_mw_adminimize_access_denied'
				) . '</p></div>';
			wp_die( $myErrors );
		}
	}

	// load theme user data
	// Some indenting cleanups
	if (
	        ( array_key_exists( '_mw_adminimize_action', $_POST )
			&& $_POST[ '_mw_adminimize_action' ] === '_mw_adminimize_load_theme' )
		    && $_POST[ '_mw_adminimize_load' ]
	) {
		if ( function_exists( 'current_user_can' ) && current_user_can( 'edit_users' ) ) {
			check_admin_referer( 'mw_adminimize_nonce' );

			$myErrors = new _mw_adminimize_message_class();
			$myErrors = '<div id="message" class="updated fade"><p>' .
                        $myErrors->get_error(
					    '_mw_adminimize_load_theme'
				        ) .
                        '</p></div>';
			echo $myErrors;
		} else {
			$myErrors = new _mw_adminimize_message_class();
			$myErrors = '<div id="message" class="error"><p>' .
                        $myErrors->get_error(
                            '_mw_adminimize_access_denied'
				        ) .
                        '</p></div>';
			wp_die( $myErrors );
		}
	}

	// Some indenting cleanups
	if (
	        ( array_key_exists( '_mw_adminimize_action', $_POST )
			&& $_POST[ '_mw_adminimize_action' ] === '_mw_adminimize_set_theme' )
		    && $_POST[ '_mw_adminimize_save' ]
	) {
		if ( function_exists( 'current_user_can' ) && current_user_can( 'edit_users' ) ) {
			check_admin_referer( 'mw_adminimize_nonce' );

			// _mw_adminimize_set_theme();
            // This function isn't defined anywhere.

			$myErrors = new _mw_adminimize_message_class();
			$myErrors = '<div id="message" class="updated fade"><p>' .
                        $myErrors->get_error(
					    '_mw_adminimize_set_theme'
				        ) .
                        '</p></div>';
			echo $myErrors;
		} else {
			$myErrors = new _mw_adminimize_message_class();
			$myErrors = '<div id="message" class="error"><p>' .
                        $myErrors->get_error(
					    '_mw_adminimize_access_denied'
				        ) .
                        '</p></div>';
			wp_die( $myErrors );
		}
	}
	?>
	<div class="wrap">
		<?php
		do_action( 'mw_adminimize_before_settings_form' );

		// Backend Options for all roles
		require_once 'inc-options/minimenu.php';
		?>
		<form name="backend_option" method="post" id="_mw_adminimize_options" action="?page=<?php echo esc_attr( $_GET[ 'page' ] ); ?>">
			<?php
			// Adminimize Settings for the plugin.
			require_once 'inc-options/self_settings.php';

			// Admin Bar options
			require_once 'inc-options/admin_bar.php';

			// Admin Bar items frontend
			require_once 'inc-options/admin_bar_frontend.php';

			// Backend Options for all roles
			require_once 'inc-options/backend_options.php';

			// global options on all pages in backend for different roles
			require_once 'inc-options/global_options.php';

			// dashboard options for different roles
			require_once 'inc-options/dashboard_options.php';

			// Menu Sub-menu Options
			require_once 'inc-options/menu_options.php';

			// Write Page Options
			require_once 'inc-options/write_post_options.php';

			// Write Page Options
			require_once 'inc-options/write_page_options.php';

			// Custom Post Type
			if ( function_exists( 'get_post_types' ) ) {
				require_once 'inc-options/write_cp_options.php';
			}

			// Links Options
			if ( 0 !== get_option( 'link_manager_enabled' ) ) {
				require_once 'inc-options/links_options.php';
			}

			// Widget options
			require_once 'inc-options/widget_options.php';

			// WP Nav Menu Options
			require_once 'inc-options/wp_nav_menu_options.php';

			do_action( 'mw_adminimize_settings_form' );
			?>
		</form>

		<?php
		do_action( 'mw_adminimize_after_settings_form' );

		// Im/Export Options
		require_once 'inc-options/im_export_options.php';

		// Uninstall options
		require_once 'inc-options/deinstall_options.php';
		?>

	</div>
	<?php
}
