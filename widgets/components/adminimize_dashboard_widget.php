<?php
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

		/*
		 * starting the widget content
		 */
		$attr = $this->get_attributes();
		$option = $attr['option_name'];

		// get widgets
		$widgets = $this->storage->get_option( $option );

		// no widgets?
		if ( empty( $widgets ) ) {

			// generating the cookies so we are also logged in when we get the dashboard with wp_remote_get()
			$cookies = array();

			foreach ( $_COOKIE as $name => $value ) {
				if ( 'PHPSESSID' === $name )
					continue;
				$cookies[] = new WP_Http_Cookie( array( 'name' => $name, 'value' => $value ) );
			}

			$args = array( 'cookies' => $cookies );

			wp_remote_get( admin_url( '/index.php' ), $args );

			printf( '<p><a href="%s">%s</a></p>', menu_page_url( $_GET['page'], false ), __( 'Please reload the page to complete the installation.', $this->pluginheaders->TextDomain ) );

// 			echo '<p class="form-invalid">';
// 			_e( 'To complete the installation for Dashboard Widgets you must visit your dashboard once and then come back to Settings > Adminimize to configure who has access to each widget.', ADMINIMIZE_TEXTDOMAIN );
// 			echo '</p>';
// 			return;

		} else {

			// create table
			echo $this->templater->get_table( $option, $widgets, 'dashboard' );

		}

	}

}