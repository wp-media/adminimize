<?php
/**
 * Global options widget
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

if ( ! class_exists( 'Adminimize_Global_Widget' ) ) {

class Adminimize_Global_Widget extends Adminimize_Base_Widget implements I_Adminimize_Widget
{
	public function get_attributes() {

		return array(
				'id'            => 'global_widget',
				'title'         => __( 'Global Options', $this->pluginheaders->TextDomain ),
				'context'       => 0,
				'priority'      => 'default',
				'callback_args' => array(),
				'option_name'   => 'global_option'
		);

	}

	public function content() {

		$attr = $this->get_attributes();

		$global_options = array(
				array( 'id' => '.show-admin-bar', 'title' => __('Admin Bar', $this->pluginheaders->TextDomain ) ),
				array( 'id' => '#favorite-actions', 'title' => __('Favorite Actions', $this->pluginheaders->TextDomain ) ),
				array( 'id' => '#screen-meta', 'title' => __('Screen-Meta', $this->pluginheaders->TextDomain ) ),
				array( 'id' => '#screen-options, #screen-options-link-wrap', 'title' => __('Screen Options', $this->pluginheaders->TextDomain ) ),
				array( 'id' => '#contextual-help-link-wrap', 'title' => __('Screen Options', $this->pluginheaders->TextDomain ) ),
				array( 'id' => '#your-profile .form-table fieldset', 'title' => __('Contextual Help', $this->pluginheaders->TextDomain ) ),
				array( 'id' => '#admin_color_scheme', 'title' => __('Admin Color Scheme', $this->pluginheaders->TextDomain ) ),
		);

		$custom_globals = $this->storage->get_custom_options( $attr['option_name'] );

		// merge standard options with custom options
		foreach ( $custom_globals['original'] as $title => $id ) {

			if ( empty( $id ) || empty( $title ) )
				continue;

			$global_options[] = array( 'id' => $id, 'title' => $title );

		}

		echo $this->templater->get_table( $attr['option_name'], $global_options, 'global' );
		echo $this->templater->get_custom_setings_table( $attr['option_name'], $custom_globals, 'global' );
		echo $this->templater->get_widget_bottom();

	}

}

}