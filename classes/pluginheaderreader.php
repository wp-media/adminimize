<?php
/**
 * PluginHeaderReader
 * @author Ralf Albert
 * @version 1.0
 *
 * Reads the plugin header from a given file and stores the data
 */
if ( ! class_exists( 'PluginHeaderReader' ) ) {

class PluginHeaderReader extends ExtendedStandardClass implements I_FileHeaderReader
{

	/**
	 * Array for id's
	 * @var arary
	 */
	public static $ids = array();

	/**
	 * Reads the plugin header from given filename
	 * @param string $filename File with plugin header
	 * @return boolean False if the file does not exists
	 * @return boolean Returns false on error or true on success
	 */
	public static function init( $filename = '', $id = '' ) {

		if ( ! defined( 'ABSPATH' ) )
			trigger_error( 'This class requires WordPress. ABSPATH not found', E_USER_ERROR );

		if ( empty( $filename ) || ! file_exists( $filename ) )
			return false;

		if ( empty( $id ) || ! is_string( $id ) )
			return false;

		self::$id = $id;
		self::$ids[ $id ] = $filename;

		if ( ! is_object( self::$data ) )
			self::$data = new stdClass();

		self::$data->$id = new stdClass();

		return true;

	}

	/**
	 * Returns an instance of itself
	 * @return object Instance of itself or null on invalid id
	 */
	public static function get_instance( $id ) {

		if ( ! key_exists( $id, self::$ids ) )
			return null;

		self::$id = $id;
		self::read();

		return new self();

	}

	/**
	 * Read the plugin headers
	 * @return boolean True on suuccess, false on error
	 */
	public static function read() {

		$id       = self::$id;
		$filename = self::$ids[ $id ];

		if ( ! function_exists( 'get_plugin_data' ) )
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

		$headers = get_plugin_data( $filename );

		if ( ! empty( $headers ) && is_array( $headers ) ) {

			if ( ! is_object( self::$data ) )
				self::$data = new stdClass();

			self::$data->$id = new stdClass();

			self::$data->$id = (object) $headers;
			self::$data->$id->headers_was_set = true;

		}

		unset( $headers );

		return true;

	}

}

}