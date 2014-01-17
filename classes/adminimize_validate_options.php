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

	protected $storage = null;
	protected $common = null;
	protected $user_roles = array();

	public function validate( $input ) {

		// do not validate if it is not an update request
		if ( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != 'update' )
			return $input;

		$registry = new Adminimize_Registry();
		$this->storage  = $registry->get_storage();
		$this->common   = $registry->get_common_functions();

		// get stored options
		$output   = $this->storage->get_option();
		$widgets  = $registry->get_widgets();

// var_dump( 'INPUT', $input );
// var_dump( 'OUTPUT', $output );

		foreach ( $widgets as $widget ) {

			$used_option = $widget->get_used_option();

			// break if no data for the used option was submitted
			if ( ! key_exists( $used_option, $input ) )
				continue;
			else
				$posted_options = $input[ $used_option ];

			// perform the callback functions to validate the options
			$callbacks = (array) $widget->get_validation_callbacks();

			foreach ( $callbacks as $cb ) {

				$cb = trim( strtolower( $cb ) );

				if ( is_string( $cb ) && method_exists( $this, $cb ) )
					$this->$cb( $used_option, $posted_options, $output );

			}

		}


		/*
		 * cleanup unused options
		 */
		$output = $this->cleanup( $output );

// var_dump( $input );
// var_dump( $output['dashboard_widgets'] );
// exit;

		return $output;

	}

	protected function get_user_roles() {
		$this->user_roles = $this->common->get_all_user_roles();
		return $this->user_roles;
	}

	/**
	 * Removes empty values from options
	 * @param  array $output Options to be cleaned
	 * @return array $output Cleaned options
	 */
	protected function cleanup( $output ) {

		$do_not_clean = array( 'available_dashboard_widgets', 'adminbar_nodes' );

		foreach ( $output as $key => &$value ) {

			if ( in_array( $key, $do_not_clean ) )
				continue;

			if ( is_array( $value ) )
				$value = $this->cleanup( $value );

			if ( empty( $value ) )
				unset( $output[ $key ] );

		}

		return $output;

	}

	/**
	 * Sanitize checkboxes
	 * @param  string $option Option name
	 * @param  array  $input  Array with options which was send
	 * @param  array  $output Reference to the original options for saving
	 * @return array  $output Array with sanitized checkboxes
	 */
	protected function sanitize_checkboxes( $option, $input, &$output ) {

		if ( empty( $this->user_roles ) )
			$this->user_roles = $this->get_user_roles();

		$old_values = (array) $this->storage->get_option( $option );

		foreach( $this->user_roles as $role ) {

			// set checked checkboxes
			if ( key_exists( $role, $input ) )
				foreach ( $input[ $role ] as $id => $value )
					$output[ $option ][ $role ][ $id ] = (bool) $value;

			// delete unchecked checkboxes
			if ( key_exists( $role, $old_values ) )
				foreach ( $old_values[ $role ] as $id => $value )
					if ( ! key_exists( $role, $input ) || ! key_exists( $id, $input[ $role ] ) )
						$output[ $option ][ $role ][ $id ] = false;


		}

		return $output;

	}

	/**
	 * Sanitize the options from custom table
	 * @param unknown $option
	 * @param unknown $input
	 * @param unknown $output
	 * @return Ambigous <multitype:string , string, mixed>
	 */
	protected function sanitize_custom_options( $option, $input, &$output ) {

			$custom_left = $custom_right = array();

			if ( key_exists( 'custom_left', $input ) ) {

				$customs = explode( "\n", $input['custom_left'] );

				foreach ( $customs as $elem )
					$custom_left[] = esc_html( $elem );

			}

			if ( key_exists( 'custom_right', $input ) ) {

				$customs = explode( "\n", $input['custom_right'] );

				foreach ( $customs as $elem )
					$custom_right[] = esc_html( $elem );

			}

			// get both sides the same size
			$cl = count( $custom_left );
			$cr = count( $custom_right );

			if ( $cl > $cr ) {
				$custom_right = array_pad( $custom_right, '', $cl );
			} elseif ( $cr > $cl ) {
				$custom_left = array_pad( $custom_left, '', $cr );
			}

			$both = array_combine( $custom_left, $custom_right );

			$output[ $option ]['custom'] = $both;

		return $output;

	}

}

}