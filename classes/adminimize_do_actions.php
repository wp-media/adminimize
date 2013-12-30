<?php
require_once 'adminimize_data_container.php';

class Adminimize_Do_Actions
{
	/**
	 * Container for common functions
	 * @var object
	 */
	public static $common = null;

	/**
	 * Initialize the common-functions-object
	 * @return object
	 */
	public static function get_common_funcs_object() {

		if ( empty( self::$common ) )
			self::$common = new Adminimize_Common();

		return self::$common;

	}

	/**
	 * Setup the dashboard
	 * @return NULL
	 */
	public static function dashboard_setup() {

		global $wp_meta_boxes;

		$common = self::get_common_funcs_object();

		// exclude super admin
		if ( $common->exclude_super_admin() )
			return NULL;

		// refresh widgets
		$widgets = self::get_dashboard_widgets();

		if ( current_user_can( 'manage_options' ) )
			$common->set_option( 'dashboard_widgets', $widgets );

		$user       = wp_get_current_user();
		$user_roles = $user->roles;

		foreach ( $user_roles as $role ) {

			$disabled = $common->get_option( 'dashboard_widgets_' . $role );
			if ( ! is_array( $disabled ) )
				continue;

			$disabled_widgets  = array_keys( $disabled );
			$available_widgets = array_keys( $widgets );

			foreach ( $disabled_widgets as $widget_to_remove ) {

				if ( in_array( $widget_to_remove, $available_widgets ) ) {
					remove_meta_box( $widget_to_remove, 'dashboard', $widgets[$widget_to_remove]['context'] );
				}

			}

		}

	}

	/**
	 * Get the registered dashboard widgets
	 * @return array
	 */
	public static function get_dashboard_widgets () {

		global $wp_meta_boxes;

		$widgets = array();

		if ( isset( $wp_meta_boxes['dashboard'] ) ) {

			foreach( $wp_meta_boxes['dashboard'] as $context => $data ) {

				foreach( $data as $priority => $data ) {

					foreach( $data as $widget => $data ) {

						$widgets[$widget] = array(
								'id'       => $widget,
								'title'    => strip_tags( preg_replace( '/( |)<span.*span>/im', '', $data['title'] ) ),
								'context'  => $context,
								'priority' => $priority
						);

					}

				}

			}

		}

		return $widgets;
	}


	/**
	 * set global options in backend in all areas
	 */
	public static function do_global_options() {

		global $_wp_admin_css_colors;

		$common = self::get_common_funcs_object();

		// exclude super admin
		if ( $common->exclude_super_admin() )
			return NULL;

		$user         = wp_get_current_user();
		$user_roles   = $user->roles;

		$global_style = '';
		$admin_head   =
<<<ADMINHEAD
<!-- global options -->
<style type="text/css">
%s
</style>
ADMINHEAD;

		foreach ( $user_roles as $role ) {

			$style   = '';
			$options = $common->get_option( 'global_option_' . $role );

			if ( is_array( $options ) ) {
				$style  = implode( ', ', array_keys( $options ) );
				$style .= " {display: none !important;}\n";

				$global_style .= $style;

				if ( isset( $options['#your-profile .form-table fieldset'] ) && true == $options['#your-profile .form-table fieldset'] )
					$_wp_admin_css_colors = 0;

			}

		}

		printf( $admin_head, $global_style );

	}

	public static function get_adminbar_nodes() {

		global $wp_admin_bar;

		$dc = new Adminimize_Data_Container();

		// get back our option page object
		$optpage = $dc->get( 'options_page_object' );
		$pagehook = $optpage->pagehook;

		$screen = get_current_screen();

		if ( empty( $screen ) || $screen->base != $pagehook )
			return null;

		$saved_nodes   = $dc->get( 'adminbar_nodes' );
		$toolbar_nodes = $wp_admin_bar->get_nodes();

		if ( empty( $saved_nodes ) && ! empty( $toolbar_nodes ) )
			$dc->set_option( 'adminbar_nodes', $wp_admin_bar->get_nodes() );

	}

}