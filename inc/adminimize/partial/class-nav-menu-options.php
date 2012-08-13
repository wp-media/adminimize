<?php 
namespace Inpsyde\Adminimize\Partial;
use Inpsyde\Adminimize;

/**
 * Options to hide menu entries.
 */
class Nav_Menu_Options extends Base {

	/**
	 * Get translated meta box title.
	 * 
	 * @return string
	 */
	public function get_meta_box_title() {
		return __( 'Deactivate Nav Menu Options for Roles', 'adminimize' );
	}

	/**
	 * Get option namespace.
	 *
	 * Will be used to serialize settings.
	 * 
	 * @return string
	 */
	public function get_option_namespace() {
		return 'adminimize_nav_menu';
	}

	/**
	 * Populate $settings var with data.
	 * 
	 * @return void
	 */
	protected function init_settings() {

		$this->settings = array(
			'help' => array(
				'title'    => __( 'Help', 'adminimize' ),
				'description' => '#contextual-help-link-wrap'
			),
			'screen_options' => array(
				'title'    => __( 'Screen Options', 'adminimize' ),
				'description' => '#screen-options-link-wrap'
			),
			'theme_locations' => array(
				'title'    => __( 'Theme Locations', 'adminimize' ),
				'description' => '#nav-menu-theme-locations'
			),
			'custom_links' => array(
				'title'    => __( 'Custom Links', 'adminimize' ),
				'description' => '#add-custom-links'
			),
			'add_menu' => array(
				'title'    => __( '#(Add menu)', 'adminimize' ),
				'description' => '.menu-add-new'
			),
			'categories' => array(
				'title'    => __( 'Categories', 'adminimize' ),
				'description' => '#add-category'
			),
			'tags' => array(
				'title'    => __( 'Tags', 'adminimize' ),
				'description' => '#add-post_tag'
			),
			'format' => array(
				'title'    => __( 'Format', 'adminimize' ),
				'description' => '#add-post_format'
			),
			'posts' => array(
				'title'    => __( 'Posts', 'adminimize' ),
				'description' => '#add-post'
			),
			'pages' => array(
				'title'    => __( 'Pages', 'adminimize' ),
				'description' => '#add-page'
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
		Adminimize\generate_checkbox_table( $args );
	}

}
