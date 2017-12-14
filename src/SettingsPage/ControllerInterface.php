<?php # -*- coding: utf-8 -*-
namespace Adminimize\SettingsPage;

interface ControllerInterface {

	/**
	 * Control the initialize of the settings page.
	 *
	 * @return mixed
	 */
	public function init();
}