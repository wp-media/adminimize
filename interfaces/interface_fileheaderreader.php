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
	public function __construct( $filename = '', $id = '' );

	/**
	 * Reads the file headers
	 */
	public function read();
}