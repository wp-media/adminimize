<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*-

namespace Adminimize\SettingsPage;

use Adminimize\Settings\Option;
use function Composer\Autoload\includeFile;

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
	 * View constructor.
	 *
	 * @param \Adminimize\SettingsPage\SettingsPage|\Adminimize\SettingsPage\SettingsPageInterface $settings_page
	 * @param Option                                                                               $option
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
			[ $this, 'render_page' ]
		);
	}

	/**
	 * HTML and Content for the settings page.
	 */
	public function render_page() {

		include $this->settings_page->get_template_path() . '/Templates/SettingsPage.php';
	}
}