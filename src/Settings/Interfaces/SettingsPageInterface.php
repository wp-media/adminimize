<?php # -*- coding: utf-8 -*-

namespace Adminimize\Settings\Interfaces;

interface SettingsPageInterface
{
	/**
	 * Get the path to the templates.
	 *
	 * @return string
	 */
	public function getTemplatePath(): string;

	/**
	 * @return string
	 */
	public function getCapability(): string;

	/**
	 * @return string
	 */
	public function getSlug(): string;
}
