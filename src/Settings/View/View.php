<?php declare(strict_types = 1); // -*- coding: utf-8 -*-

namespace Adminimize\Settings\View;

use Adminimize\Http\Request;
use ChriCo\Fields\ViewFactory;
use ChriCo\Fields\ElementFactory;
use Adminimize\Settings\SettingsPage;
use Adminimize\Settings\View\Tabs\Tabs;
use Adminimize\Settings\SettingsRepository;
use Adminimize\Settings\Interfaces\ViewInterface;
use Adminimize\Settings\Interfaces\SettingsPageInterface;

/**
 * View for the SettingsPage.
 */
class View implements ViewInterface
{
    /**
     * @var SettingsRepository
     */
    private $settings;

    /**
     * @var SettingsPageInterface
     */
    private $settingsPage;

    /**
     * @var string
     */
    private $pageTitle;

    /**
     * Holds all instantiated tabs.
     *
     * @var \Adminimize\Settings\View\Tabs\Tab
     */
    private $tabs = [];

    /**
     * @var ElementFactory
     */
    private $elementFactory;

    /**
     * @var ViewFactory
     */
    private $viewFactory;

    /**
     * @var Request
     */
    private $request;

    /**
     * View constructor.
     *
     * @param \Adminimize\Settings\SettingsPage       $settingsPage
     * @param \Adminimize\Settings\SettingsRepository $settings
     * @param \ChriCo\Fields\ElementFactory           $elementFactory
     * @param \ChriCo\Fields\ViewFactory              $viewFactory
     */
    public function __construct(
        SettingsPage $settingsPage,
        SettingsRepository $settings,
        ElementFactory $elementFactory,
        ViewFactory $viewFactory
    ) {

        $this->settings = $settings;
        $this->settingsPage = $settingsPage;
        $this->elementFactory = $elementFactory;
        $this->viewFactory = $viewFactory;
        $this->request = Request::fromGlobals();

        $this->pageTitle = esc_html_x('Adminimize', 'Settings page title', 'adminimize');
    }

    /**
     * Adds the settings page to the WP menu.
     */
    public function addOptionsPage()
    {
        $menuTitle = esc_html_x('Adminimize', 'Settings menu title', 'adminimize');
        $capability = $this->settingsPage->capability();
        $menuSlug = $this->settingsPage->slug();

        $hook = add_options_page(
            $this->pageTitle,
            $menuTitle,
            $capability,
            $menuSlug,
            [$this, 'renderPage']
        );

        add_action('load-' . $hook, [$this, 'update']);
    }

    /**
     * @return bool
     * @throws \Adminimize\Exceptions\SettingNotFoundException
     */
    public function update()
    {
        if ($this->request->server()->get('REQUEST_METHOD', 'GET') !== 'POST') {
            return false;
        }

        $postData = $this->request->data()->all();

        if ($this->settings->update($postData)) {
            wp_redirect($this->settingsPage->url());
        }
    }

    /**
     * Enqueue scripts and styles necessary for the Settings Page.
     *
     * Only executed when the Settings Page is actually displayed.
     *
     * @return void
     */
    public function enqueueScriptsStyles()
    {
        $screen = get_current_screen();

        if (null === $screen) {
            return;
        }

        if ($screen->id !== 'settings_page_adminimize') {
            return;
        }

        wp_register_script(
            'adminimize_admin',
            plugins_url('../../../assets/js/adminimize.js', __FILE__), ['jquery']
        );

        wp_enqueue_script('adminimize_admin');

        wp_register_style(
            'adminimize_admin',
            plugins_url('../../../assets/css/style.css', __FILE__), []
        );

        wp_enqueue_style('adminimize_admin');
    }

    /**
     * Returns all user roles.
     *
     * @return array
     */
    protected function userRoles()
    {
        $wpRoles = get_editable_roles();

        $allRoles = [];
        foreach ($wpRoles as $roleKey => $roleData) {
            $allRoles[$roleKey] = $roleData['name'];
        }

        return $allRoles;
    }

    /**
     * Get and instantiate all Tabs.
     *
     * @return \Adminimize\Settings\View\Tabs\Tab[] Array of instantiated Tabs.
     */
    private function initTabs(): array
    {
        $allTabs = [];
        $tabs = new Tabs();

        foreach ($tabs->list() as $tabClass) {
            if (class_exists($tabClass)) {
                $tab = new $tabClass($this->settingsPage, $this->elementFactory, $this->viewFactory);
                $allTabs[] = $tab;
            }
        }

        return $allTabs;
    }

    /**
     * HTML and Content for the settings page.
     */
    public function renderPage()
    {
        $this->tabs = $this->initTabs();

        /**
         * @noinspection PhpIncludeInspection
         */
        include $this->settingsPage->templatePath() . '/SettingsPage.php';
    }
}
