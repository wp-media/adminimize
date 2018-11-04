<?php

namespace Adminimize\Settings\View\Tabs;

use Adminimize\Settings\Interfaces\ViewInterface;
use Adminimize\Settings\Interfaces\SettingsPageInterface;

abstract class Tab
{
    /**
     * Holds an instance of the settings page
     *
     * @var \Adminimize\Settings\Interfaces\SettingsPageInterface
     */
    protected $settingsPage;

    /**
     * Holds an instance of the settings page
     *
     * @var \Adminimize\Settings\Interfaces\ViewInterface $view
     */
    protected $view;

    /**
     * @var \ChriCo\Fields\Element\Form
     */
    protected $form;

    /**
     * Constructor.
     *
     * @param \Adminimize\Settings\Interfaces\ViewInterface         $view
     * @param \Adminimize\Settings\Interfaces\SettingsPageInterface $settingsPage
     */
    public function __construct(ViewInterface $view, SettingsPageInterface $settingsPage)
    {
        $this->view = $view;
        $this->settingsPage = $settingsPage;
        $this->form = $this->view->form->create($this->defineFields());
    }

    /**
     * @return array
     */
    protected function userRoles()
    {
        $allRoles = [];
        $wpRoles  = get_editable_roles();

        foreach ($wpRoles as $roleKey => $roleData) {
            $allRoles[$roleKey] = $roleData['name'];
        }

        return $allRoles;
    }

    /**
     * Get display title for the tab.
     *
     * @return string
     */
    abstract public function getTabTitle(): string;

    /**
     * Define which fields should be displayed in this tab.
     *
     * @return array
     */
    abstract public function defineFields(): array;

    /**
     * Render content of the tab.
     *
     * @return void
     */
    abstract public function render();
}
