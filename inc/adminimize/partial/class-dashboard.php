<?php 
namespace Inpsyde\Adminimize\Partial;

/**
 * Options to hide menu entries.
 */
class Dashboard_Options extends Base {

	/**
	 * Get translated meta box title.
	 * 
	 * @return string
	 */
	public function get_meta_box_title() {
		return __( 'Deactivate Dashboard Options for Roles', 'adminimize' );
	}

	/**
	 * Get option namespace.
	 *
	 * Will be used to serialize settings.
	 * 
	 * @return string
	 */
	public function get_option_namespace() {
		return 'adminimize_dashboard';
	}

	/**
	 * Populate $settings var with data.
	 * 
	 * @return void
	 */
	protected function init_settings() {
		$this->settings = array(
			'right_now' => array(
				'title'       => __( 'Right Now', 'adminimize' ),
				'description' => 'dashboard_right_now'
			),
			'recent_comments' => array(
				'title'       => __( 'Recent Comments', 'adminimize' ),
				'description' => 'dashboard_recent_comments'
			),
			'incoming_links' => array(
				'title'       => __( 'Incoming Links', 'adminimize' ),
				'description' => 'dashboard_incoming_links'
			),
			'quick_press' => array(
				'title'       => __( 'QuickPress', 'adminimize' ),
				'description' => 'dashboard_quick_press'
			),
			'recent_drafts' => array(
				'title'       => __( 'Recent Drafts', 'adminimize' ),
				'description' => 'dashboard_recent_drafts'
			),
			'primary' => array(
				'title'       => __( 'WordPress Blog', 'adminimize' ),
				'description' => 'dashboard_primary'
			),
			'secondary' => array(
				'title'       => __( 'Other WordPress News', 'adminimize' ),
				'description' => 'dashboard_secondary'
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
		\Adminimize\generate_checkbox_table( $args );
	}

}

Dashboard_Options::get_instance();
