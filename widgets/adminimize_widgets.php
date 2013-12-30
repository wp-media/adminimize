<?php
/**
 * Adminimize_Widgets
 * @author Ralf Albert
 *
 * Class providing the widgets for My_Widget_SAPI_MenuPage
 *
 */

class Adminimize_Widgets
{
	/**
	 * Container for common functions
	 * @var object
	 */
	public static $common = null;

	/**
	 * Container for templater class
	 * @var object
	 */
	public static $templater = null;

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
	 * Key for options
	 * @var string
	 */
	public $option_name = '';

	/**
	 * Array for default widget attributes
	 * @var array
	 */
	public $default_widget_attr = array();

	/**
	 * Array with attributes of each available widget
	 * @var array
	 */
	public static $widgets_attr = array();

	/**
	 * Array with option names used by the widgets
	 * @var array
	 */
	public static $used_options = array();

	/**
	 * Initialize the common function class and the templater class
	 * @param boolean $recursive Set to true when call from inside the class ( new self( true ); ) to prevent recursion
	 */
	public function __construct( $recursive = false) {

		if ( false == $recursive ) {

			if ( empty( self::$common ) )
				self::$common = new Adminimize_Common();

			if ( empty( self::$templater ) )
				self::$templater = new Adminimize_Templater();

			// setup the $widget_attr array
			if ( empty( self::$widgets_attr ) )
				$this->get_widgets_attributes();

		}

	}

	/**
	 * Returns an array with the used option names
	 * @return array
	 */
	public static function get_used_options() {

		$used_options = array();

		// create $widgets_attr if not exists
		if ( empty( self::$widgets_attr ) )
			new self( true );

		foreach ( self::$widgets_attr as $attr ) {
				if ( isset( $attr['option_name'] ) )
					array_push( $used_options, $attr['option_name'] );
		}

		return array_unique( $used_options );

	}

	/**
	 * Get the attributes of each available widget
	 * @return array
	 */
	public function get_widgets_attributes() {

		// set dummy options if none was set before
		if ( empty( $this->screen ) )
			$this->screen = '';

		if ( empty( $this->columns ) )
			$this->columns = array_fill( 0, 90, 'foo' );

		// get the widgets
		$widgets     = get_class_methods( __CLASS__ );
		$me          = new self( true );
		$me->screen  = $this->screen;
		$me->columns = $this->columns;

		foreach ( $widgets as $widget ) {

			if ( true == preg_match( '#_widget$#is' ,$widget ) ) {

				$attrs = $me->{$widget}( true );

				if ( ! empty( $attrs ) ) {

					$attrs = array_merge( $this->default_widget_attr, $attrs );
					self::$widgets_attr[] = $attrs;

				}

			}

		}

		return self::$widgets_attr;

	}

	/**
	 * Test Options Widget
	 * @param boolean $get If the widget callback should return the widget fields or not
	 * @return array	Returns an array with the widget propperties or output the widget content
	 */
	public function test_widget( $get = false ) {

		$attr = array(
					'id'            => __FUNCTION__, // __FUNCTION__ will retunr ONLY the method-name (w/o the class-name)
					'title'         => __( 'Test Options Widget for Developers', ADMINIMIZE_TEXTDOMAIN ),
					'callback'      => array( $this, __FUNCTION__ ),
					'post_type'     => $this->screen,
					'context'       => $this->columns[0],
					'priority'      => 'default',
					'callback_args' => array(),
					'option_name'   => 'test_option'
		);

		if ( true == $get )
			return $attr;

		/*
		 * starting the widget content
		 */
		$option = $attr['option_name'];

		$test_option = self::$common->get_option( $option );

		$v1 = new stdClass();
		$v1->text     = __( 'A test option: ', ADMINIMIZE_TEXTDOMAIN );
		$v1->name_arg = self::$templater->get_name_arg( $option );
		$v1->value    = ( ! empty( $test_option ) ) ? $test_option : 'nothing';
		$v1_pat       = '{text} <input type="text" size="10" value="{value}" {name_arg} />';

		$v2 = new stdClass();
		$v2->label = self::$templater->get_label( $option );
		$v2->input = self::$templater->sprintf( $v1_pat, $v1 );
		$v2_pat    = '{label}{input}</label>';

		$out  = self::$templater->sprintf( $v2_pat, $v2 );
		$out .= self::$templater->get_submitbutton();

// 		print( $out );

		var_dump( self::$common->get_option() );

	}

	/**
	 * Dashboard Options Widget
	 * @param boolean $get If the widget callback should return the widget fields or not
	 * @return array	Returns an array with the widget propperties or output the widget content
	 */
	public function dashboard_widget( $get = false ) {

		$attr = array(
					'id'            => __FUNCTION__,
					'title'         => __( 'Dashboard Options', ADMINIMIZE_TEXTDOMAIN ),
					'callback'      => array( $this, __FUNCTION__ ),
					'post_type'     => $this->screen,
					'context'       => $this->columns[0],
					'priority'      => 'default',
					'callback_args' => array(),
					'option_name'   => 'dashboard_widgets'
		);

		if ( true == $get )
			return $attr;

		/*
		 * starting the widget content
		 */
// 		require_once 'inside_widget/dashboard_options.php';

		$option = $attr['option_name'];

		// get widgets
		$widgets = self::$common->get_option( $option );

		// no widgets?
		if ( empty( $widgets ) ) {

			echo '<p class="form-invalid">';
			_e( 'To complete the installation for Dashboard Widgets you must visit your dashboard once and then come back to Settings > Adminimize to configure who has access to each widget.', ADMINIMIZE_TEXTDOMAIN );
			echo '</p>';
			return;

		} else {

			// create table
			echo self::$templater->get_table( $option, $widgets, 'dashboard' );

		}

	}

	/**
	 * Global Options Widget
	 * @param boolean $get If the widget callback should return the widget fields or not
	 * @return array	Returns an array with the widget propperties or output the widget content
	 */
	public function global_widget( $get = false ) {

		$attr = array(
					'id'            => __FUNCTION__,
					'title'         => __( 'Global Options', ADMINIMIZE_TEXTDOMAIN ),
					'callback'      => array( $this, __FUNCTION__ ),
					'post_type'     => $this->screen,
					'context'       => $this->columns[0],
					'priority'      => 'default',
					'callback_args' => array(),
					'option_name'   => 'global_option'
		);

		if ( true == $get )
			return $attr;

		/*
		 * starting the widget content
		 */

		$option = $attr['option_name'];

		$global_options = array(
				array( 'id' => '.show-admin-bar', 'title' => __('Admin Bar', ADMINIMIZE_TEXTDOMAIN ) ),
				array( 'id' => '#favorite-actions', 'title' => __('Favorite Actions', ADMINIMIZE_TEXTDOMAIN ) ),
				array( 'id' => '#screen-meta', 'title' => __('Screen-Meta', ADMINIMIZE_TEXTDOMAIN ) ),
				array( 'id' => '#screen-options, #screen-options-link-wrap', 'title' => __('Screen Options', ADMINIMIZE_TEXTDOMAIN ) ),
				array( 'id' => '#contextual-help-link-wrap', 'title' => __('Screen Options', ADMINIMIZE_TEXTDOMAIN ) ),
				array( 'id' => '#your-profile .form-table fieldset', 'title' => __('Contextual Help', ADMINIMIZE_TEXTDOMAIN ) ),
				array( 'id' => '#admin_color_scheme', 'title' => __('Admin Color Scheme', ADMINIMIZE_TEXTDOMAIN ) ),
		);

		echo self::$templater->get_table( $option, $global_options, 'global' );

	}

	/**
	 * Adminbar Options Widget (beta)
	 * @param boolean $get If the widget callback should return the widget fields or not
	 * @return array	Returns an array with the widget propperties or output the widget content
	 */
	public function adminbar_widget( $get = false ) {

		if ( true == $get ) {
			return array(
					'id'            => __FUNCTION__, // __FUNCTION__ will retunr ONLY the method-name (w/o the class-name)
					'title'         => __( 'Adminbar Options &middot; Beta', ADMINIMIZE_TEXTDOMAIN ),
					'callback'      => array( $this, __FUNCTION__ ),
					'post_type'     => $this->screen,
					'context'       => $this->columns[0],
					'priority'      => 'default',
					'callback_args' => array()
			);
		}

		/*
		 * starting the widget content
		 */
		global $wp_admin_bar;

		if ( ! isset( $wp_admin_bar ) )
			$wp_admin_bar = '';

		$admin_bar_items = self::$common->get_admin_bar_items();
		$option = 'admin_bar';

		echo self::$templater->get_table( $option, $admin_bar_items, 'admin_bar' );

	}

	/**
	 * Backend Options Widget
	 * @param boolean $get If the widget callback should return the widget fields or not
	 * @return array	Returns an array with the widget propperties or output the widget content
	 */
// 	public function backend_widget( $get = false ) {

// 		if ( true == $get ) {
// 			return array(
// 					'id'            => __FUNCTION__,
// 					'title'         => __( 'Backend Options', ADMINIMIZE_TEXTDOMAIN ),
// 					'callback'      => array( $this, __FUNCTION__ ),
// 					'post_type'     => $this->screen,
// 					'context'       => $this->columns[0],
// 					'priority'      => 'default',
// 					'callback_args' => array()
// 			);
// 		}

// 		/*
// 		 * starting the widget content
// 		 */
// 		require_once 'inside_widget/backend_options.php';
// 	}

	/**
	 * About the plugin
	 */
	public function about_widget( $get = false ) {

		if ( true == $get ) {
			return array(
					'id'            => __FUNCTION__,
					'title'         => __( 'About the plugin', ADMINIMIZE_TEXTDOMAIN ),
					'callback'      => array( $this, __FUNCTION__ ),
					'post_type'     => $this->screen,
					'context'       => $this->columns[1],
					'priority'      => 'default',
					'callback_args' => array()
			);
		}

		/*
		 * starting the widget content
		 */
		require_once 'inside_widget/about.php';
	}

}