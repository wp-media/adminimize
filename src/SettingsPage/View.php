<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*-

namespace Adminimize\SettingsPage;

use Adminimize\Settings\Option;
use Adminimize\SettingsPage\Tabs;
use function Composer\Autoload\includeFile;

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
		//$this->tabs          = array();

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

		if ( $screen->id === 'settings_page_adminimize' ) {
			wp_enqueue_script(
				'adminimize_admin',
				plugins_url( '../../assets/js/adminimize.js', __FILE__ ),
				[ 'jquery', 'jquery-ui-tabs' ]
			);
		}		
	}

	/**
	 * HTML and Content for the settings page.
	 */
	public function render_page() {
		$this->get_tabs();

		include $this->settings_page->get_template_path() . '/Templates/SettingsPage.php';
	}

	/**
	 * Get instances of all tabs.
	 *
	 * @return void
	 */
	private function get_tabs() {
		$tabs = new Tabs\Tabs;

		$tabs_list = $tabs->get_tabs_list();

		foreach ( $tabs_list as $tab_class ) {
			if ( ! is_null( $tab = $this->instantiate_tab( $tab_class ) ) ) {
				$this->tabs[] = $tab;
			}
		}
	}

	/**
	 * Instantiate single tab if class exists.
	 *
	 * @param string $tab_class Class name of Tab to be instantiated
	 * @return Tabs\TabInterface|null
	 */
	private function instantiate_tab( string $tab_class ) {
		if ( class_exists( $tab_class ) ) {
			return new $tab_class( $this->settings_page );
		} else {
			return null;
		}
	}
}
