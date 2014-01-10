<?php
/**
 * Class with common functions
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

if ( ! class_exists( 'Adminimize_Common' ) ) {

class Adminimize_Common extends Adminimize_Storage
{
	/**
	 * _mw_adminimize_get_all_user_roles() - Returns an array with all user roles(names) in it.
	 * Inclusive self defined roles (for example with the 'Role Manager' plugin).
	 * code by Vincent Weber, www.webRtistik.nl
	 *
	 * @uses $wp_roles
	 * @return $user_roles
	 */
	public function get_all_user_roles() {

		global $wp_roles;

		$user_roles = array ();

		if ( isset( $wp_roles->roles ) && is_array( $wp_roles->roles ) ) {
			foreach ( $wp_roles->roles as $role => $data ) {
				array_push( $user_roles, $role );
				// $data contains caps, maybe for later use..
			}
		}

		// exclude the new bbPress roles
		$user_roles = array_diff( $user_roles, array (
				'bbp_keymaster',
				'bbp_moderator',
				'bbp_participant',
				'bbp_spectator',
				'bbp_blocked'
		) );

		return $user_roles;

	}

	/**
	 * _mw_adminimize_get_all_user_roles_names() - Returns an array with all user roles_names in it.
	 * Inclusive self defined roles (for example with the 'Role Manager' plugin).
	 *
	 * @uses $wp_roles
	 * @return $user_roles_names
	 */
	public function get_all_user_roles_names() {

		global $wp_roles;

		$user_roles_names = array ();

		foreach ( $wp_roles->role_names as $role_name => $data ) {
			if ( function_exists( 'translate_user_role' ) )
				$data = translate_user_role( $data );
			else
				$data = translate_with_context( $data );

			array_push( $user_roles_names, $data );
		}

		// exclude the new bbPress roles
		$user_roles_names = array_diff( $user_roles_names, array (
				__( 'Keymaster', 'bbpress' ),
				__( 'Moderator', 'bbpress' ),
				__( 'Participant', 'bbpress' ),
				__( 'Spectator', 'bbpress' ),
				__( 'Blocked', 'bbpress' )
		) );

		return $user_roles_names;

	}

	/**
	 * Get all admin bar items from settings
	 *
	 * @since 1.8.1 01/10/2013
	 * @return void
	 */
	public function get_admin_bar_items() {

		$admin_bar_items = $this->get_option( 'adminbar_nodes' );
		return $admin_bar_items;

	}

	public function recursive_in_array( $needle, $haystack ) {

		if ( '' != $haystack ) {
			foreach ( $haystack as $stalk ) {
				if ( $needle == $stalk || (is_array( $stalk ) && $this->recursive_in_array( $needle, $stalk ) ) ) {
					return TRUE;
				}
			}
			return FALSE;
		}

	}

	public function exclude_super_admin() {

		// exclude super admin
		if ( function_exists( 'is_super_admin' )
			&& is_super_admin()
			&& true == $this->get_option( 'exclude_super_admin' )
		)
			return TRUE;

		return FALSE;
	}

}

}