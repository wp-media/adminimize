<?php 

/**
 * Table wrapper for settings metabox content.
 */
function adminimize_meta_box_links_options_page() {

	$settings = array(
		'name' => array(
			'title'    => __( 'Name', 'adminimize' ),
			'description' => '#namediv'
		),
		'web_address' => array(
			'title'    => __( 'Web Address', 'adminimize' ),
			'description' => '#addressdiv'
		),
		'description' => array(
			'title'    => __( 'Description', 'adminimize' ),
			'description' => '#descriptiondiv'
		),
		'categories' => array(
			'title'    => __( 'Categories', 'adminimize' ),
			'description' => '#linkcategorydiv'
		),
		'target' => array(
			'title'    => __( 'Target', 'adminimize' ),
			'description' => '#linktargetdiv'
		),
		'link_relationship' => array(
			'title'    => __( 'Link Relationship (XFN)', 'adminimize' ),
			'description' => '#linkxfndiv'
		),
		'advanced' => array(
			'title'    => __( 'Advanced', 'adminimize' ),
			'description' => '#linkadvanceddiv'
		),
		'publish_actions' => array(
			'title'    => __( 'Publish Actions', 'adminimize' ),
			'description' => '#misc-publishing-actions'
		),
	);

	$args = array(
		'option_namespace' => 'adminimize_links',
		'settings'         => $settings
	);
	adminimize_generate_checkbox_table( $args );
}

function adminimize_add_meta_box_links_options() {

	add_meta_box(
		/* $id,           */ 'adminimize_add_meta_box_links_options',
		/* $title,        */ __( 'Deactivate Link Options for Roles', 'adminimize' ),
		/* $callback,     */ 'adminimize_meta_box_links_options_page',
		/* $post_type,    */ Adminimize_Options_Page::$pagehook,
		/* $context,      */ 'normal'
		/* $priority,     */
		/* $callback_args */
	);
	
}

add_action( 'admin_menu', 'adminimize_add_meta_box_links_options', 20 );
add_action( 'network_admin_menu', 'adminimize_add_meta_box_links_options', 20 );

add_action( 'adminimize_register_settings', function () {
	register_setting( Adminimize_Options_Page::$pagehook, 'adminimize_links' );
	register_setting( Adminimize_Options_Page::$pagehook, 'adminimize_links_custom' );
} );