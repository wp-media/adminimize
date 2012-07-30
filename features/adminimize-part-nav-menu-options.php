<?php 

/**
 * Table wrapper for settings metabox content.
 */
function adminimize_meta_box_nav_menu_options_page() {

	$settings = array(
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

	$args = array(
		'option_namespace' => 'adminimize_nav_menu',
		'settings'         => $settings
	);
	adminimize_generate_checkbox_table( $args );
}

function adminimize_add_meta_box_nav_menu_options() {

	add_meta_box(
		/* $id,           */ 'adminimize_add_meta_box_nav_menu_options',
		/* $title,        */ __( 'Deactivate WP Nav Menu Options for Roles', 'adminimize' ),
		/* $callback,     */ 'adminimize_meta_box_nav_menu_options_page',
		/* $post_type,    */ Adminimize_Options_Page::$pagehook,
		/* $context,      */ 'normal'
		/* $priority,     */
		/* $callback_args */
	);
	
}

add_action( 'admin_menu', 'adminimize_add_meta_box_nav_menu_options', 20 );
add_action( 'network_admin_menu', 'adminimize_add_meta_box_nav_menu_options', 20 );

add_action( 'adminimize_register_settings', function () {
	register_setting( Adminimize_Options_Page::$pagehook, 'adminimize_nav_menu' );
	register_setting( Adminimize_Options_Page::$pagehook, 'adminimize_nav_menu_custom' );
} );