<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*-

namespace Adminimize\Settings;

/**
 * Class Settings to handle settings in the database.
 *
 * @package Adminimize\Settings
 */
class Settings {

	/**
	 * @var string
	 */
	private $option_name;

	/**
	 * Settings constructor.
	 *
	 * @param Option $option
	 */
	public function __construct( Option $option ) {

		$this->option_name = $option->get_name();
	}

}