<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace Adminimize\SettingsPage\Interfaces;

/**
 * Interface for Tabs class.
 */
interface TabsInterface {

	/**
	 * Returns array with namespaced class names of all tabs.
	 *
	 * @return array
	 */
	public function get_tabs_list(): array;
}
