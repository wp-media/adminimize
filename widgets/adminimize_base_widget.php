<?php
/**
 * Provide basic functionality used by nearly every widget
 * like TextDomain, common functions, storage, etc
 * @author Ralf Albert
 *
 */
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
		$this->pluginheaders = new PluginHeaderReader( 'adminimize' );

	}

}