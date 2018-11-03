<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*-

namespace Adminimize\Settings;

use Adminimize\Exceptions\SettingNotFoundException;

class SettingsRepository {

	/**
	 * Option's key in WP.
	 */
	const OPTION_NAME = 'mw_adminimize';

	/**
	 * SettingsRepository constructor.
	 */
	public function __construct()
    {
		add_option(self::OPTION_NAME, [], '', 'no');
	}

    /**
     * Gets all options if no key is provided, otherwise returns requested option.
     *
     * @param string $key
     *
     * @return mixed
     * @throws \Adminimize\Exceptions\SettingNotFoundException
     */
    public function get(string $key = '')
	{
	    $options = get_option(self::OPTION_NAME);

	    if (!$key) {
	        return $options;
        }

        if (!array_key_exists($key, $options)) {
            throw new SettingNotFoundException;
        }

        return $options[$key];
	}

    /**
     * Updates the whole options set with a new set.
     *
     * @param array $options
     * @return bool
     */
	public function update(array $options): bool
	{
        return update_option(self::OPTION_NAME, $options);
	}
}
