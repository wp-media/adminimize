<?php
/**
 * Export settings as json file.
 *
 * @package    Adminimize
 * @subpackage export
 * @author     Frank Bültge
 * @version    2017-04-13
 */

if ( ! function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a part of plugin, not much I can do when called directly.";
	exit;
}

add_action( 'admin_init', '_mw_adminimize_export_json' );
add_action( 'admin_init', '_mw_adminimize_export_role_json' );

/**
 * Process a settings export that generates a .json file of the shop settings.
 */
function _mw_adminimize_export_json() {

	if ( ! is_admin() ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// If is AJAX Call.
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return;
	}

	if ( empty( $_POST[ '_mw_adminimize_export' ] ) || 'true' !== $_POST[ '_mw_adminimize_export' ] ) {
		return;
	}

	require_once ABSPATH . 'wp-includes/pluggable.php';
	if ( ! wp_verify_nonce( $_POST[ 'mw_adminimize_export_nonce' ], 'mw_adminimize_export_nonce' ) ) {
		return;
	}

	$settings = _mw_adminimize_get_option_value();
	$filepath = 'mw_adminimize-settings-export-' . date( 'm-d-Y' ) . '.json';

	ignore_user_abort( TRUE );

	nocache_headers();
	header( 'Cache-Control: public' );
	header( 'Content-Type: application/json; charset=utf-8' );
	header( 'Content-Transfer-Encoding: binary' );
	header( 'Content-Disposition: attachment; filename=' . $filepath );
	//header( 'Content-Length: ' . filesize( $filepath ) );
	header( 'Expires: 0' );

	echo wp_json_encode( $settings );
	exit();
}

/**
 * Process a settings export for one or many roles that generates a .json file of the shop settings.
 * 
 * @return array
 */
function _mw_adminimize_export_role_json() {

	if ( ! is_admin() ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// If is AJAX Call.
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return;
	}

	if ( empty( $_POST[ '_mw_adminimize_export_role' ] ) 
	|| 'true' !== $_POST[ '_mw_adminimize_export_role' ] 
	|| empty( $_POST['select_adminimize_roles']) ) {
		return;
	}

	require_once ABSPATH . 'wp-includes/pluggable.php';
	if ( ! wp_verify_nonce( $_POST[ 'mw_adminimize_export_role_nonce' ], 'mw_adminimize_export_role_nonce' ) ) {
		return;
	}

	$keys = [];
	$adminimize_roles = $_POST['select_adminimize_roles'];
	$adminimize_option = _mw_adminimize_get_option_value();
	foreach( $adminimize_roles as $adminimize_role ){

		$adminimize_role_keys = array_filter(
			$adminimize_option, function( $option_key ) use ( $adminimize_role ){
				return stripos(  $option_key, '_' . $adminimize_role ) !== false;
			}, ARRAY_FILTER_USE_KEY
		);
		if ( empty( $keys ) ){
			$keys = $adminimize_role_keys;
		} else {
			$keys = array_merge( $keys, $adminimize_role_keys );
		} 
	}

	$filepath = 'mw_adminimize-settings-role-export-' . date( 'm-d-Y' ) . '.json';

	ignore_user_abort( TRUE );

	nocache_headers();
	header( 'Cache-Control: public' );
	header( 'Content-Type: application/json; charset=utf-8' );
	header( 'Content-Transfer-Encoding: binary' );
	header( 'Content-Disposition: attachment; filename=' . $filepath );
	header( 'Expires: 0' );

	echo wp_json_encode( $keys );
	exit();
}

