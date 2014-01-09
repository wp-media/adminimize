<?php
	/**
	 * Adminimize_Widgets
	* @author Ralf Albert
	*
	* Class providing the widgets for My_Widget_SAPI_MenuPage
	*
	*/
class Adminimize_New_Widgets implements I_Adminimize_Widgets_Provider
{

	/**
	 * Screen object
	 * @var object
	 */
	public $screen = null;

	/**
	 * Availbale columns
	 * @var array
	 */
	public $columns = array();

	/**
	 * Array for default widget attributes
	 * @var array
	 */
	public $default_widget_attr = array();

	/**
	 * Array with attributes of each available widget
	 * @var array
	*/
	public $widgets = array();

	/**
	 * Array with option names used by the widgets
	 * @var array
	*/
	public $used_options = array();

	/**
	 * Initialize the common function class and the templater class
	 * @param boolean $recursive Set to true when call from inside the class ( new self( true ); ) to prevent recursion
	*/
	public function __construct() {}

	/**
	 * Returns an array with the used option names
	 * @return array
	 */
	public function get_used_options() {

		if ( ! empty( $this->used_options ) )
			return $this->used_options;

		$this->used_options = array();

		// get widgets if not already done
		if ( empty( $this->widgets ) )
			$this->get_widgets();

		foreach ( $this->widgets as $attr ) {
			if ( isset( $attr['option_name'] ) )
				array_push( $this->used_options, $attr['option_name'] );
		}

		$this->used_options = array_unique( $this->used_options );

		return $this->used_options;

	}

	/**
	 * Get the attributes of each available widget
	 * @return array
	 */
	public function get_widgets() {

		if ( empty( $this->columns ) || empty( $this->default_widget_attr ) )
			return array();

//FIXME Ugly thing... we need a PSR autoloader to fix this

		// make things easy, require the base widget class here
		if ( ! class_exists( 'Adminimize_Base_Widget' ) )
			require_once 'adminimize_base_widget.php';

		$dir_pattern = sprintf(
				'%s/%s/*_widget.php',
				dirname( __FILE__ ),
				str_replace( '/', '', Adminimize_Storage::WIDGET_DIR )
		);

		// get the widgets
		$widgets = glob( $dir_pattern );

		foreach ( $widgets as $widget ) {

			require_once $widget;

			$class = str_replace( '.php', '', basename( $widget ) );
			$obj = new $class();

			$attr = $this->sanitize_attrs( $obj->get_attributes(), $obj );

			$this->widgets[] = array_merge( $this->default_widget_attr, $attr );

		}

		return $this->widgets;

	}

	/**
	 * Sanitizing the attributes for a widget
	 * @param		array		$attr	Array with attributes
	 * @param		object	$obj	Widget class for building the callback
	 * @return	array		$attr	Array with sinitized values
	 */
	public function sanitize_attrs( $attr, $obj ) {

		$col = (int) $attr['context'];
		$attr['context'] = ( isset( $this->columns[ $col ] ) ) ?
			$this->columns[ $col ] : $this->columns[0];

		$attr['callback'] = isset( $attr['callback'] ) ? $attr['callback'] : array( $obj, 'content' );

		$attr['post_type'] = ( isset( $attr['post_type'] ) && ! empty( $attr['post_type'] ) ) ?
			$attr['post_type'] : $this->screen;

		return $attr;

	}

}