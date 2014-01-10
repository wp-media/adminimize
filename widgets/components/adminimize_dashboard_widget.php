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

}

}