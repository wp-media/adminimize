<?php
/**
 * WordPress Class to read and keep the file headers
 *
 * PHP version 5.2
 *
 * @category   PHP
 * @package    WordPress
 * @subpackage FileHeaderReader
 * @author     Ralf Albert <me@neun12.de>
 * @license    GPLv3 http://www.gnu.org/licenses/gpl-3.0.txt
 * @version    1.0
 * @link       http://wordpress.com
 */

/**
 * FileHeaderReader
 * @author Ralf Albert
 * @version 1.0
 *
 * Reads the plugin header from a given file and stores the data
 */
abstract class FileHeaderReader extends ExtendedStandardClass implements I_FileHeaderReader
{
	/**
	 * Flag to show if the pluginheaders was read
	 * @var boolean
	 */
	public static $headers_was_set = false;

}