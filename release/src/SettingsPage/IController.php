<?php # -*- coding: utf-8 -*-
namespace Adminimize\SettingsPage;

interface IController {

	/**
	 * Control the initialize of the settings page.
	 *
	 * @return mixed
	 */
	public function init();
}