<?php
/**
 * Interface for FileHeaderReader
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

if ( ! class_exists( 'I_FileHeaderReader' ) ) {

interface I_FileHeaderReader
{
	/**
	 * Reads the plugin header from given filename
	 * @param string $filename File with plugin header
	 * @return boolean False if the file does not exists
	 */
	public function __construct( $id = '', $filename = ''  );

	/**
	 * Reads the file headers
	 */
	public function read();
}

}