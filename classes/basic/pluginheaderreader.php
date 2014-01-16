<?php
/**
 * Class to read WordPress file headers
 * Reads the plugin header from a given file and stores the data
 *
 * PHP version 5.2
 *
 * @category   PHP
 * @package    WordPress
 * @subpackage RalfAlbert\Tooling
 * @author     Ralf Albert <me@neun12.de>
 * @license    GPLv3 http://www.gnu.org/licenses/gpl-3.0.txt
 * @version    1.0
 * @link       http://wordpress.com
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
	public function __construct( $id = '', $filename = '' ) {

		if ( ! defined( 'ABSPATH' ) )
			trigger_error( 'This class requires WordPress. ABSPATH not found', E_USER_WARNING );

		if ( empty( $id ) || ! is_string( $id ) )
			trigger_error( 'Param (string) id expected', E_USER_WARNING );

		$this->set_id( $id );

		if ( ! is_object( self::$data ) )
			self::$data = new stdClass();

		if ( ! empty( $filename ) && file_exists( $filename ) ) {

			self::$ids[ $id ] = $filename;
			self::$data->$id  = new stdClass();

			$this->read();

		}

		return true;

	}

	/**
	 * Read the plugin headers
	 * @return boolean True on suuccess, false on error
	 */
	public function read() {

		$id       = $this->id;
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