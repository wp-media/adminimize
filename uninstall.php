<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*-

namespace Adminimize;

use Adminimize\Settings\SettingsRepository;

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	return;
}

delete_site_option( SettingsRepository::OPTION_NAME );
delete_option( SettingsRepository::OPTION_NAME );
