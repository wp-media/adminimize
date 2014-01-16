<?php
/**
 * Concrete implementation of the abstract class MenuPage_Widgets_SAPI
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

if ( ! class_exists( 'Adminimize_Options_Page' ) ) {

class Adminimize_Options_Page extends MenuPage_Widgets_SAPI
{

	/**
	 * Menu Slug
	 * @var string
	 */
	const MENU_SLUG  = 'adminimizeoop';

	/**
	 * The default options
	 * @var array
	 */
	public static $default_options = array();

	public $storage = null;

	public $plugindata = null;

	/**
	 * Constructor
	 * Setup the rest of the needed vars
	 */
	public function __construct() {

		$registry = new Adminimize_Registry();

		$this->storage    = $registry->get_storage();
		$this->plugindata = $registry->get_pluginheaders();
		$this->templater  = $registry->get_templater();

		$this->get_available_widgets( $registry->get_widget_provider() );

		$this->option_group = Adminimize_Storage::OPTION_KEY;
		$this->option_name  = Adminimize_Storage::OPTION_KEY;

		$this->menu_slug  = self::MENU_SLUG;
		$this->menu_title = $this->page_title = __( 'Adminimize OOP', $this->plugindata->TextDomain );

		$this->page_callback     = array( $this, 'main_frame' );
		$this->validate_callback = array( $this, 'validate_callback' );

		$this->layout_columns = array( 'max' => 3, 'default' => 1 );
		$this->columns        = $this->get_columns();

		parent::__construct();

// TODO Remove die(var_dump()) after testing
		if ( ! empty( $this->errors ) )
			die( var_dump( $this->errors ) );

	}

	/**
	 * Set $screen to the current screen
	 * @return object	$current_screen	Screen object of the current screen
	 */
	public function get_screen() {

		global $current_screen;

		return ( isset( $current_screen ) ) ?
			$current_screen : null;

	}

	public function get_columns() {
		return array( 'column1', 'column2', 'column3' );
	}

	/**
	 * Enqueues the needed scripts
	 */
	public function enqueue_scripts() {

		wp_register_script(
			'adminimize-backend',
			plugins_url( '/js/adminimize-backend.js', $this->storage->basejs ),
			array( 'jquery', 'postbox'  ),
			false,
			true
		);

		// enque the registered style(s)
		wp_enqueue_style( 'adminimize-style' );

	}

	/**
	 * Get the available widgets
	 */
	protected function get_available_widgets( I_Adminimize_Widgets_Provider $widget_provider ) {

		if ( empty( $this->screen ) )
			$this->get_screen();

		$widget_provider->option_name = $this->option_name;
		$widget_provider->screen      = $this->screen;
		$widget_provider->columns     = $this->get_columns();

		$widget_provider->default_widget_attr = $this->get_default_widget_attributes();

		$this->widgets = $widget_provider->get_widgets_attributes();

		return true;

	}

	/**
	 * Implementation to validate the options
	 * @see MenuPage_Widgets_SAPI::validate_callback()
	 */
	public function validate_callback( $input ){

		$validate = new Adminimize_Validate_Options();

		return $validate->validate( $input );

	}

	/**
	 * Display the page
	 * @see MenuPage_Widgets::main_frame()
	 */
	public function main_frame() {

		wp_enqueue_script( 'adminimize-backend' );

		echo '<div class="adminimize_wrap">';

		printf( '<h1>%s</h1>', esc_html( __( 'Adminimize', $this->plugindata->TextDomain ) ) );

		$this->display_widgets();

		echo '</div>'; //  end class wrap

	}

	/**
	 * Default callback for the widget content if no callback was set
	 */
	public function default_widget() {

		printf( '<p>%s</p>', esc_html( __( 'There was no content or callback defined for this widget.', $this->plugindata->TextDomain ) ) );

	}

}

}