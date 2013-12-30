<?php
/**
 *
 * @package Adminimize
 * @subpackage Admin Bar Options, settings page
 * @author Frank Bültge, Ralf Albert
 * @since 1.8.1 01/10/2013
 */
if ( ! function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a part of plugin, not much I can do when called directly.";
	exit();
}

$common    = new Adminimize_Common();
$templater = new Adminimize_Templater();

$user_roles       = &Adminimize_Templater::$user_roles;
$user_roles_names = &Adminimize_Templater::$user_roles_names;


if ( ! isset( $wp_admin_bar ) )
	$wp_admin_bar = '';

$option = 'admin_bar';
$admin_bar_items = $common->get_admin_bar_items();

echo $templater->get_table( $option, $admin_bar_items );
