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

class Adminimize_Base_Widget
{
	public $storage       = null;
	public $common        = null;
	public $templater     = null;
	public $pluginheaders = null;

	public function __construct() {

		$this->storage       = new Adminimize_Storage();
		$this->common        = new Adminimize_Common();
		$this->templater     = new Adminimize_Templater();
		$this->pluginheaders = new Adminimize_PluginHeaders();

	}

}

}