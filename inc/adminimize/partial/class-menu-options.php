<?php 
namespace Inpsyde\Adminimize\Partial;
use \Inpsyde\Adminimize;

/**
 * Options to hide menu entries.
 */
class Menu_Options extends Checkbox_Base {

	/**
	 * This method is called for every setting that is active.
	 *
	 * Hides main- and submenu entries.
	 * 
	 * @param  string $index  setting index
	 * @param  array  $values setting values
	 * @param  string $role   WordPress role handle
	 * @return void
	 */
	public function apply_checkbox_setting( $index, $values, $role ) {
		
		if ( NULL === $values['submenu_index'] )
			Adminimize\remove_mainmenu_entry( $values['menu_index'] );
		else
			Adminimize\remove_submenu_entry( $values['menu_index'], $values['submenu_index'] );
	}

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

		foreach ( $menu as $menu_index => $menu_entry ) {

			if ( false !== stripos( $menu_entry[2], 'separator' ) )
				continue;

			$title = $menu_entry[0];
			$file  = $menu_entry[2];

			$settings[ strtolower( $title . '_' . $file ) ] = array(
				'title'         => $title,
				'description'   => $file,
				'menu_index'    => $menu_index,
				'submenu_index' => NULL
			);

			if ( isset( $submenu[ $file ] ) ) {
				foreach ( $submenu[ $file ] as $submenu_index => $submenu_entry ) {
					$sub_title = $submenu_entry[0];
					$sub_file  = $submenu_entry[2];

					$settings[ strtolower( $sub_title . '_' . $sub_file ) ] = array(
						'title'         => ' &mdash; ' . $sub_title,
						'description'   => $sub_file,
						'menu_index'    => $menu_index,
						'submenu_index' => $submenu_index
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
		Adminimize\generate_checkbox_table( $args );
	}

}
