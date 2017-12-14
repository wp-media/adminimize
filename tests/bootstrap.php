<?php # -*- coding: utf-8 -*-
$vendor = dirname( __DIR__ ) . '/vendor/';
if ( ! realpath( $vendor ) ) {
	die( 'Please install via Composer before running the tests.' );
}
require_once $vendor . 'autoload.php';
unset( $vendor );