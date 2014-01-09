<?php
interface I_Adminimize_Widget
{
	/**
	 * Get the widgets attributes
	 * @return array	$anon	Array with widget attributes
	 */
	public function get_attributes();

	/**
	 * Outputs the content of the widget
	 */
	public function content();

}