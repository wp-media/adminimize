<?php
class Adminimize_Data_Container
{
	/**
	 * Key for options in database
	 * @var string
	 */
	const OPTION_KEY = 'mw_adminimize';

	/**
	 * Menu Slug
	 * @var string
	 */
	const MENU_SLUG  = 'adminimizeoop';

	/**
	 * Options from database
	 * @var array
	 */
	public static $options = null;

	/**
	 * Values stored in the datacontainer
	 * @var array
	 */
	public static $values = array();

	/**
	 * Set a value in the datacontainer
	 * @param string $name Name of the value
	 * @param string $value Value to set
	 * @return boolean
	 */
	public function set( $name = '', $value = null ) {

		if ( empty( $name ) || ! is_string( $name ) )
			return false;
		else
			$name = strtolower( $name );

		self::$values[$name] = $value;

		return true;

	}

	/**
	 * Get a value from the datacontainer
	 * @param string $name Value to retrieve
	 * @return boolean|NULL
	 */
	public function get( $name = '' ) {

		if ( empty( $name ) || ! is_string( $name ) )
			return false;
		else
			$name = strtolower( $name );

		return ( isset( self::$values[$name] ) ) ?
			self::$values[$name] : null;

	}

	/**
	 * Setup some base directories
	 * @param string $filename File inside the base directories
	 */
	public function set_basedirs( $filename ) {

		if ( ! empty( self::$values ) )
			return self::$values;

		$basedir = dirname( $filename );

		self::set( 'basefile', $filename );
		self::set( 'basedir',  $basedir );
		self::set( 'basejs',   $basedir . '/js/' );
// 		self::set( 'basecss',  $basedir . '/css/' );
// 		self::set( 'classes',  $basedir . '/classes/');
// 		self::set( 'widgets',  $basedir . '/widgets/' );

		self::set( 'basename',      plugin_basename( $filename ) );
		self::set( 'basefolder',    plugin_basename( dirname( $filename ) ) );
		self::set( 'MW_ADMIN_FILE', plugin_basename( $filename ) );

	}

	/**
	 * Get an option from the database
	 * @param string $name Name of the option
	 * @return mixed
	 */
	public function get_option( $name = '' ) {

		// check for use on multisite
		if ( is_multisite() && is_plugin_active_for_network( self::get( 'MW_ADMIN_FILE' ) ) )
			$adminimizeoptions = get_site_option( self::OPTION_KEY );
		else
			$adminimizeoptions = get_option( self::OPTION_KEY );

		// return all options if no name is set
		if ( empty( $name ) )
			return $adminimizeoptions;

		// return specified option
		return ( isset( $adminimizeoptions[$name] ) ) ?
			( $adminimizeoptions[$name] ) : null;

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

		if ( is_multisite() && is_plugin_active_for_network( $this->get( 'MW_ADMIN_FILE' ) ) )
			update_site_option( self::OPTION_KEY, $options );
		else
			update_option( self::OPTION_KEY, $options );

	}

}