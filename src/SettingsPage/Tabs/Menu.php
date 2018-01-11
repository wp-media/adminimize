<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*-

namespace Adminimize\SettingsPage\Tabs;

class Menu implements TabInterface {

	/**
	 * Returns the title
	 *
	 * @return string
	 */
	public function getTitle(): string {
		return __('Menu');
	}

	/**
	 * Returns the subtitle
	 *
	 * @return string
	 */
	public function getSubTitle(): string {
		return '';
	}

	/**
	 * Returns the tab content
	 *
	 * @return string
	 */
	public function getContent(): string {
		return '<h3>' . esc_html_x('Menu Options', 'Settings tab title', 'adminimize') . '</h3>';
	}
}
