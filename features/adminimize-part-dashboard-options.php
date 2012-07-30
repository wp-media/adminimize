<?php 

/**
 * Table wrapper for settings metabox content.
 */
function adminimize_meta_box_dashboard_options_page() {

	$settings = array(
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

	$args = array(
		'option_namespace' => 'adminimize_dashboard',
		'settings'         => $settings
	);
	adminimize_generate_checkbox_table( $args );
}

function adminimize_add_meta_box_dashboard_options() {

	add_meta_box(
		/* $id,           */ 'adminimize_add_meta_box_dashboard_options',
		/* $title,        */ __( 'Deactivate Dashboard Options for Roles', 'adminimize' ),
		/* $callback,     */ 'adminimize_meta_box_dashboard_options_page',
		/* $post_type,    */ Adminimize_Options_Page::$pagehook,
		/* $context,      */ 'normal'
		/* $priority,     */
		/* $callback_args */
	);
	
}

add_action( 'admin_menu', 'adminimize_add_meta_box_dashboard_options', 20 );
add_action( 'network_admin_menu', 'adminimize_add_meta_box_dashboard_options', 20 );

add_action( 'adminimize_register_settings', function () {
	register_setting( Adminimize_Options_Page::$pagehook, 'adminimize_dashboard' );
	register_setting( Adminimize_Options_Page::$pagehook, 'adminimize_dashboard_custom' );
} );
