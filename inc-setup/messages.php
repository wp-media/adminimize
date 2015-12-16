<?php
/**
 * some basics for message
 */
if ( ! function_exists( 'add_action' ) ) {
	die( "Hi there!  I'm just a part of plugin, not much I can do when called directly." );
}

// Need only on admin area
if ( ! is_admin() ) {
	return NULL;
}

class _mw_adminimize_message_class {

	/**
	 * constructor
	 */
	public function __construct() {

		$this->localizion_name = 'adminimize';
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
			return __( 'Unknown error.', $this->localizion_name );
		}

		return $errorMessage;
	}

	/**
	 * Initializes all the error messages
	 */
	public function initialize_errors() {

		$this->errors->add( '_mw_adminimize_update', __( 'The updates were saved.', $this->localizion_name ) );
		$this->errors->add(
			'_mw_adminimize_access_denied',
			__( 'You have not enough rights to edit entries in the database.', $this->localizion_name )
		);
		$this->errors->add(
			'_mw_adminimize_import', __( 'All entries in the database were imported.', $this->localizion_name )
		);
		$this->errors->add(
			'_mw_adminimize_deinstall', __( 'All entries in the database were deleted.', $this->localizion_name )
		);
		$this->errors->add(
			'_mw_adminimize_deinstall_yes', __( 'Set the checkbox on deinstall-button.', $this->localizion_name )
		);
		$this->errors->add(
			'_mw_adminimize_get_option', __( 'Can\'t load menu and submenu.', $this->localizion_name )
		);
		$this->errors->add( '_mw_adminimize_set_theme', __( 'Backend-Theme was activated!', $this->localizion_name ) );
		$this->errors->add(
			'_mw_adminimize_load_theme', __( 'Load user data to themes was successful.', $this->localizion_name )
		);
	}

} // end class