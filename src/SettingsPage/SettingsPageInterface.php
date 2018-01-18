<?php # -*- coding: utf-8 -*-
namespace Adminimize\SettingsPage;

interface SettingsPageInterface {

	/**
	 * Get the path to the templates.
	 *
	 * @return string
	 */
	public function get_template_path() : string;
	/**
	 * @return string
	 */
	public function get_capability() : string;

	/**
	 * @return string
	 */
	public function get_slug() : string;
}
