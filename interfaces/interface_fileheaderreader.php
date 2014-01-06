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
	 * Magic get; returns the value if it is set
	 * @param string $value Value to be retrieved
	 * @return string $value The value if set or empty string
	 */
	public function __get( $value );

	/**
	 * Sets a value
	 * @param string $name Name of the value
	 * @param mixed $value Value to be set
	 */
	public function __set( $name, $value );

	/**
	 * Implements the isset() functionality for class propperties
	 * @param string $name
	 */
	public function __isset( $name );

}