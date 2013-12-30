<?php
/**
 * PluginHeader_Reader
 * @author Ralf Albert
 * @version 1.0
 *
 * Reads the plugin header from a given file and stores the data
 */

if ( !class_exists( 'PluginHeader_Reader' ) ) {

class PluginHeader_Reader
{
	/**
	 * Array for data from plugin header
	 * @var array
	 */
	public static $data = array();

	/**
	 * Reads the plugin header from given filename
	 * @param string $filename File with plugin header
	 * @return boolean False if the file does not exists
	 */
	public static function init( $filename = '' ) {

		if ( empty( $filename ) || ! file_exists( $filename ) )
			return false;

		if ( ! function_exists( 'get_plugin_data' ) )
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

		self::$data = get_plugin_data( $filename );

	}

	/**
	 * Returns an instance of itself
	 * @return object Instance of itself
	 */
	public static function get_instance() {
		return new self();
	}

	/**
	 * Magic get; returns the value if it is set
	 * @param string $value Value to be retrieved
	 * @return string $value The value if set or empty string
	 */
	public function __get( $value ) {

		$value = (string) $value;

		return ( isset( self::$data[ $value ] ) ) ?
			self::$data[ $value ] : '';

	}

}

}