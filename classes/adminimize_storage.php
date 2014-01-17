<?php
/**
 * Adminimizer storage class
 *
 * PHP version 5.2
 *
 * @category   PHP
 * @package    WordPress
 * @subpackage Inpsyde\Adminimize
 * @author     Ralf Albert <me@neun12.de>
 * @license    GPLv3 http://www.gnu.org/licenses/gpl-3.0.txt
 * @version    1.0
 * @link       http://wordpress.com
 */

if ( ! class_exists( 'Adminimize_Storage' ) ) {

class Adminimize_Storage extends ExtendedStandardClass
{
	/**
	 * ID for the ExtendedStandardClass
	 * @var string
	 */
	const ID = 'adminimize_storage';

	/**
	 * Key for options in database
	 * @var string
	 */
	const OPTION_KEY = 'adminimizeOOP';

	public function __construct() {

		$this->set_id( self::ID );

	}

	/**
	 * Setup some base directories
	 * @param string $filename File inside the base directories
	 */
	public function set_basedirs( $filename ) {

		$basedir = dirname( $filename );

		$this->basefile      = $filename;
		$this->basedir       = $basedir;
		$this->basejs        = $basedir . '/js/';
		$this->basename      = plugin_basename( $filename );
		$this->basefolder    = plugin_basename( dirname( $filename ) );
		$this->MW_ADMIN_FILE = plugin_basename( $filename );
		$this->widgets_dir   =  $basedir . 'widgets';

// 		self::set( 'basecss',  $basedir . '/css/' );
// 		self::set( 'classes',  $basedir . '/classes/');


	}

	/**
	 * Get an option from the database
	 * @param string $name Name of the option
	 * @return mixed
	 */
	public function get_option( $name = '' ) {

		// check for use on multisite
		if ( is_multisite() && is_plugin_active_for_network( $this->MW_ADMIN_FILE ) )
			$adminimizeoptions = get_site_option( self::OPTION_KEY );
		else
			$adminimizeoptions = get_option( self::OPTION_KEY );

		// return all options if no name is set
		if ( empty( $name ) )
			return $adminimizeoptions;

		// return nested options
		if ( is_array( $name ) )
			return $this->get_nested_option( $name, $adminimizeoptions );

		// return specified option
		return ( isset( $adminimizeoptions[$name] ) ) ?
			( $adminimizeoptions[$name] ) : null;

	}

	/**
	 * Returns a nested option
	 * @param  array $name   Array with index to search for
	 * @param  array $option Array with options
	 * @return mixed         Null if the option could not be found, else the option value
	 */
	public function get_nested_option( $name = array(), $option = array() ) {

		$found = true;

		foreach ( $name as $key ) {

			if ( is_array( $option ) ) {

				if ( key_exists( $key, $option ) ) {

					 $option = $option[ $key ];

				} else {

					$found = false;

				}

			}

		}

		return ( true == $found ) ?
			$option : null;

	}

	/**
	 * Set an option in the database
	 * @param string $name Name of the option
	 * @param mixed $value Value to be set
	 */
	public function set_option( $name = '', $value = null ) {

		if ( empty( $name ) )
			return  false;

		$options = $this->get_option();

		$options[$name] = $value;

		if ( is_multisite() && is_plugin_active_for_network( $this->MW_ADMIN_FILE ) )
			return update_site_option( self::OPTION_KEY, $options );
		else
			return update_option( self::OPTION_KEY, $options );

	}

	/**
	 * Get custom options
	 * @param string $option_name
	 * @return array	$anon	Array in format array( 'options' => [array], 'values' => [array] )
	 */
	public function get_custom_options( $option_name = '' ) {

		$customs = $this->get_option( array( $option_name, 'custom' ) );

		return ( empty( $customs ) ) ?
			array( 'custom_left' => array(), 'custom_right' => array(), 'original' => array() ) :
			array(
				'custom_left'  => array_keys( $customs ),
				'custom_right' => array_values( $customs ),
				'original'     => $customs
			);

	}

}

}