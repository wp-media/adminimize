<?php
/**
 * Adminimizes Autoloader
 * PHP version 5.2
 *
 * @category PHP
 * @package WordPress
 * @subpackage Inpsyde\Adminimize
 * @author Ralf Albert <me@neun12.de>
 * @license GPLv3 http://www.gnu.org/licenses/gpl-3.0.txt
 * @version 1.0
 * @link http://wordpress.com
 */
if ( ! class_exists( 'Adminimize_Autoload' ) ) {

	class Adminimize_Autoload
	{

		/**
		 * List with allowed class names
		 *
		 * @var array
		 */
		protected static $whitelist_classes = array ();

		/**
		 * Registering the autoloader and create the classes whitelist
		 *
		 * @param arary|string $dirpath to classes and interfaces
		 */
		public function __construct( $dirpath ) {

			spl_autoload_register( array (
					__CLASS__,
					'load'
			) );

			$this->get_classlist( $dirpath );

		}

		/**
		 * Simple loading function
		 *
		 * @param string $class Classname to load
		 */
		public static function load( $class ) {

			$class = strtolower( $class );

			if ( key_exists( $class, self::$whitelist_classes ) )
				require_once self::$whitelist_classes[$class];

		}

		/**
		 * Creates the classes/interfaces whitelist
		 *
		 * @param array|string $dirpath to classes/interfaces
		 */
		public function get_classlist( $dirpath ) {

			if ( ! is_array( $dirpath ) )
				$dirpath = ( array ) $dirpath;

			foreach ( $dirpath as $dir ) {

				if ( is_file( $dir ) )
					$dirpath = dirname( $dir );

				$files = $this->glob_recursive( $dir . '/*.php' );

				foreach ( $files as $file ) {

					$key = strtolower( str_replace( '.php', '', basename( $file ) ) );
					/*
					 * workaround because the filename of an interface starts with 'interface_', but the classname starts with 'I_'
					 */
					$key = str_replace( 'interface', 'i', $key );

					self::$whitelist_classes[$key] = $file;
				}

			}

		}

		/**
		 * Reads a directory recursiv using glob()
		 * @param	string	$pattern		Pattern to match
		 * @param	number	$flags			Flags for glob()
		 * @return arary	$files			Array with files
		 */
		protected function glob_recursive( $pattern, $flags = 0 ) {

			$files = glob( $pattern, $flags );

			foreach ( glob( dirname( $pattern ) . '/*', GLOB_ONLYDIR | GLOB_NOSORT ) as $dir ) {

				$files = array_merge( $files, $this->glob_recursive( $dir . '/' . basename( $pattern ), $flags ) );

			}

			return $files;

		}

	}

}