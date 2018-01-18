<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*-

namespace Adminimize\SettingsPage;

use Adminimize\Settings\Option;
use Adminimize\SettingsPage\Tabs;

/**
 * View for the SettingsPage.
 */
class View implements ViewInterface {

	/**
	 * @var SettingsPageInterface
	 */
	private $settings_page;

	/**
	 * @var Option
	 */
	private $option;

	/**
	 * @var string
	 */
	private $page_title;

	/**
	 * Holds all instantiated tabs.
	 *
	 * @var Tabs\TabInterface[]
	 */
	private $tabs = array();

	/**
	 * View constructor.
	 *
	 * @param \Adminimize\SettingsPage\SettingsPage|\Adminimize\SettingsPage\SettingsPageInterface $settings_page
	 * @param Option                                                                               $option
	 */
	public function __construct( SettingsPage $settings_page, Option $option ) {

		$this->settings_page = $settings_page;
		$this->option        = $option;

		$this->page_title    = esc_html_x( 'Adminimize', 'Settings page title', 'adminimize' );
	}

	/**
	 * Adds the settings page to the WP menu.
	 */
	public function add_options_page() {

		$menu_title = esc_html_x( 'Adminimize', 'Settings menu title', 'adminimize' );
		$capability = $this->settings_page->get_capability();
		$menu_slug  = $this->settings_page->get_slug();

		add_options_page(
			$this->page_title,
			$menu_title,
			$capability,
			$menu_slug,
			[ $this, 'render_page' ]
		);
	}

	/**
	 * Enqueue scripts and styles necessary for the Settings Page.
	 *
	 * Only executed when the Settings Page is actually displayed.
	 *
	 * @return void
	 */
	public function enqueue_scripts_styles() {

		$screen = get_current_screen();
		if ( null === $screen ) {
			return;
		}

		if ( $screen->id !== 'settings_page_adminimize' ) {
			return;
		}

		wp_enqueue_script(
			'adminimize_admin',
			plugins_url( '../../assets/js/adminimize.js', __FILE__ ),
			[ 'jquery', 'jquery-ui-tabs' ]
		);
	}

	/**
	 * HTML and Content for the settings page.
	 */
	public function render_page() {
		$this->tabs = $this->instantiate_tabs();

		/** @noinspection PhpIncludeInspection */
		include $this->settings_page->get_template_path() . '/Templates/SettingsPage.php';
	}

	/**
	 * Get and instantiate all Tabs.
	 *
	 * @return Tabs\TabInterface[] Array of instantiated Tabs.
	 */
	private function instantiate_tabs() : array {
		$tabs = new Tabs\Tabs;

		$tabs_list = $tabs->get_tabs_list();

		$all_tabs = array();

		foreach ( $tabs_list as $tab_class ) {
			if ( class_exists( $tab_class ) ) {
				$tab = new $tab_class( $this->settings_page );
				$all_tabs[] = $tab;
			}
		}

		return $all_tabs;
	}
}
