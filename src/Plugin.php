<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*-

namespace Adminimize;

use Adminimize\Settings\View\View;
use Adminimize\Settings\Controller;
use Adminimize\Settings\SettingsPage;
use Adminimize\Settings\SettingsRepository;

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
		$settings_repository = new SettingsRepository();

		// Render the settings page.
		$settings_page = new SettingsPage( __DIR__ );
		$settings_page_view = new View( $settings_page, $settings_repository );
		$settings_page_control = new Controller( $settings_page_view );
		$settings_page_control->init();
	}
}
