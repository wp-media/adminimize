<?php
/**
 * Wrapper class for PluginHeaderReader
 *
 * This class is a helper class to avoid to specify the id each time when the PluginHeaderReader is called
 * @author Ralf Albert
 *
 */

class Adminimize_PluginHeaders extends PluginHeaderReader
{
	/**
	 * The used ID
	 * @var string
	 */
	const ID = 'adminimize';

	/**
	 * Simply calls the parent constructor with a fixed id. If the param $file is set, the parent class will be
	 * initialized.
	 *
	 * @param string $file Absolute path to file with plugin headers
	 */
	public function __construct( $file = '' ) {

		parent::__construct( self::ID, $file );

	}

}