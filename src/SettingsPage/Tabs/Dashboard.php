<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace Adminimize\SettingsPage\Tabs;

use Adminimize\SettingsPage;

/**
 * Stub: Tab for Dashboard Settings.
 */
class Dashboard implements TabInterface {

	/**
	 * Holds an instance of the settings page
	 *
	 * @var SettingsPageInterface
	 */
	private $settings_page;

	/**
	 * Constructor.
	 *
	 * @param SettingsPage\SettingsPageInterface $settings_page
	 */
	public function __construct( SettingsPage\SettingsPageInterface $settings_page ) {

		$this->settings_page = $settings_page;
	}

	/**
	 * Get display title for the tab.
	 *
	 * @return string
	 */
	public function get_tab_title(): string {

		return esc_html_x( 'Dashboard', 'Tab Title', 'adminimize' );
	}

	/**
	 * Render content of the tab.
	 *
	 * @return void
	 */
	public function render_tab_content() {

		include $this->settings_page->get_template_path() . '/Templates/Dashboard.php';
	}
}