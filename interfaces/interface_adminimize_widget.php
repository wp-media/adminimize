<?php
/**
 * Interface for Adminimize widget classes
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

if ( ! class_exists( 'I_Adminimize_Widget' ) ) {

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

}