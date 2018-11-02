<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*-

namespace Adminimize\SettingsPage\Interfaces;

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
     * Define which fields should be displayed in this tab.
     *
     * @return array
     */
	public function define_fields(): array;

	/**
	 * Render content of the tab.
	 *
	 * @return void
	 */
	public function render_tab_content();
}
