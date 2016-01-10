<?php # -*- coding: utf-8 -*-
namespace Adminimize\SettingsPage;

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

		add_action( 'admin_menu', [ $this->view, 'add_options_page' ] );
	}
}