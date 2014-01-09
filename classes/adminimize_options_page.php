<?php
/**
 * Concrete implementation of the abstract class MenuPage_Widgets_SAPI
 * @author Ralf Albert
 *
 */

require_once 'menupage_widgets_sapi.php';
require_once 'adminimize_storage.php';

class Adminimize_Options_Page extends MenuPage_Widgets_SAPI
{

	/**
	 * Option Group
	 * @var string
	 */
	public $option_group = '';

	/**
	 * Key for options
	 * @var string
	 */
	public $option_name = '';

	/**
	 * Array for widgets
	 * @var array
	 */
	public $widgets = array();

	/**
	 * Name of the class which provide the widgets
	 * @var string
	 */
	public $widget_class = '';

	/**
	 * Callback to display the page (main) content
	 * @var array
	 */
	public $page_callback;

	/**
	 * Menu slug for the page
	 * @var string
	 */
	public $menu_slug = '';

	/**
	 * Menu title for the page
	 * @var string
	 */
	public $menu_title = '';

	/**
	 * Page title for the page
	 * @var string
	 */
	public $page_title = '';

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

		$this->storage    = new Adminimize_Storage();
		$this->plugindata = new PluginHeaderReader( 'adminimize' );
//TODO Maybe change dependency of templater
		$this->templater  = new Adminimize_Templater();

		$widget_class     = Adminimize_Storage::WIDGET_CLASS;

		if ( ! class_exists( $widget_class ) )
			require_once sprintf( '%s/widgets/%s.php', $this->storage->basedir, strtolower( $widget_class ) );

		$this->widget_class = new $widget_class();
		$this->storage->widget_object = $this->widget_class;

		$this->option_group = Adminimize_Storage::OPTION_KEY;
		$this->option_name  = Adminimize_Storage::OPTION_KEY;

		$this->menu_slug  = Adminimize_Storage::MENU_SLUG;
		$this->menu_title = $this->page_title = __( 'Adminimize OOP', $this->plugindata->TextDomain );

		$this->page_callback     = array( $this, 'main_frame' );
		$this->validate_callback = array( $this, 'validate_callback' );

		$this->layout_columns = array( 'max' => 3, 'default' => 1 );
		$this->columns        = array( 'column1', 'column2', 'column3' );

		$this->get_available_widgets( $this->widget_class );

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

	}

	/**
	 * Get the available widgets
	 */
	protected function get_available_widgets( I_Adminimize_Widgets_Provider $widget_class ) {

		if ( empty( $this->screen ) )
			$this->get_screen();

		$widget_class->option_name = $this->option_name;
		$widget_class->screen      = $this->screen;
		$widget_class->columns     = $this->columns;

		$widget_class->default_widget_attr = $this->get_default_widget_attributes();

		$this->widgets = $widget_class->get_widgets();

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