<?php
namespace Inpsyde\Adminimize;

class Adminimize {

	/**
	 * Instance holder
	 *
	 * @since	0.1
	 * @access	private
	 * @static
	 * @var		NULL | Adminimize
	 */
	private static $instance = NULL;

	/**
	 * Method for ensuring that only one instance of this object is used
	 *
	 * @since	0.1
	 * @access	public
	 * @static
	 * @return	Adminimize
	 */
	public static function get_instance() {
			
		if ( ! self::$instance )
			self::$instance = new self;
			
		return self::$instance;
	}

	/**
	 * Setting up some data, initialize localization and load
	 * the features
	 *
	 * @since	0.1
	 * @access	public
	 * @return	void
	 */
	public function __construct() {

		// Load the features
		$this->load_features();
		
		// javascript
		add_action( 'admin_print_scripts-settings_page_adminimize-2/adminimize' , array( $this, 'register_admin_scripts' ) );

		$this->kickoff();
	}

	/**
	 * Init all partials.
	 * 
	 * @return void
	 */
	public function kickoff() {

		$partials = array(
			'About',
			'Backend_Options',
			'Dashboard_Options',
			'Global_Options',
			'Links_Options',
			'Menu_Options',
			'Nav_Menu_Options',
			'Write_Page_Options',
			'Write_Post_Options'
		);
		$partials = apply_filters( 'adminimize_active_partials', $partials );

		foreach ( $partials as $partial_class ) {
			call_user_func( "\\Inpsyde\\Adminimize\\Partial\\$partial_class::get_instance" );
		}
	}

	function register_admin_scripts() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.dev' : '';
		
		wp_register_script(
			'adminimize_admin',
			plugins_url( "/js/admin$suffix.js", __FILE__ ),
			array( 'jquery' ),
			'1.0'
		);

		wp_enqueue_script( 'adminimize_admin' );
	}

	/**
	 * Get a value of the plugin header
	 *
	 * @todo  maybe more flexible regarding location
	 * 
	 * @param	string $value
	 * @return	string The plugin header value
	 */
	public function get_plugin_header( $value = 'TextDomain' ) {
		
		static $plugin_data; // only load file once

		if ( ! function_exists( 'get_plugin_data' ) )
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

		$plugin_data = get_plugin_data( $this->get_plugin_file() );

		return $plugin_data[ $value ];
	}

	/**
	 * Get the Textdomain Path where the language files are located
	 *
	 * @since	0.1
	 * @access	public
	 * @return	string The plugins textdomain path
	 */
	public function get_domain_path() {
			
		return $this->get_plugin_header( 'DomainPath' );
	}

	public function get_plugin_file() {

		static $file;
		$file = dirname( dirname( dirname( __FILE__ ) ) ) . '/adminimize.php';

		return $file;
	}

	public function get_plugin_basename() {

		static $basename;
		$basename = plugin_basename( $this->get_plugin_file() );

		return $basename;
	}

	public function is_active_for_multisite() {
		
		static $is_active;
		$is_active = is_multisite() && is_plugin_active_for_network( $this->get_plugin_basename() );

		return $is_active;
	}

	/**
	 * Autoloads all files in $dir subdirectory
	 * 
	 * @param  string $dir name of plugin subdirectory
	 * @return void
	 */
	private function autoload_subdirectory( $dir ) {
		
		// load all files with the pattern *.php from the $dir directory
		foreach( glob( dirname( __FILE__ ) . '/' . $dir . '/*.php' ) as $file )
			require_once $file;
	}

	/**
	 * Autoloads all files in "/features" subdirectory
	 *
	 * @since	0.1
	 * @access	protected
	 * @return	void
	 */
	protected function load_features() {
		
		$this->autoload_subdirectory( 'inc/features' );
	}
}
