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

		$attr = $this->get_attributes();

		// get widgets
		$widgets = $this->storage->get_option( $attr['option_name'] );

		// no widgets?
		if ( empty( $widgets ) ) {
//TODO read widgets on plugin activation
			// generating the cookies so we are also logged in when we get the dashboard with wp_remote_get()
			$cookies = array();

			foreach ( $_COOKIE as $name => $value ) {
				if ( 'PHPSESSID' === $name )
					continue;
				$cookies[] = new WP_Http_Cookie( array( 'name' => $name, 'value' => $value ) );
			}

			wp_remote_get( admin_url( '/index.php' ), array( 'cookies' => $cookies ) );

			printf( '<p><a href="%s">%s</a></p>', menu_page_url( $_GET['page'], false ), __( 'Please reload the page to complete the installation.', $this->pluginheaders->TextDomain ) );

		} else {

			$custom_dash = $this->storage->get_custom_options( $attr['option_name'] );

			// merge standard options with custom options
			foreach ( $custom_dash['original'] as $title => $id ) {

				if ( empty( $id ) || empty( $title ) )
					continue;

				$widgets[] = array( 'id' => $id, 'title' => $title );

			}

			// create table
			echo $this->templater->get_table( $attr['option_name'], $widgets, 'dashboard' );

			// creates table with custom settings
			echo $this->templater->get_custom_setings_table( $attr['option_name'], $custom_dash, 'dashboard' );

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

		// refresh widgets
		$widgets = $this->get_dashboard_widgets();

		if ( current_user_can( 'manage_options' ) )
			$this->storage->set_option( 'dashboard_widgets', $widgets );

		$user         = wp_get_current_user();
		$user_roles   = $user->roles;
//FIXME Using 'custom' as role name is dangerous if someone creates an role and named it 'custom'!!
		$user_roles[] = 'custom'; // add the custom options 'role'

		foreach ( $user_roles as $role ) {

			$disabled = $this->common->get_option( 'dashboard_widgets_' . $role );

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