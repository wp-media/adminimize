<?php
class Adminimize_Validate_Options extends Adminimize_Storage
{

	public function validate( $input ) {

		// do not validate if it is not an update request
		if ( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != 'update' )
			return $input;

		$output  = array();
		$output  = $this->get_option();
		$widgets = $this->widget_object;

// var_dump( 'INPUT', $input );
// var_dump( 'OUTPUT', $output );

		$common       = new Adminimize_Common();
		$user_roles   = $common->get_all_user_roles();
		$used_options = $widgets->get_used_options();

		foreach( $used_options as $option ) {

			/*
			 * sanitize checkboxes
			 */
			foreach( $user_roles as $role ) {

				$id   = sprintf( '%s_%s', $option, $role );
				$data = ( isset( $output[$id] ) ) ? $output[$id] :
					( isset( $input[$id] ) ? $input[$id] : array() );

				foreach ( $data as $key => $value )
					$output[$id][$key] = ( isset( $input[$id][$key] ) && ! empty( $input[$id][$key] ) ) ? true : false;

			}

			/*
			 * sanitize custom options
			 */
			$custom_option_options = sprintf( 'custom_%s_options', $option );
			$custom_option_values  = sprintf( 'custom_%s_values', $option );

			if ( key_exists( $custom_option_options, $input ) && key_exists( $custom_option_values, $input ) ) {

				$opts = explode( "\n", $input[ $custom_option_options ] );
				$vals = explode( "\n", $input[ $custom_option_values ] );

				// make both arrays the same size
				$c_opts = count( $opts );
				$c_vals = count( $vals );

				if ( $c_opts > $c_vals ) {
					$vals = array_pad( $vals, $c_opts, '' );
				} elseif( $c_vals > $c_opts ) {
					$opts = array_pad( $opts, $c_vals, '' );
				}

				// escaping-escaping-escaping!!!oneeleven
				foreach ( $opts as &$o )
					$o = esc_attr( trim( $o ) );

				foreach ( $vals as &$v )
					$v = esc_attr( trim( $v ) );

				$output[ $option . '_custom' ] = array_combine( $opts, $vals );

			}

		}



// var_dump( 'INPUT', $input );
// var_dump( 'OUTPUT', $output['dashboard_widgets_custom']  );
// exit;

		return $output;
	}

}