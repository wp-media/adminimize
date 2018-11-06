<?php declare( strict_types = 1 ); // -*- coding: utf-8 -*-

namespace Adminimize\Settings\Interfaces;

interface ViewInterface
{
    /**
     * Adds the settings page to the WP menu.
     *
     * @return mixed
     */
    public function addOptionsPage();

    /**
     * HTML and Content for the settings page.
     */
    public function renderPage();
}
