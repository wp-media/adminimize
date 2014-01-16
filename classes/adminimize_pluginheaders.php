<?php
/**
 * Wrapper class for PluginHeaderReader
 *
 * This class is a helper class to avoid to specify the id each time when the PluginHeaderReader is called
 *
 * PHP version 5.2
 *
 * @category   PHP
 * @package    WordPress
 * @subpackage Inpsyde\Adminimize
 * @author     Ralf Albert <me@neun12.de>
 * @license    GPLv3 http://www.gnu.org/licenses/gpl-3.0.txt
 * @version    1.0
 * @link       http://wordpress.com
 */

if ( ! class_exists( 'Adminimize_PluginHeaders' ) ) {

class Adminimize_PluginHeaders extends PluginHeaderReader
{
	/**
	 * The used ID
	 * @var string
	 */
	const ID = 'adminimize_pluginheaders';

	/**
	 * Simply calls the parent constructor with a fixed id. If the param $file is set, the parent class will be
	 * initialized. The positions of params $file and $id are switched, so we can initialize the class just
	 * by specify the base-file leavoing the id empty/unset.
	 *
	 * @param string $file	Absolute path to file with plugin headers
	 * @param string $id		Not used, required by interface
	 */
	public function __construct( $file = '', $id = '' ) {

		parent::__construct( self::ID, $file );

	}

}

}