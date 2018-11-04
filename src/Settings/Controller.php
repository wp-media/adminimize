<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*-

namespace Adminimize\Settings;

use Adminimize\Settings\View\View;
use Adminimize\Settings\Interfaces\ControllerInterface;

/**
 * Controller for the SettingsPage.
 */
class Controller implements ControllerInterface {

	/**
	 * @var View
	 */
	private $view;

	/**
	 * Controller constructor.
	 *
	 * @param View $view
	 */
	public function __construct( View $view ) {

		$this->view = $view;
	}

	/**
	 * Control the initialize for display settings.
	 */
	public function init() {

		add_action('admin_menu', [$this->view, 'addOptionsPage']);
		add_action('admin_enqueue_scripts', [$this->view, 'enqueueScriptsStyles']);
	}
}
