<?php 
namespace Adminimize\Part;

require_once 'class-adminimize-part-base-meta-box.php';

/**
 * Options to hide menu entries.
 */
class Menu_Options extends \Adminimize\Part\Base_Meta_Box {

	/**
	 * Get translated meta box title.
	 * 
	 * @return string
	 */
	public function get_meta_box_title() {
		return __( 'Deactivate Menu Options for Roles', 'adminimize' );
	}

	/**
	 * Get option namespace.
	 *
	 * Will be used to serialize settings.
	 * 
	 * @return string
	 */
	public function get_option_namespace() {
		return 'adminimize_menu';
	}

	/**
	 * Populate $settings var with data.
	 * 
	 * @return void
	 */
	protected function init_settings() {
		global $menu, $submenu;

		$settings = array();

		foreach ( $menu as $menu_entry ) {

			if ( false !== stripos( $menu_entry[2], 'separator' ) )
				continue;

			$title = $menu_entry[0];
			$file  = $menu_entry[2];

			$settings[ strtolower( $title ) ] = array(
				'title'       => $title,
				'description' => $file
			);

			if ( isset( $submenu[ $file ] ) ) {
				foreach ( $submenu[ $file ] as $submenu_entry ) {
					$sub_title = $submenu_entry[0];
					$sub_file  = $submenu_entry[2];

					$settings[ strtolower( $sub_title ) ] = array(
						'title'       => ' &mdash; ' . $sub_title,
						'description' => $sub_file
					);
				}
			}
		}

		$this->settings = $settings;
	}

	/**
	 * Print meta box contents.
	 * 
	 * @return void
	 */
	public function meta_box_content() {

		$args = array(
			'option_namespace' => $this->get_option_namespace(),
			'settings'         => $this->get_settings(),
			'custom_options'   => false
		);
		adminimize_generate_checkbox_table( $args );
	}

}

Menu_Options::get_instance();




