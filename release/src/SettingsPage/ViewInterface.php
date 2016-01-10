<?php # -*- coding: utf-8 -*-
namespace Adminimize\SettingsPage;

interface ViewInterface {

	/**
	 * Adds the settings page to the WP menu.
	 *
	 * @return mixed
	 */
	public function add_options_page();

	/**
	 * HTML and Content for the settings page.
	 */
	public function get_page();
}