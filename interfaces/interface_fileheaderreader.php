<?php
/**
 * Interface for FileHeaderReader
 * @author Ralf Albert
 *
 */

interface I_FileHeaderReader
{
	/**
	 * Reads the plugin header from given filename
	 * @param string $filename File with plugin header
	 * @return boolean False if the file does not exists
	 */
	public static function init( $filename = '', $id = '' );

	/**
	 * Returns an instance of itself
	 * @return object Instance of itself
	 */
	public static function get_instance( $id );

	/**
	 * Reads the file headers
	 */
	public static function read();
}