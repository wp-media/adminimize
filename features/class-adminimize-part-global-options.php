<?php 
namespace Adminimize\Part;

require_once 'class-adminimize-part-base-meta-box.php';

/**
 * Options to hide menu entries.
 */
class Global_Options extends \Adminimize\Part\Base_Meta_Box {

	/**
	 * Get translated meta box title.
	 * 
	 * @return string
	 */
	public function get_meta_box_title() {
		return __( 'Deactivate Global Options for Roles', 'adminimize' );
	}

	/**
	 * Get option namespace.
	 *
	 * Will be used to serialize settings.
	 * 
	 * @return string
	 */
	public function get_option_namespace() {
		return 'adminimize_global';
	}

	/**
	 * Populate $settings var with data.
	 * 
	 * @return void
	 */
	protected function init_settings() {
		$this->settings = array(
			'admin_bar' => array(
				'title'    => __( 'Admin Bar', 'adminimize' ),
				'description' => '.show-admin-bar'
			),
			'fav_actions' => array(
				'title'    => __( 'Favorite Actions', 'adminimize' ),
				'description' => '#favorite-actions'
			),
			'screen_meta' => array(
				'title'    => __( 'Screen-Meta', 'adminimize' ),
				'description' => '#screen-meta'
			),
			'screen_options' => array(
				'title'    => __( 'Screen Options', 'adminimize' ),
				'description' => '#screen-options, #screen-options-link-wrap'
			),
			'context_help' => array(
				'title'    => __( 'Contextual Help', 'adminimize' ),
				'description' => '#contextual-help-link-wrap'
			),
			'admin_color_scheme' => array(
				'title'    => __( 'Admin Color Scheme', 'adminimize' ),
				'description' => '#your-profile .form-table fieldset'
			),
		);
	}

	/**
	 * Print meta box contents.
	 * 
	 * @return void
	 */
	public function meta_box_content() {

		$args = array(
			'option_namespace' => $this->get_option_namespace(),
			'settings'         => $this->get_settings()
		);
		\Adminimize\adminimize_generate_checkbox_table( $args );
	}

}

Global_Options::get_instance();
