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
	const ID = 'adminimize';

	public function __construct( $file = '' ) {

		parent::__construct( self::ID, $file );

	}

}