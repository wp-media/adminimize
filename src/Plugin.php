<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*-

namespace Adminimize;

use ChriCo\Fields\ElementFactory;
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
	public function __construct($file) {

		$this->file = $file;
	}

	/**
	 * Initialize the plugin.
	 */
	public function init() {

		// Set option possibility.
		$settingsRepository = new SettingsRepository();

		// Render the settings page.
		$settingsPage = new SettingsPage(__DIR__);
		$settingsPageView = new View($settingsPage, $settingsRepository, new ElementFactory);
		$settingsPageController = new Controller($settingsPageView);
		$settingsPageController->init();
	}
}
