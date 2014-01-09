<?php
/**
 * I_Adminimize_Widgets_Provider
 * @author Ralf Albert
 *
 * Interface for widgets provider classes
 *
 */

interface I_Adminimize_Widgets_Provider
{
	/**
	 * Returns an array with the used option names
	 * @return array
	 */
	public function get_used_options();

	/**
	 * Get the attributes of each available widget
	 * @return array
	 */
	public function get_widgets();

}