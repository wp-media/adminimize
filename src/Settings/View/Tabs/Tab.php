<?php

namespace Adminimize\Settings\View\Tabs;

use Adminimize\Settings\Interfaces\ViewInterface;
use Adminimize\Settings\Interfaces\SettingsPageInterface;
use ChriCo\Fields\Element\Element;
use ChriCo\Fields\ElementFactory;
use ChriCo\Fields\ViewFactory;

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
        $this->elementFactory = $elementFactory;
        $this->viewFactory = $viewFactory;
        $this->settingsPage = $settingsPage;
        $this->form = $this->elementFactory->create($this->defineFields());
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
    public function render()
    {
        $html = $this->viewFactory->create('form')->render($this->form);
        $baseClassName = substr(strrchr(static::class, '\\'), 1);

        /** @noinspection PhpIncludeInspection */
        include $this->settingsPage->getTemplatePath() . '/' . $baseClassName . '.php';
    }
}
