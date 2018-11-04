<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*-

namespace Adminimize\Settings\View;

use Adminimize\Http\Request;
use ChriCo\Fields\ElementFactory;
use Adminimize\Settings\View\Tabs;
use Adminimize\Settings\SettingsPage;
use Adminimize\Settings\SettingsRepository;
use Adminimize\Settings\Interfaces\ViewInterface;
use Adminimize\Settings\Interfaces\SettingsPageInterface;
use ChriCo\Fields\ViewFactory;

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
    public $form;

    /**
     * @var ViewFactory
     */
    public $factory;

    /**
     * @var Request
     */
    private $request;

    /**
     * View constructor.
     *
     * @param \Adminimize\Settings\SettingsPage       $settingsPage
     * @param \Adminimize\Settings\SettingsRepository $settings
     * @param \ChriCo\Fields\ElementFactory           $form
     * @param \ChriCo\Fields\ViewFactory              $viewFactory
     */
	public function __construct(
	    SettingsPage $settingsPage,
        SettingsRepository $settings,
        ElementFactory $form,
        ViewFactory $viewFactory
    )
    {
        $this->settings = $settings;
        $this->settingsPage = $settingsPage;
        $this->form = $form;
        $this->factory = $viewFactory;
        $this->request = Request::fromGlobals();

		$this->pageTitle = esc_html_x('Adminimize', 'Settings page title', 'adminimize');
	}

	/**
	 * Adds the settings page to the WP menu.
	 */
	public function addOptionsPage()
    {
		$menuTitle = esc_html_x('Adminimize', 'Settings menu title', 'adminimize');
		$capability = $this->settingsPage->getCapability();
		$menuSlug = $this->settingsPage->getSlug();

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
     */
	public function update()
	{
        if ('POST' !== $this->request->server()->get('REQUEST_METHOD', 'GET')) {
            return false;
        }

        $postData = $this->request->data()->all();

	    return $this->settings->update($postData);
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
			plugins_url( '../../../assets/js/adminimize.js', __FILE__),
			['jquery', 'jquery-ui-tabs']
		);

		wp_enqueue_script('adminimize_admin');

		wp_register_style(
			'adminimize_admin',
			plugins_url('../../../assets/css/style.css', __FILE__),
			[]
		);

		wp_enqueue_style('adminimize_admin');
	}

	/**
	 * HTML and Content for the settings page.
	 */
	public function renderPage()
    {
		$this->tabs = $this->initTabs();

		/** @noinspection PhpIncludeInspection */
		include $this->settingsPage->getTemplatePath() . '/SettingsPage.php';
	}

	/**
	 * Get and instantiate all Tabs.
	 *
	 * @return Tabs\TabInterface[] Array of instantiated Tabs.
	 */
	private function initTabs(): array
    {
        $allTabs = [];
        $tabs = new Tabs\Tabs();

		foreach ($tabs->getTabsList() as $tabClass) {
			if (class_exists($tabClass)) {
				$tab = new $tabClass($this, $this->settingsPage);
				$allTabs[] = $tab;
			}
		}

		return $allTabs;
	}
}
