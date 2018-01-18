<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*-

namespace Adminimize\SettingsPage\Tabs;

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
            'Adminimize\\SettingsPage\\Tabs\\AdminBar',
            'Adminimize\\SettingsPage\\Tabs\\Dashboard',
            'Adminimize\\SettingsPage\\Tabs\\AdminMenu',
            'Adminimize\\SettingsPage\\Tabs\\AdminMenuze',
        );
    }

    /**
     * Returns array with namespaced class names of all tabs.
     *
     * @return array
     */
    public function get_tabs_list() : array {
        /**
         * Add Tabs to the SettingsPage.
         * 
         * All classes added must implement Adminimize\SettingsPage\Tabs\TabsInterface.
         * 
         * @param array $tabs {
         *      Array of namespaced classes.
         * 
         *      $type string $class Namespaced class.
         * }
         */
        return apply_filters( 'adminimize_settings_tabs', $this->tabs );
    }
}