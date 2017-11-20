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
