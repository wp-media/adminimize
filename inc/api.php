<?php

/**
 * Get option from adminize settings namespace.
 * 
 * @param  string $name    
 * @param  mixed  $default 
 * @return mixed          
 */
function adminimize_get_option( $name, $default = NULL, $namespace = 'adminimize' ) {

	if ( Adminimize::get_instance()->is_active_for_multisite() )
		$options = get_site_option( $namespace, array() );
	else
		$options = get_option( $namespace, array() );

	return isset( $options[ $name ] ) ? $options[ $name ] : $default; 
}

/**
 * Check if user has given role.
 * 
 * @param  string  $role WordPress role identificator
 * @param  WP_User $user WordPress user object. Defaults to current user.
 * @return bool          true if user has given role. Otherwise false.
 */
function adminimize_user_has_role( $role, WP_User $user = NULL ) {

	if ( NULL === $user )
		$user = wp_get_current_user();

	return is_array( $user->roles )
	    && in_array( $role, $user->roles )
	    && current_user_can( $role );
}

/**
 * Returns an array with all user roles(names) in it.
 * Includes self defined roles.
 * 
 * @return array
 */
function adminimize_get_all_user_roles() {
	global $wp_roles;
	
	$user_roles = array();
	
	if ( isset( $wp_roles->roles ) && is_array( $wp_roles->roles ) ) {
		foreach ( $wp_roles->roles as $role => $_)
			array_push( $user_roles, $role );
	}
	
	return $user_roles;
}


/**
 * Returns an array with all user roles_names in it.
 * Includes self defined roles.
 * 
 * @return array
 */
function adminimize_get_all_user_roles_names() {
	global $wp_roles;
	
	$user_roles_names = array();

	foreach ( $wp_roles->role_names as $role_name => $data ) {
		if ( function_exists( 'translate_user_role' ) )
			$data = translate_user_role( $data );
		else
			$data = translate_with_context( $data );
		
		array_push( $user_roles_names, $data );
	}
	
	return $user_roles_names;
}