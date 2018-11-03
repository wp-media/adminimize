<?php

namespace Adminimize\Settings\View\Tabs;

use ChriCo\Fields\ViewFactory;
use ChriCo\Fields\ElementFactory;
use Adminimize\Settings\Interfaces\SettingsPageInterface;

abstract class Tab
{
    /**
     * Holds an instance of the settings page
     *
     * @var \Adminimize\Settings\Interfaces\SettingsPageInterface
     */
    protected $settings_page;

    /**
     * @var ElementFactory
     */
    protected $form;

    /**
     * @var ViewFactory
     */
    protected $view;

    /**
     * Constructor.
     *
     * @param \Adminimize\SettingsPage\Interfaces\SettingsPageInterface $settings_page
     */
    public function __construct(SettingsPageInterface $settings_page)
    {
        $this->form = (new ElementFactory())->create($this->define_fields());
        $this->view = (new ViewFactory())->create('form');
        $this->settings_page = $settings_page;
    }

    /**
     * @return array
     */
    protected function user_roles()
    {
        $wp_roles  = get_editable_roles();
        $all_roles = [];

        foreach ($wp_roles as $role_key => $role_data) {
            $all_roles[$role_key] = $role_data['name'];
        }

        return $all_roles;
    }

    /**
     * Get display title for the tab.
     *
     * @return string
     */
    abstract public function get_tab_title(): string;

    /**
     * Define which fields should be displayed in this tab.
     *
     * @return array
     */
    abstract public function define_fields(): array;

    /**
     * Render content of the tab.
     *
     * @return void
     */
    abstract public function render_tab_content();
}
