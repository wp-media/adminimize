<?php
/**
 * Class to handle datas
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

if ( ! class_exists( 'ExtendedStandardClass' ) ) {

abstract class ExtendedStandardClass implements IteratorAggregate
{
	/**
	 * Object for data from plugin header
	 * @var Object
	 */
	public static $data = array();

	/**
	 * Instance identifier
	 * @var string
	*/
	public $id = '';

	/**
	 * Switch between display errors and hide errors
	 * @var bool
	 */
	public static $show_errors = false;

	/**
	 * Triggers an error
	 * @param	string	$err		Error type
	 * @param	string	$method	Method where the error occurs
	 */
	public function print_error( $error, $method = '' ) {

		$errors = array(
			'no id'      => array( 'Error in %s: no id was set', E_USER_NOTICE ),
			'invalid id' => array( 'Error in %s: invalid id', E_USER_NOTICE ),
		);

		if ( isset( $errors[ $error ] ) ) {

			if ( true == self::$show_errors )
				trigger_error( sprintf( $errors[ $error ][0], $method ), $errors[ $error ][1] );

		}

		return false;

	}

	/**
	 * Set an id
	 * @param	string	$id	ID
	 */
	public function set_id( $id ) {

		if ( empty( $id ) )
			return $this->print_error( 'no id', __METHOD__ );

		$this->id = $id;

	}

	/**
	 * Returns a value
	 * @param string $name Name of the value
	 * @return mixed The value if it is set, else null
	 */
	public function __get( $name ) {

		if ( empty( $this->id ) )
			$this->print_error( 'no id', __METHOD__ );

		$id = $this->id;

		if ( empty( $id ) )
			return null;

		return ( isset( self::$data->$id->$name ) ) ?
			self::$data->$id->$name : null;

	}

	/**
	 * Set a value
	 * @param string $name Name of the value
	 * @param string $value The value itself
	 */
	public function __set( $name, $value = null ) {

		if ( empty( $this->id ) )
			$this->print_error( 'no id', __METHOD__ );

		$id = $this->id;

		if ( ! is_object( self::$data ) )
			self::$data = new stdClass();

		if ( ! property_exists( self::$data, $id ) || ! is_object( self::$data->$id ) )
			self::$data->$id = new stdClass();

		self::$data->$id->$name = $value;

	}

	/**
	 * Implements the isset() functionality to check if a propperty is set with isset()
	 * @param string $name Name of the propperty to check
	 * @return boolean True if the popperty is set, else false
	 */
	public function __isset( $name ) {

		if ( empty( $this->id ) )
			$this->print_error( 'no id', __METHOD__ );

		$id = $this->id;

		if ( ! is_object( self::$data ) )
			return false;

		if ( ! property_exists( self::$data, $id ) || ! is_object( self::$data->$id ) )
			return false;

		return ( property_exists( self::$data->$id, $name ) ) ?
			true : false;

	}

	/**
	 * Returns the iterator
	 * @return ArrayIterator
	 */
	public function getIterator() {

		if ( empty( $this->id ) )
			$this->print_error( 'no id', __METHOD__ );

		$id = $this->id;

		return new ArrayIterator( self::$data->$id );

	}

}

}