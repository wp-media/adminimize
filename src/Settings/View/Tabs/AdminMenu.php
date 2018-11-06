<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace Adminimize\Settings\View\Tabs;

/**
 * Stub: Tab for Admin Menu Settings.
 */
class AdminMenu extends Tab
{
	/**
	 * Get display title for the tab.
	 *
	 * @return string
	 */
	public function title(): string
    {
		return esc_html_x('Admin Menu', 'Tab Title', 'adminimize');
	}

    /**
     * @return array
     */
    public function defineFields(): array
    {
       return [];
    }
}
