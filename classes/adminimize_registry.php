<?php
/**
 * Adminimizes Registry
 * Manage the registration of hooks and filters
 *
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

if ( ! class_exists( 'Adminimize_Registry' ) ) {

class Adminimize_Registry extends ExtendedStandardClass
{
	/**
	 * ID to identify the data in storage
	 * @var string
	 */
	const ID = 'adminimize_registry';

	const STORAGE = 'Adminimize_Storage';

	const PLUGIN_HEADERS = 'Adminimize_PluginHeaders';

	const COMMON_FUNCTIONS = 'Adminimize_Common';

	/**
	 * Class containing the widget handling
	 * @var string
	 */
	const WIDGET_CLASS = 'Adminimize_Widgets';

	/**
	 * Templater class
	 * @var string
	 */
	const TEMPLATER = 'Adminimize_Templater';

	/**
	 * Directory where to find the widgets.
	 * Path is relative to the directory of the widget class (see above)
	 * @var string
	 */
	const WIDGET_DIR = '/components/';

	/**
	 * Array for hooks and filters
	 * @var array
	 */
	public static $hooks = array();

	public function __construct() {

		$this->set_id( self::ID );

	}

	/**
	 * Create an instance of the requested class if not already done and stores this instance
	 * @param		object		$class		Instance of the requested class
	 */
	protected function get_class_instance( $class ) {

		if ( isset( $this->$class) && ! empty( $this->$class) )
			return $this->$class;

		$this->$class = new $class();

		return $this->$class;

	}

	/**
	 * Create an instance of the widget provider
	 * @return	object	::$widget_provider	Instance of the widget provider class
	 */
	public function get_widget_provider() {
		return $this->get_class_instance( self::WIDGET_CLASS );
	}

	public function get_templater() {
		return $this->get_class_instance( self::TEMPLATER );
	}

	public function get_storage() {
		return $this->get_class_instance( self::STORAGE );
	}

	public function get_pluginheaders() {
		return $this->get_class_instance( self::PLUGIN_HEADERS );
	}

	public function get_common_functions() {
		return $this->get_class_instance( self::COMMON_FUNCTIONS );
	}

	/**
	 * Add the hooks needed by the widgets to work
	 */
	public function add_hooks() {

		$hooks = $this->get_widgets_actions( $this->get_widget_provider() );

		foreach ( $hooks as $action_filter ) {

			foreach( $action_filter as $actions ) {

				foreach( $actions as $action ) {

					@list( $tag, $callback, $priority, $accepted_args ) = $action;

					if ( empty( $priority ) )
						$priority = 10;

					if ( empty( $accepted_args ) )
						$accepted_args = 0;

					add_filter( $tag, $callback, $priority, $accepted_args );
					$this->register_action( $action );

				}

			}

		}

	}

	protected function register_action( $action ) {

		@list( $tag, $callback, $priority ) = $action;

		if ( empty( $priority ) )
			$priority = 10;

		$is_callable = is_callable( $callback, true, $method );

		if ( true == $is_callable ) {
			self::$hooks[ $tag ][] = $callback;
		}

	}

	public function remove_hook( $tag ) {

		if ( key_exists( $tag, self::$hooks ) )
			remove_filter( $tag, self::$hooks[ $tag ] );
	}

	/**
	 * Get the actions and filters from the widgets
	 * @param I_Adminimize_Widgets_Provider $widget_provider
	 * @return array
	 */
	protected function get_widgets_actions( I_Adminimize_Widgets_Provider $widget_provider ) {
		return $widget_provider->get_widgets_actions();
	}

}

}