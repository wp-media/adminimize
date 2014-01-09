<?php
class Adminimize_Validate_Options extends Adminimize_Storage
{

	public function validate( $input ) {

		// do not validate if it is not an update request
		if ( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != 'update' )
			return $input;

		$output = array();
		$output = $this->get_option();
		$widgets = $this->widget_object;

// var_dump( 'INPUT', $input );
// var_dump( 'OUTPUT', $output );

		$common       = new Adminimize_Common();
		$user_roles   = $common->get_all_user_roles();
		$used_options = $widgets->get_used_options();

		foreach( $used_options as $option ) {

			foreach( $user_roles as $role ) {

				$id = sprintf( '%s_%s', $option, $role );

				if ( isset( $output[$id] ) ) {
					foreach ( $output[$id] as $key => $value ) {
						$output[$id][$key] = ( isset( $input[$id][$key] ) && ! empty( $input[$id][$key] ) ) ? true : false;
					}
				}

			}

		}



// var_dump( 'INPUT', $input );
// var_dump( 'OUTPUT', $output );
// exit;

		return $output;
	}

}