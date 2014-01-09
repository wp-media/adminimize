<?php
/**
 * MenuPage_Widgets_SAPI
 * @author Ralf Albert
 * @version 1.0
 *
 * Creates a menupage with widgets which uses the Settings API to save options
 */

/**
 * This piece of javascript is neccessary to enable the metaboxes!
 * It will be outputted when the file is called with the GET param 'ver'
 */
if ( isset( $_GET['ver'] ) ) {
	echo "postboxes.add_postbox_toggles( pagenow, {} );";
}

abstract class MenuPage_Widgets_SAPI
{

	/**
	 * Tag used for the automatic generated js-script
	 * @var string
	 */
	public $script_tag = '';

	/**
	 * Error messages
	 * @var array
	 */
	public $errors = array();

	/**
	 * Templater object to render templates
	 * @var object
	 */
	public $templater = null;

	/**
	 * Option Group
	 * @var string
	 */
	public $option_group = '';

	/**
	 * Option Name
	 * @var string
	 */
	public $option_name = '';

	/**
	 * Array for the widgets
	 * @var array
	 */
	public $widgets = array();

	/**
	 * Callback to render the main page content
	 * @var array|string
	 */
	public $page_callback = null;

	/**
	 * Callback to validate the options
	 * @var array|string
	 */
	public $validate_callback = null;

	/**
	 * The slug for the menu page
	 * @var string
	 */
	public $menu_slug  = '';

	/**
	 * The menu title
	 * @var string
	 */
	public $menu_title = 'Empty Menu Title';

	/**
	 * The page title
	 * @var string
	 */
	public $page_title = 'Empty Page Title';

	/**
	 * Capabilities a user must have to see the page
	 * @var unknown
	 */
	public $capabilities = 'manage_options';

	/**
	 * Array for the layout columns
	 * @var array
	 */
	public $layout_columns = array();

	/**
	 * Array for the columns the widgets can be registered for
	 * @var array
	 */
	public $columns = array( 'default' );

	/**
	 * Nonce for saving the widget content
	 * @var string
	 */
	public $nonce = '';

	/**
	 * Pagehook of the menu page
	 * @var string
	 */
	public $pagehook = '';

	/**
	 * Screen object
	 * @var object
	 */
	public $screen = null;

	/**
	 * The constructor add the needed hooks
	 */
	public function __construct() {

		if ( true == $this->check_settings() ) {

			add_action( 'admin_init', array( $this, 'settings_api_init' ), 1, 0 );
			add_action( 'admin_menu', array( $this, 'add_menu_page' ), 10, 0 );

		}

	}

	/**
	 * Returns error messages
	 * @return array
	 */
	public function get_errors() {
		return $this->errors;
	}

	/**
	 * Checks if everything was correctly setup
	 * @return boolean True on success, false on error
	 */
	public function check_settings() {

		$success = true;

		if ( empty( $this->page_callback ) ) {
			$success = false;
			$this->errors['page_callback'] = 'The page callback can not be empty!';
		}

		if ( empty( $this->option_name ) ) {
			$success = false;
			$this->errors['option_name'] = 'The options-name can not be empty!';
		}

		// create a random menu slug if it is not set
		if ( empty( $this->menu_slug ) )
			$this->menu_slug = 'empty-menu-slug' . rand( 0, 999 );

		if ( empty( $this->nonce ) )
			$this->nonce = wp_nonce_field( 'save_widgets', 'save_widgets', true, false );

		$this->layout_columns = wp_parse_args( $this->layout_columns, array( 'max' => 3, 'default' => 1 ) );

		return $success;

	}

	/**
	 * Initialise the WordPress Settings-API
	 * - Register the settings
	 * - Register the sections
	 * - Register the fields for each section
	 */
	public function settings_api_init() {

		// register settings
		register_setting(
			$this->option_group,
			$this->option_name,
			$this->validate_callback
		);

	}

	/**
	 * Add a page to the dashboard-menu
	 */
	public function add_menu_page() {

		if ( ! current_user_can( $this->capabilities ) )
			return false;

		$this->pagehook = add_options_page(
				$this->page_title,
				$this->menu_title,
				$this->capabilities,
				$this->menu_slug,
				$this->page_callback
		);

		add_action( 'load-' . $this->pagehook, array( $this, 'enqueue_scripts') );
		add_action( 'load-' . $this->pagehook, array( $this, 'add_screen_options' ) );

		return $this->pagehook;

	}

	/**
	 * Enqueues the really needed javascript
	 */
	public function enqueue_scripts() {

		$this->script_tag = 'menupage_widgets' . rand( 0, 999 );

		wp_enqueue_script(
			$this->script_tag,
			plugin_dir_url( __FILE__ ) . basename( __FILE__ ),
			array( 'jquery', 'postbox'  ),
			false,
			true
		);

	}

	/**
	 * Adding the screen options
	 */
	public function add_screen_options() {

		$this->screen = get_current_screen();

		// get out of here if we are not on our page
		if ( ! is_object( $this->screen ) || $this->screen->id != $this->pagehook )
			return;

		add_screen_option( 'layout_columns', $this->layout_columns );

		if ( ! empty( $this->widgets ) )
			$this->add_widgets();

	}

	/**
	 * Registering the widgets
	 */
	public function add_widgets() {

		$defaults = $this->get_default_widget_attributes();

		foreach( $this->widgets as $widget ) {

			$widget = array_merge( $defaults, $widget );

			// be sure to use a unique id!
			if ( 'empty' === $widget['id'] )
				$widget['id'] .= rand( 0, 999 );

			add_meta_box(
				$widget['id'],
				$widget['title'],
				$widget['callback'],
				$this->screen,
				$widget['context'],
				$widget['priority'],
				$widget['callback_args']
			);

		}

	}

	/**
	 * Returns the neccessary widget settings
	 * @return array Array with default widget settings
	 */
	protected function get_default_widget_attributes() {

		return array(
				'id'            => 'empty',
				'title'         => 'Empty Title',
				'callback'      => array( $this, 'default_widget' ),
				'post_type'     => $this->screen,
				'context'       => $this->columns[0],
				'priority'      => 'default',
				'callback_args' => array()

		);

	}

	/**
	 * Display the registered widgets
	 * @param object $screen Screen object
	 * @return boolean
	 */
	public function display_widgets() {

		global $pagenow;

		if ( empty( $this->screen ) ) {
			$this->errors['no_screen'] = 'There is no screen-object set for this page!';
			return false;
		}

		if ( empty( $this->widgets ) ) {
			$this->errors['no_widgets'] = 'There are no registered widgets for this page to display!';
			return false;
		}

		$pattern = '
		<form action="{page}" method="POST">
		{sapi_fields}
			<div id="dashboard-widgets-wrap">
				<div id="dashboard-widgets" class="metabox-holder {class}">
					{nonce1}
					{nonce2}
					{metaboxes}
				</div>
				<div class="clear"></div>
			</div>
		</form>';

		$this->v              = new stdClass();
		$this->v->page        = esc_attr( admin_url( 'options.php' ) );
		$this->v->slug        = $this->menu_slug;
		$this->v->class       = 'columns-' . $this->screen->get_columns();
		$this->v->sapi_fields = $this->get_settings_fields();

		$this->v->nonce1      = wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false, false );
		$this->v->nonce2      = wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false, false );

		$this->v->metaboxes   = '';

// 		array_walk(
// 			$this->columns,
// 			function( $context, $key ) use( &$v, $screen ) {
// 				$v->metaboxes .= sprintf(
// 						'<div id="postbox-container-%d" class="postbox-container">%s</div>',
// 						( $key + 1 ), $this->get_widgets_html( $screen->id, $context, '' )
// 				);
// 			}
// 		);

		foreach ( $this->columns as $key => $context )
			$this->postboxes_callback( $context, $key );

		$this->templater->printf( $pattern, $this->v );

	}

	/**
	 * Callback to generate the postbox container
	 * @param string $context Context of the postbox
	 * @param integer $key Index of the container
	 */
	protected function postboxes_callback( $context, $key ) {

		$this->v->metaboxes .= sprintf(
				'<div id="postbox-container-%d" class="postbox-container">%s</div>',
				( $key + 1 ), $this->get_widgets_html( $this->screen, $context, '' )
		);

	}

	/**
	 * Callback to get the widgets HTML
	 * Will be called for each widget
	 * @param unknown $screen
	 * @param unknown $context
	 * @param unknown $object
	 * @return string
	 */
	protected function get_widgets_html( $screen, $context, $object ) {

		ob_start();
		do_meta_boxes( $screen, $context, $object );
		return ob_get_clean();

	}

	/**
	 * Returns the needed Settings API fields
	 * @return string
	 */
	protected function get_settings_fields() {

		ob_start();
		settings_fields( $this->option_group );
		return ob_get_clean();

	}

	/**
	 * Validate saved options
	 *
	 * @param array $input Options send
	 * @return array $input Validated options
	 */
	abstract public function validate_callback( $input );

	/**
	 * Outputs the main content
	 * Must call MenuPage_Widget::display_widgets() somewhere to display the widgets
	 */
	abstract public function main_frame();

	/**
	 * Default callback for the widget content if no callback was set
	 */
	abstract public function default_widget();

}