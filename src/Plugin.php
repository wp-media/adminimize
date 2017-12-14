<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*-

namespace Adminimize;

class Plugin {

	/**
	 * @var string
	 */
	private $file;

	/**
	 * Plugin constructor.
	 *
	 * @param string $file Main plugin file.
	 */
	public function __construct( $file ) {

		$this->file = $file;
	}

	/**
	 * Initialize the plugin.
	 */
	public function init() {

		// Set option possibility.
		$option = new Settings\Option();

		// Render the settings page.
		$settings_page         = new SettingsPage\SettingsPage( __DIR__ );
		$settings_page_view    = new SettingsPage\View( $settings_page, $option );
		$settings_page_control = new SettingsPage\Controller( $settings_page_view );
		$settings_page_control->init();
	}
}