<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*-

namespace Adminimize\SettingsPage\Tabs;

interface TabInterface {

	/**
	 * Returns the title
	 *
	 * @return string
	 */
	public function getTitle(): string;

	/**
	 * Returns the subtitle
	 *
	 * @return string
	 */
	public function getSubTitle(): string;

	/**
	 * Returns the tab content
	 *
	 * @return string
	 */
	public function getContent(): string;
}
