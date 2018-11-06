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
    protected $settingsPage;

    /**
     * ElementFactory
     */
    protected $elementFactory;

    /**
     * ViewFactory
     */
    protected $viewFactory;

    /**
     * @var \ChriCo\Fields\Element\Form
     */
    protected $form;

    /**
     * Constructor.
     *
     * @param \Adminimize\Settings\Interfaces\SettingsPageInterface $settingsPage
     * @param \ChriCo\Fields\ElementFactory                         $elementFactory
     * @param \ChriCo\Fields\ViewFactory                            $viewFactory
     */
    public function __construct(SettingsPageInterface $settingsPage, ElementFactory $elementFactory, ViewFactory $viewFactory)
    {
        $this->viewFactory = $viewFactory;
        $this->settingsPage = $settingsPage;
        $this->elementFactory = $elementFactory;

        if ($fields = $this->defineFields()) {
            $this->form = $this->elementFactory->create($fields);
        }
    }

    /**
     * Get display title for the tab.
     *
     * @return string
     */
    abstract public function title(): string;

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
    public function render()
    {
        if ($this->form) {
            $html = $this->viewFactory->create('form')->render($this->form);
        }

        $baseClassName = substr(strrchr(static::class, '\\'), 1);

        /** @noinspection PhpIncludeInspection */
        include $this->settingsPage->getTemplatePath() . '/' . $baseClassName . '.php';
    }
}
