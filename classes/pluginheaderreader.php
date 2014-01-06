<?php
/**
 * PluginHeaderReader
 * @author Ralf Albert
 * @version 1.0
 *
 * Reads the plugin header from a given file and stores the data
 */
if ( ! class_exists( 'PluginHeaderReader' ) ) {

class PluginHeaderReader extends FileHeaderReader
{

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

		if ( ! function_exists( 'get_plugin_data' ) )
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

		$headers = get_plugin_data( $filename );

		if ( ! empty( $headers ) && is_array( $headers ) ) {

			if ( ! is_object( self::$data ) )
				self::$data = new \stdClass();

			self::$data->$id = new \stdClass();

			self::$data->$id = (object) $headers;
			self::$data->$id->headers_was_set = true;

		}

		unset( $headers );

		return true;

	}

	/**
	 * Returns an instance of itself
	 * @return object Instance of itself
	 */
	public static function get_instance( $id ) {

		if ( empty( $id ) || ! is_string( $id ) )
			trigger_error( 'Error in ' . __METHOD__ . ': parameter (string) id expected', E_USER_NOTICE );

		self::$id = $id;

		return new self();

	}

}

}