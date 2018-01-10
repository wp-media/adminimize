<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*-

namespace Adminimize\SettingsPage\Tabs;

class Dashboard implements TabInterface {

	/**
	 * Returns the title
	 *
	 * @return string
	 */
	public function getTitle(): string {
		return __('Dashboard');
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
		return '<h3>' . esc_html_x('Dashboard Options', 'Settings tab title', 'adminimize') . '</h3>';
	}
}
