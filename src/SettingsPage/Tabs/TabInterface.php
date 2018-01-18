<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*-

namespace Adminimize\SettingsPage\Tabs;

/**
 * Interface for single tabs.
 */
interface TabInterface {

	/**
	 * Get display title for the tab.
	 *
	 * @return string
	 */
	public function get_tab_title(): string;

	/**
	 * Render content of the tab.
	 *
	 * @return void
	 */
	public function render_tab_content();
}
