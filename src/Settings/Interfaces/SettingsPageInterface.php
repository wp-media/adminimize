<?php // -*- coding: utf-8 -*-

namespace Adminimize\Settings\Interfaces;

interface SettingsPageInterface
{
    /**
     * Get the path to the templates.
     *
     * @return string
     */
    public function templatePath(): string;

    /**
     * @return string
     */
    public function capability(): string;

    /**
     * @return string
     */
    public function slug(): string;

    /**
     * @return string
     */
    public function url(): string;
}
