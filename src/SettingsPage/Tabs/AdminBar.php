<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*-

namespace Adminimize\SettingsPage\Tabs;

use Adminimize\SettingsPage\Interfaces\TabInterface;
use Adminimize\SettingsPage\Interfaces\SettingsPageInterface;

/**
 * Stub: Tab for Admin Bar Settings.
 */
class AdminBar implements TabInterface {

	/**
	 * Holds an instance of the settings page
	 *
	 * @var \Adminimize\SettingsPage\Interfaces\SettingsPageInterface
	 */
	private $settings_page;

    /**
     * Constructor.
     *
     * @param \Adminimize\SettingsPage\Interfaces\SettingsPageInterface $settings_page
     */
	public function __construct( SettingsPageInterface $settings_page ) {

		$this->settings_page = $settings_page;
	}

	/**
	 * Get display title for the tab.
	 *
	 * @return string
	 */
	public function get_tab_title(): string {

		return esc_html_x( 'Admin Bar', 'Tab Title', 'adminimize' );
	}

	/**
	 * Render content of the tab.
	 *
	 * @return void
	 */
	public function render_tab_content() {

		/** @noinspection PhpIncludeInspection */
		include $this->settings_page->get_template_path() . '/AdminBar.php';
	}
}
