<?php
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

		echo $this->templater->get_table( $attr['option_name'], $global_options, 'global' );

	}

}