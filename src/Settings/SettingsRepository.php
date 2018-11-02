<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*-

namespace Adminimize\Settings;

use Adminimize\Settings\Exceptions\SettingNotFoundException;

/**
 * Class Settings to handle settings in the database.
 *
 * @package Adminimize\Settings
 */
class SettingsRepository {

	/**
	 * @var string
	 */
	private $option_name = 'mw_adminimize';

	/**
	 * SettingsRepository constructor.
	 */
	public function __construct()
    {
		add_option($this->get_name(), [], '', 'no');
	}

    /**
     * @return string
     */
	public function get_name(): string
	{
	    return $this->option_name;
	}

    /**
     * Gets all options if no key is provided, otherwise returns requested option.
     *
     * @param string $key
     * @return mixed
     * @throws \Adminimize\Settings\Exceptions\SettingNotFoundException
     */
    public function get(string $key = '')
	{
	    $options = get_option($this->get_name());

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
        return update_option($this->get_name(), $options);
	}
}
