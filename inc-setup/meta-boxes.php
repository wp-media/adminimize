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

if ( ! is_admin() ) {
	return;
}

// If is AJAX Call.
if ( defined('DOING_AJAX') && DOING_AJAX ) {
	return;
}

// The global var is only usable on edit Post Type page
add_filter( 'do_meta_boxes', '_mw_adminimize_get_all_meta_boxes', 0, 3 );
function _mw_adminimize_get_all_meta_boxes( $post_type, $priority, $post ) {

	global $wp_meta_boxes;

	if ( ! empty( $wp_meta_boxes[ $post_type ] ) ) {

		// get all options
		$adminimizeoptions = _mw_adminimize_get_option_value();

		// add meta box array for post type
		$adminimizeoptions[ 'mw_adminimize_meta_boxes_' . $post_type ] = $wp_meta_boxes[ $post_type ];

		// update options
		_mw_adminimize_update_option( $adminimizeoptions );
	}
}

function _mw_adminimize_get_meta_boxes( $post_type = NULL, $context = 'advanced' ) {

	$saved_wp_meta_boxes = _mw_adminimize_get_option_value( 'mw_adminimize_meta_boxes_' . $post_type );

	return $saved_wp_meta_boxes;
}

function _mw_adminimize_remove_meta_boxes( $post_type = NULL, $context = 'advanced', $priority = 'default', $id ) {

	// @TODO foreach about settings
	remove_meta_box( $id, $post_type, $context );
}

// remove on 'admin_menu' Hook
