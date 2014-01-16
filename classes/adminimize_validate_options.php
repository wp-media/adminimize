<?php
/**
 * Class to validate the saved options
 *
 * PHP version 5.2
 *
 * @category   PHP
 * @package    WordPress
 * @subpackage Inpsyde\Adminimize
 * @author     Ralf Albert <me@neun12.de>
 * @license    GPLv3 http://www.gnu.org/licenses/gpl-3.0.txt
 * @version    1.0
 * @link       http://wordpress.com
 */

if ( ! class_exists( 'Adminimize_Validate_Options' ) ) {

class Adminimize_Validate_Options
{

	public function validate( $input ) {

		// do not validate if it is not an update request
		if ( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != 'update' )
			return $input;

		$registry = new Adminimize_Registry();
		$storage  = $registry->get_storage();
		$common   = $registry->get_common_functions();
		$widgets  = $registry->get_widget_provider();

		$output   = $storage->get_option();

		$user_roles   = $common->get_all_user_roles();
		$used_options = $widgets->get_used_options();

// var_dump( 'INPUT', $input['dashboard_widgets_editor'] );
// var_dump( 'OUTPUT', $output['dashboard_widgets_editor'] );

		foreach( $used_options as $option ) {

			/*
			 * sanitize checkboxes
			 */
			$this->sanitize_checkboxes( $user_roles, $option, $input, $output );

			/*
			 * sanitize custom options
			 */
			$this->sanitize_custom_options( $option, $input, $output );

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

	protected function sanitize_checkboxes( $user_roles, $option, $input, &$output ) {

		foreach( $user_roles as $role ) {

			$id = sprintf( '%s_%s', $option, $role );

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

	}

	protected function sanitize_custom_options( $option, $input, &$output ) {

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

}

}