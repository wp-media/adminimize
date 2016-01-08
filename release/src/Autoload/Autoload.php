<?php # -*- coding: utf-8 -*-
namespace Inpsyde\Autoload;

/**
 * Class Autoload
 *
 * t5-libraries inspired autoloader.
 *
 * @link    https://github.com/toscho/t5-libraries/
 * @package Inpsyde\Autoload
 * @version 2016-01-08
 */
class Autoload {

	/**
	 * @var AutoloadRule[]
	 */
	private $rules = array();

	/**
	 * Register this object to spl autoload stack.
	 */
	public function __construct() {

		spl_autoload_register( array( $this, 'load' ) );
	}

	/**
	 * Add a single autoload rule.
	 *
	 * @param AutoloadRule $rule Autoload rule object
	 */
	public function add_rule( AutoloadRule $rule ) {

		$this->rules[] = $rule;
	}

	/**
	 * Load a class or interface.
	 *
	 * @param string $name Class or interface name
	 *
	 * @return bool
	 */
	public function load( $name ) {

		foreach ( $this->rules as $rule ) {
			if ( $rule->load( $name ) ) {
				return TRUE;
			}
		}

		return FALSE;
	}

}