<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*-

namespace Adminimize\SettingsPage;

class SettingsPage implements SettingsPageInterface {

	/**
	 * @var string
	 */
	private $capability;

	/**
	 * @var string
	 */
	private $slug;

	/**
	 * SettingsPageInterface constructor.
	 */
	public function __construct() {

		$this->capability = 'manage_options';
		$this->slug       = 'adminimize';
	}

	/**
	 * Get the allowed capability to view settings page.
	 *
	 * @return string
	 */
	public function get_capability() : string {

		return $this->capability;
	}

	/**
	 * Get the slug for the settings area.
	 *
	 * @return string
	 */
	public function get_slug() : string {

		return $this->slug;
	}
}