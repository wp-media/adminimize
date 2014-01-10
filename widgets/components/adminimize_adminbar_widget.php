<?php
/**
 * Adminbar widget
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

if ( ! class_exists( 'Adminimize_AdminBar_Widget' ) ) {

class Adminimize_AdminBar_Widget extends Adminimize_Base_Widget implements I_Adminimize_Widget
{

	public function get_attributes() {

		return array(
				'id'            => 'adminbar_widget',
				'title'         => __( 'Adminbar Options &middot; Beta', $this->pluginheaders->TextDomain),
				'context'       => 0,
				'priority'      => 'default',
				'callback_args' => array(),
				'option_name'   => 'admin_bar'
		);

	}

	public function content() {

		global $wp_admin_bar;

		$attr = $this->get_attributes();

		if ( ! isset( $wp_admin_bar ) )
			$wp_admin_bar = '';

		$admin_bar_items = $this->common->get_admin_bar_items();

		echo $this->templater->get_table( $attr['option_name'], $admin_bar_items, 'admin_bar' );
		echo $this->templater->get_widget_bottom();

	}

}

}