<?php
/**
 * Adminimizes Autoloader
 * @author Ralf Albert
 *
 */
class Adminimize_Autoload
{

	/**
	 * List with allowed class names
	 * @var array
	 */
	protected static $whitelist_classes = array();

	/**
	 * Registering the autoloader and create the classes whitelist
	 * @param arary|string $dirpath	Path(es) to classes and interfaces
	 */
	public function __construct( $dirpath ) {

		spl_autoload_register( array( __CLASS__, 'load' ) );

		$this->get_classlist( $dirpath );

	}

	/**
	 * Simple loading function
	 * @param string $class Classname to load
	 */
	public static function load( $class ) {

		$class= strtolower( $class );

		if ( key_exists( $class, self::$whitelist_classes ) )
			require_once self::$whitelist_classes[ $class ];

	}

	/**
	 * Creates the classes/interfaces whitelist
	 * @param array|string $dirpath	Path to classes/interfaces
	 */
	public function get_classlist( $dirpath ) {

		if ( ! is_array( $dirpath ) )
			$dirpath = (array) $dirpath;

		foreach ( $dirpath as $dir ) {

			if ( is_file( $dir ) )
				$dirpath = dirname( $dir );

			$files = glob( $dir . '/*.php' );

			foreach ( $files as $file ) {

				$key = strtolower( str_replace( '.php', '', basename( $file ) ) );
				/*
				 * workaround because the filename of an interface starts with 'interface_',
				 * but the classname starts with 'I_'
				 */
				$key = str_replace( 'interface', 'i', $key );

				self::$whitelist_classes[ $key ] = $file;

			}

		}

	}

}