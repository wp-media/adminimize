<?php

/**
 * Get option from adminize settings namespace.
 * 
 * @param  string $name    
 * @param  mixed  $default 
 * @return mixed          
 */
function adminimize_get_option( $name, $default = NULL ) {

	if ( Adminimize::get_instance()->is_active_for_multisite() )
		$options = get_site_option( 'adminimize', array() );
	else
		$options = get_option( 'adminimize', array() );

	return isset( $options[ $name ] ) ? $options[ $name ] : $default; 
}
