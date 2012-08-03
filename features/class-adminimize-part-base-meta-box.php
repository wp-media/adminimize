<?php
namespace Adminimize\Part;

require_once 'class-adminimize-options-page.php';

abstract class Base_Meta_Box {

	/**
	 * Settings options array.
	 * 
	 * @var array|NULL
	 */
	protected $settings = NULL;

	/**
	 * All Parts are singletons.
	 * 
	 * @return Base_Meta_Box
	 */
	static public function get_instance() {
		static $instances = array();

		 $calledClass = get_called_class();

		 if ( ! isset( $instances[ $calledClass ] ) )
		     $instances[ $calledClass ] = new $calledClass();

		 return $instances[ $calledClass ];
	}

	protected function __construct(){
		// register setting
		add_action( 'adminimize_register_settings', array( $this, 'register_setting' ) );

		// register meta box
		add_action( 'admin_menu',         array( $this, 'register_meta_box' ), 20 );
		add_action( 'network_admin_menu', array( $this, 'register_meta_box' ), 20 );
	}

	final private function __clone(){}

	/**
	 * Register Metabox.
	 * 
	 * @return void
	 */
	public final function register_meta_box() {

		// extract class name withour namespace
		$full_class_name = get_class( $this );
		$class_parts     = explode( '\\', $full_class_name );
		$class_name      = array_pop( $class_parts );

		\add_meta_box(
			/* $id,           */ 'adminimize_add_meta_box_' . strtolower( $class_name ),
			/* $title,        */ $this->get_meta_box_title(),
			/* $callback,     */ array( $this, 'meta_box_content' ),
			/* $post_type,    */ \Adminimize_Options_Page::$pagehook,
			/* $context,      */ 'normal'
			/* $priority,     */
			/* $callback_args */
		);
	}

	public final function register_setting() {
		\register_setting( \Adminimize_Options_Page::$pagehook, $this->get_option_namespace() );
	}

	/**
	 * Return settings array.
	 * 
	 * @return array
	 */
	public function get_settings() {

		if ( NULL === $this->settings )
			$this->init_settings();

		return $this->settings;
	}

	/**
	 * Get translated meta box title.
	 * 
	 * @return string
	 */
	public abstract function get_meta_box_title();

	/**
	 * Get option namespace.
	 *
	 * Will be used to serialize settings.
	 * 
	 * @return string
	 */
	public abstract function get_option_namespace();

	/**
	 * Print meta box contents.
	 * 
	 * @return void
	 */
	public abstract function meta_box_content();

	/**
	 * Populate $settings var with data.
	 * 
	 * @return void
	 */
	protected abstract function init_settings();

}
