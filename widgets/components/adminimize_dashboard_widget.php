<?php
/**
 * Dashboard options widget
 *
 * PHP version 5.2
 *
 * @category   PHP
 * @package    WordPress
 * @subpackage Inpsyde\Adminimize
 * @author     Ralf Albert <me@neun12.de>
 * @license    GPLv3 http://www.gnu.org/licenses/gpl-3.0.txt
 * @version    1.0
 * @link       http://wordpress.com
 */

if ( ! class_exists( 'Adminimize_Dashboard_Widget' ) ) {

class Adminimize_Dashboard_Widget extends Adminimize_Base_Widget implements I_Adminimize_Widget
{

	public function get_attributes() {

		return array(
				'id'            => 'dashboard_widget',
				'title'         => __( 'Dashboard Options', $this->pluginheaders->TextDomain ),
				'context'       => 0,
				'priority'      => 'default',
				'callback_args' => array(),
				'option_name'   => 'dashboard_widgets'
		);

	}

	public function get_hooks() {

		return array(
				'actions' => array(
					array( 'wp_dashboard_setup', array( $this, 'dashboard_setup' ), 99, 0 ),
				)
		);

	}

	public function content() {

		$option_name  = $this->get_used_option();
		$dash_widgets = $this->storage->get_option( 'available_' . $option_name );

		// no widgets?
		if ( empty( $dash_widgets ) ) {

			printf(
				'<p>%s</p>',
				esc_html(
					__( 'Something went terrible wrong during plugin installation. Please deactivate and reactivate the plugin. If the error still occurs, please notify the administrator.', $this->pluginheaders->TextDomain )
				)
			);

		} else {

			$custom_dash = $this->storage->get_custom_options( $option_name );

			// merge standard options with custom options
			foreach ( $custom_dash['original'] as $title => $id ) {

				if ( empty( $id ) || empty( $title ) )
					continue;

				$dash_widgets[] = array( 'id' => $id, 'title' => $title );

			}

			// create table
			echo $this->templater->get_table( $option_name, $dash_widgets, 'dashboard' );

			// creates table with custom settings
			echo $this->templater->get_custom_setings_table( $option_name, $custom_dash, 'dashboard' );

			// create submit- and scrolltop-button
			echo $this->templater->get_widget_bottom();

		}

	}

	/**
	 * Setup the dashboard
	 * @return NULL
	 */
	public function dashboard_setup() {

		global $wp_meta_boxes;

		// exclude super admin
		if ( $this->common->exclude_super_admin() )
			return NULL;

		$option_name = $this->get_used_option();

		// refresh widgets
		$widgets = $this->get_dashboard_widgets();

		if ( current_user_can( 'manage_options' ) )
			$this->storage->set_option( 'available_' . $option_name, $widgets );

		$user         = wp_get_current_user();
		$user_roles   = $user->roles;

		foreach ( $user_roles as $role ) {

			$disabled = $this->storage->get_option( array( $option_name, $role ) );

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
	public function get_dashboard_widgets () {

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

}

}