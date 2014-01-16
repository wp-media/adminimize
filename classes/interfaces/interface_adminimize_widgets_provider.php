<?php
/**
 * Interface for widgets provider classes
 *
 * PHP version 5.2
 *
 * @category   PHP
 * @package    WordPress
 * @subpackage RalfAlbert\Tooling
 * @author     Ralf Albert <me@neun12.de>
 * @license    GPLv3 http://www.gnu.org/licenses/gpl-3.0.txt
 * @version    1.0
 * @link       http://wordpress.com
 */

if ( ! class_exists( 'I_Adminimize_Widgets_Provider' ) ) {

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
	public function get_widgets_attributes();

	/**
	 * Get the action hooks and filters for each widget
	 * @return array
	 */
	public function get_widgets_actions();

}

}