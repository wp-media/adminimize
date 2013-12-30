<?php
/**
 * Interface for PluginHeader_Reader
 * @author Ralf Albert
 *
 */
interface I_PluginHeader_Reader
{
	/**
	 * Array for data from plugin header
	 * @var array
	 */
// 	public static $data;

	/**
	 * Reads the plugin header from given filename
	 * @param string $filename File with plugin header
	 * @return boolean False if the file does not exists
	 */
	public static function init( $filename );

	/**
	 * Returns an instance of itself
	 * @return object Instance of itself
	 */
	public static function get_instance();

	/**
	 * Magic get; returns the value if it is set
	 * @param string $value Value to be retrieved
	 * @return string $value The value if set or empty string
	 */
	public function __get( $value );

}