<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace Adminimize\SettingsPage\Tabs;

use Adminimize\SettingsPage\Interfaces\TabsInterface;

/**
 * Define Tabs for the Settings Page.
 */
class Tabs implements TabsInterface {

	/**
	 * Holds all namespaced class names for tabs defined by this class.
	 *
	 * @var array
	 */
	private $tabs;

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->tabs = array(
			AdminBar::class,
			Dashboard::class,
			AdminMenu::class,
		);
	}

	/**
	 * Returns array with namespaced class names of all tabs.
	 *
	 * @return array
	 */
	public function get_tabs_list(): array {

		/**
		 * Add Tabs to the SettingsPage.
		 * All classes added must implement Adminimize\SettingsPage\Tabs\TabsInterface.
		 *
		 * @param array $tabs {
		 *                    Array of namespaced classes.
		 *                    $type string $class Namespaced class.
		 *                    }
		 */
		return apply_filters( 'adminimize.settings_tabs', $this->tabs );
	}
}
