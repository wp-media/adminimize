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

		$this->option_group = Adminimize_Storage::OPTION_KEY;
		$this->option_name  = Adminimize_Storage::OPTION_KEY;

		$this->menu_slug  = Adminimize_Storage::MENU_SLUG;
		$this->menu_title = $this->page_title = __( 'Adminimize OOP', $this->plugindata->TextDomain );

		$this->page_callback     = array( $this, 'main_frame' );
		$this->validate_callback = array( $this, 'validate_callback' );

		$this->layout_columns = array( 'max' => 3, 'default' => 1 );
		$this->columns        = array( 'column1', 'column2', 'column3' );

		// setup $widgets array
		$this->get_available_widgets();

		parent::__construct();

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
	protected function get_available_widgets() {

		$widget_class = 'Adminimize_Widgets';

		if ( ! class_exists( $widget_class ) || ! is_a( $my_widgets, $widget_class ) ) {
			require_once $this->storage->basedir . '/widgets/adminimize_widgets.php';
			$my_widgets = new $widget_class();
		}

		$my_widgets->option_name = $this->option_name;
		$my_widgets->screen      = $this->screen;
		$my_widgets->columns     = $this->columns;
		$my_widgets->default_widget_attr = $this->get_default_widget_attributes();

		$this->widgets = $my_widgets->get_widgets_attributes();

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

		$this->display_widgets( $this->screen );

		echo '</div>'; //  end class wrap

	}

	/**
	 * Default callback for the widget content if no callback was set
	 */
	public function default_widget() {

		printf( '<p>%s</p>', esc_html( __( 'There was no content or callback defined for this widget.', $this->plugindata->TextDomain ) ) );

	}

}