<?php # -*- coding: utf-8 -*-
namespace Adminimize\Settings;

class Option {

	/**
	 * @var string
	 */
	private $option_name = 'mw_adminimize';

	/**
	 * Return the name of the option.
	 *
	 * @return string
	 */
	public function get_name() {

		return $this->option_name;
	}

	/**
	 * Return the option.
	 *
	 * @param array $default
	 *
	 * @return mixed|void
	 */
	public function get( array $default = array() ) {

		return get_option( $this->option_name, $default );
	}
}