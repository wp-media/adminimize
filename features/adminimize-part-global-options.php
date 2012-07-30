<?php 

/**
 * Table wrapper for settings metabox content.
 */
function adminimize_meta_box_global_options_page() {

	$settings = array(
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

	$args = array(
		'option_namespace' => 'adminimize_backend',
		'settings'         => $settings
	);
	adminimize_generate_checkbox_table( $args );
}

function adminimize_add_meta_box_global_options() {

	add_meta_box(
		/* $id,           */ 'adminimize_add_meta_box_global_options',
		/* $title,        */ __( 'Deactivate Backend Options for Roles', 'adminimize' ),
		/* $callback,     */ 'adminimize_meta_box_global_options_page',
		/* $post_type,    */ Adminimize_Options_Page::$pagehook,
		/* $context,      */ 'normal'
		/* $priority,     */
		/* $callback_args */
	);
	
}

add_action( 'admin_menu', 'adminimize_add_meta_box_global_options', 20 );
add_action( 'network_admin_menu', 'adminimize_add_meta_box_global_options', 20 );
