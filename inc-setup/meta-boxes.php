<?php
/**
 * @package    Adminimize
 * @subpackage Meta Boxes Setup
 * @author     Frank Bültge
 * @since      1.8.1  01/10/2013
 */
if ( ! function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a part of plugin, not much I can do when called directly.";
	exit;
}

if ( ! is_admin() )
	return NULL;

// The global var is only usable on edit Post Type page
add_filter( 'do_meta_boxes', '_mw_adminimize_get_all_meta_boxes', 0, 3 );
function _mw_adminimize_get_all_meta_boxes( $post_type, $priority, $post ) {
	global $wp_meta_boxes;
	
	if ( ! empty( $wp_meta_boxes[$post_type] ) ) {
		
		// get all options
		if ( is_multisite() && is_plugin_active_for_network( MW_ADMIN_FILE ) )
			$adminimizeoptions = get_site_option( 'mw_adminimize' );
		else
			$adminimizeoptions = get_option( 'mw_adminimize' );
		
		// add admin bar array
		$adminimizeoptions['mw_adminimize_meta_boxes_' . $post_type ] = $wp_meta_boxes[$post_type];
		
		// update options
		if ( is_multisite() && is_plugin_active_for_network( MW_ADMIN_FILE ) )
			update_site_option( 'mw_adminimize', $adminimizeoptions );
		else
			update_option( 'mw_adminimize', $adminimizeoptions );
	}
}

function _mw_adminimize_get_meta_boxes( $post_type = null, $context = 'advanced' ) {
	
	$saved_wp_meta_boxes = _mw_adminimize_get_option_value( 'mw_adminimize_meta_boxes_' . $post_type );
	
	return $saved_wp_meta_boxes;
}

function _mw_adminimize_remove_meta_boxes( $post_type = null, $context = 'advanced', $priority = 'default', $id ) {
	
	//@todo foreach about settings
	remove_meta_box( $id, $post_type, $context );
}

// remove on 'admin_menu' Hook
