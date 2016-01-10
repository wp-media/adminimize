<?php # -*- coding: utf-8 -*-
namespace Adminimize\SettingsPage;

interface SettingsPageInterface {

	/**
	 * @return string
	 */
	public function get_capability();

	/**
	 * @return string
	 */
	public function get_slug();
}