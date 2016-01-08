<?php # -*- coding: utf-8 -*-
namespace Adminimize\SettingsPage;

use Adminimize\Settings\Option;

class View implements IView {

	/**
	 * @var SettingsPage
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
	 * View constructor.
	 *
	 * @param SettingsPage $settings_page
	 * @param Option       $option
	 */
	public function __construct( SettingsPage $settings_page, Option $option ) {

		$this->settings_page = $settings_page;
		$this->option        = $option;

		$this->page_title = esc_html_x( 'Adminimize', 'Settings page title', 'adminimize' );
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
			[ $this, 'get_page' ]
		);
	}

	/**
	 * HTML and Content for the settings page.
	 */
	public function get_page() {

		?>
		The Admin Page
		<?php
	}
}