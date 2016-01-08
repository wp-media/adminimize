<?php # -*- coding: utf-8 -*-
namespace Inpsyde\Autoload;

/**
 * Require autoload classes.
 *
 * @package Inpsyde\Autoload
 * @version 2016-01-08
 */
foreach ( array( 'Autoload', 'AutoloadRule', 'NamespaceRule' ) as $name ) {
	$fqn = __NAMESPACE__ . '\\' . $name;
	if ( ! class_exists( $fqn ) && ! interface_exists( $fqn ) ) {
		require_once __DIR__ . DIRECTORY_SEPARATOR . $name . '.php';
	}
}