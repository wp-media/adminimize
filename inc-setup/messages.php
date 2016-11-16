<?php
/**
 * some basics for message
 */
if ( ! function_exists( 'add_action' ) ) {
	die( "Hi there!  I'm just a part of plugin, not much I can do when called directly." );
}

// Need only on admin area
if ( ! is_admin() ) {
	return;
}

// If is AJAX Call.
if ( defined('DOING_AJAX') && DOING_AJAX ) {
	return;
}

class _mw_adminimize_message_class {

	/**
	 * constructor
	 */
	public function __construct() {

		$this->errors          = new WP_Error();
		$this->initialize_errors();
	}

	/**
	 * get_error - Returns an error message based on the passed code
	 * Parameters - $code (the error code as a string)
	 *
	 * @param string $code
	 *
	 * @return string $errorMessage
	 */
	public function get_error( $code = '' ) {

		$errorMessage = $this->errors->get_error_message( $code );

		if ( NULL === $errorMessage ) {
			return esc_attr__( 'Unknown error.', 'adminimize' );
		}

		return $errorMessage;
	}

	/**
	 * Initializes all the error messages
	 */
	public function initialize_errors() {

		$this->errors->add( '_mw_adminimize_update', esc_attr__( 'The updates were saved.', 'adminimize' ) );
		$this->errors->add(
			'_mw_adminimize_access_denied',
			esc_attr__( 'You have not enough rights to edit entries in the database.', 'adminimize' )
		);
		$this->errors->add(
			'_mw_adminimize_import', esc_attr__( 'All entries in the database were imported.', 'adminimize' )
		);
		$this->errors->add(
			'_mw_adminimize_uninstall', esc_attr__( 'All entries in the database were deleted.', 'adminimize' )
		);
		$this->errors->add(
			'_mw_adminimize_uninstall_yes', esc_attr__( 'Set the checkbox on deinstall-button.', 'adminimize' )
		);
		$this->errors->add(
			'_mw_adminimize_get_option', esc_attr__( 'Can\'t load menu and submenu.', 'adminimize' )
		);
		$this->errors->add( '_mw_adminimize_set_theme', esc_attr__( 'Backend-Theme was activated!', 'adminimize' ) );
		$this->errors->add(
			'_mw_adminimize_load_theme', esc_attr__( 'Load user data to themes was successful.', 'adminimize' )
		);
	}

} // end class