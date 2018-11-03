<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*-

namespace Adminimize\Settings\View\Tabs;

use Adminimize\Settings\Interfaces\SettingsPageInterface;

/**
 * Stub: Tab for Dashboard Settings.
 */
class Dashboard extends Tab {

	/**
	 * Holds an instance of the settings page.
	 *
	 * @var \Adminimize\Settings\Interfaces\SettingsPageInterface
	 */
	private $settings_page;

    /**
     * Constructor.
     *
     * @param \Adminimize\Settings\Interfaces\SettingsPageInterface $settings_page
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

		return esc_html_x( 'Dashboard', 'Tab Title', 'adminimize' );
	}

    /**
     * @return array
     */
    public function define_fields(): array
    {
        return [];
    }

	/**
	 * Render content of the tab.
	 *
	 * @return void
	 */
	public function render_tab_content() {

		/** @noinspection PhpIncludeInspection */
		include $this->settings_page->get_template_path() . '/Dashboard.php';
	}
}
