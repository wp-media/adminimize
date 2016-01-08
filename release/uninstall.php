<?php # -*- coding: utf-8 -*-
namespace Adminimize;

use Inpsyde\Autoload;

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	return;
}

delete_site_option( 'mw_adminimize' );
delete_option( 'mw_adminimize' );