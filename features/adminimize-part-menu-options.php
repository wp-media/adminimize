<?php 

/**
 * Table wrapper for settings metabox content.
 */
function adminimize_meta_box_menu_options_page() {
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

	$args = array(
		'option_namespace' => 'adminimize_menu',
		'settings'         => $settings,
		'custom_options'   => false
	);
	adminimize_generate_checkbox_table( $args );
}

function adminimize_add_meta_box_menu_options() {

	add_meta_box(
		/* $id,           */ 'adminimize_add_meta_box_menu_options',
		/* $title,        */ __( 'Deactivate Menu Options for Roles', 'adminimize' ),
		/* $callback,     */ 'adminimize_meta_box_menu_options_page',
		/* $post_type,    */ Adminimize_Options_Page::$pagehook,
		/* $context,      */ 'normal'
		/* $priority,     */
		/* $callback_args */
	);
	
}

add_action( 'admin_menu', 'adminimize_add_meta_box_menu_options', 20 );
add_action( 'network_admin_menu', 'adminimize_add_meta_box_menu_options', 20 );

add_action( 'adminimize_register_settings', function () {
	register_setting( Adminimize_Options_Page::$pagehook, 'adminimize_menu' );
} );
