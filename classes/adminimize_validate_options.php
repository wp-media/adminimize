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

		$common       = new Adminimize_Common();
		$user_roles   = $common->get_all_user_roles();
		$used_options = $widgets->get_used_options();

// var_dump( 'INPUT', $input['dashboard_widgets_editor'] );
// var_dump( 'OUTPUT', $output['dashboard_widgets_editor'] );

		foreach( $used_options as $option ) {

			/*
			 * sanitize checkboxes
			 */
			foreach( $user_roles as $role ) {

				$id   = sprintf( '%s_%s', $option, $role );

				if ( ! key_exists( $id, $input ) )
					$input[ $id ] = array();

				if ( ! key_exists( $id, $output ) )
					$output[ $id ] = array();

				$output[$id] = array_merge( $output[ $id ], $input[ $id ] );

				foreach ( $output[ $id ] as $key => $value ) {

					if ( isset( $output[ $id ][ $key ] ) && ! key_exists( $key, $input[ $id ] ) )
						$output[ $id ][ $key ] = false;

					$output[ $id ][ $key ] = (bool) trim( $output[ $id ][ $key ] );

				}

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
			/* end sanitize custom options */

		}


		/*
		 * cleanup unused options
		 */
		$output = $this->cleanup( $output );


// var_dump( 'INPUT', $input['dashboard_widgets_editor'] );
// var_dump( 'OUTPUT', $output['dashboard_widgets_editor'] );


// var_dump( 'INPUT', $input );
// $out = array_keys( $output );
// sort( $out );
// var_dump( 'OUTPUT', $out );
// var_dump( $output['dashboard_widgets_editor'] );
// exit;

		return $output;

	}

	protected function cleanup( $output ) {

		$do_not_clean = array( 'dashboard_widgets', 'adminbar_nodes' );

		foreach ( $output as $key => $value ) {

			if ( in_array( $key, $do_not_clean ) )
				continue;

			if ( ! is_array( $value ) )
				if ( empty( $value ) )
					unset( $output[ $key ] );

			$has_values = false;

			if ( ! is_array( $value ) )
				continue;

			foreach ( $value as $k => $v )
				if ( ! empty( $v ) )
					$has_values = true;

			if ( false == $has_values )
				unset( $output[ $key ] );

		}

		return $output;

	}

}