<?php declare( strict_types=1 ); // -*- coding: utf-8 -*-

namespace Adminimize\Settings\Interfaces;

/**
 * Interface for Tabs class.
 */
interface TabsInterface
{
    /**
     * Returns array with namespaced class names of all tabs.
     *
     * @return array
     */
    public function list(): array;
}
