<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*-

namespace Adminimize\SettingsPage\Interfaces;

interface ControllerInterface {

	/**
	 * Control the initialize of the settings page.
	 *
	 * @return mixed
	 */
	public function init();
}
