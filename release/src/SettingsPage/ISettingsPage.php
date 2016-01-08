<?php # -*- coding: utf-8 -*-
namespace Adminimize\SettingsPage;

interface ISettingsPage {

	/**
	 * @return string
	 */
	public function get_capability();

	/**
	 * @return string
	 */
	public function get_slug();
}