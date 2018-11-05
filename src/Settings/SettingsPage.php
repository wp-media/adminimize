<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*-

namespace Adminimize\Settings;

use Adminimize\Settings\Interfaces\SettingsPageInterface;

/**
 * Class SettingsPage
 *
 * @package Adminimize\SettingsPage
 */
class SettingsPage implements SettingsPageInterface
{
	/**
	 * @var string
	 */
	private $capability;

	/**
	 * @var string
	 */
	private $slug;

	/**
	 * Store path to the templates for each tab.
	 *
	 * @var string
	 */
	private $templatePath;

	/**
	 * SettingsPageInterface constructor.
	 *
	 * @param string $templatePath
	 */
	public function __construct( $templatePath )
    {
        $this->slug = 'adminimize';
        $this->capability = 'manage_options';
        $this->templatePath = $templatePath . '/Templates';
    }

	/**
	 * Get the path to the templates.
	 *
	 * @return string
	 */
	public function getTemplatePath(): string
    {
		return $this->templatePath;
	}

	/**
	 * Get the allowed capability to view settings page.
	 *
	 * @return string
	 */
	public function getCapability(): string
    {
		return $this->capability;
	}

	/**
	 * Get the slug for the settings area.
	 *
	 * @return string
	 */
	public function getSlug(): string
    {
		return $this->slug;
	}

    /**
     * Get the URL to the Adminimize settings area.
     *
     * @return string
     */
	public function getUrl(): string
	{
        return add_query_arg(
            ['page' => $this->getSlug()],
            admin_url('options-general.php')
        );
	}
}
