<?php declare(strict_types=1); // -*- coding: utf-8 -*-

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
     * Returns the allowed HTML elements and attributes to be used in wp_kses.
     *
     * @return array
     */
    public function allowedElements(): array
    {
        $genericAttributes = [
            'id' => [],
            'class' => [],
        ];

        $formAttributes = [
            'type' => [],
            'name' => [],
            'value' => [],
            'checked' => [],
            '_lpchecked' => [],
            'action' => [],
        ];

        $attributes = array_merge($genericAttributes, $formAttributes);

        $elements = [
            'input' => $attributes,
            'form' => $attributes,
            'table' => $genericAttributes,
            'tbody' => $genericAttributes,
            'tr' => $genericAttributes,
            'th' => $genericAttributes,
            'td' => $genericAttributes,
        ];

        return $elements;
    }

    /**
     * Returns the rendered HTML form as a string.
     *
     * @return string
     */
    public function form(): string
    {
        return $this->form ? $this->viewFactory->create('form')->render($this->form) : '';
    }

    /**
     * Render content of the tab.
     *
     * @return void
     */
    public function render()
    {
        $baseClassName = substr(strrchr(static::class, '\\'), 1);

        /**
         * @noinspection PhpIncludeInspection
         */
        include $this->settingsPage->templatePath() . '/' . $baseClassName . '.php';
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
}
