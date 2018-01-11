<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*-

namespace Adminimize;

if ( ! \defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	return;
}

delete_site_option( 'mw_adminimize' );
delete_option( 'mw_adminimize' );
