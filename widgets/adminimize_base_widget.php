<?php
/**
 * Widget base class
 * Provide basic functionality used by nearly every widget
 * like TextDomain, common functions, storage, etc
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

if ( ! class_exists( 'Adminimize_Base_Widget' ) ) {

abstract class Adminimize_Base_Widget
{
	public $storage       = null;
	public $common        = null;
	public $templater     = null;
	public $pluginheaders = null;

	public function __construct() {

		$registry = new Adminimize_Registry();

		$this->storage       = $registry->get_storage();
		$this->common        = $registry->get_common_functions();
		$this->templater     = $registry->get_templater();
		$this->pluginheaders = $registry->get_pluginheaders();

	}

	/**
	 * Returns an array with the widget attributesto display it on the Adminimize dashboard
	 */
	abstract function get_attributes();

	/**
	 * Returns the hooks and filters a widget need to execute it's actions
	 */
	abstract public function get_hooks();

	public function get_used_option() {

		$attr = $this->get_attributes();
		return key_exists( 'option_name', $attr ) ? $attr['option_name'] : '';

	}

	public function get_validation_callbacks() {
		return array(
				'sanitize_checkboxes',     // no array = use method of validation class (standard function)
				'sanitize_custom_options',
		);
	}

	/**
	 * Create the widget content on Adminimize dashboard
	 */
	abstract function content();

}

}