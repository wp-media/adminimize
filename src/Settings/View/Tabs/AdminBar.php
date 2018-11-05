<?php declare(strict_types = 1); # -*- coding: utf-8 -*-

namespace Adminimize\Settings\View\Tabs;

/**
 * Stub: Tab for Admin Bar Settings.
 */
class AdminBar extends Tab
{
	/**
	 * Get display title for the tab.
	 *
	 * @return string
	 */
	public function getTabTitle(): string
    {
		return esc_html_x('Admin Bar', 'Tab Title', 'adminimize');
	}

    /**
     * @return array
     */
	public function defineFields(): array
	{
        return [
            'attributes' => [
                'name' => 'my-form',
                'action' => $this->settingsPage->getUrl(),
                'type' => 'form',
                'method' => 'post',
            ],
            'elements' => [
                [
                    'attributes' => [
                        'name' => 'test1',
                        'type' => 'text'
                    ],
                    'label' => 'Test 1',
                    'label_attributes' => [ 'for' => 'test1' ],
                ],
                [
                    'attributes' => [
                        'name' => 'test2',
                        'type' => 'text'
                    ],
                    'label' => 'Test 2',
                    'label_attributes' => [ 'for' => 'test1' ],
                ],
                [
                    'attributes' => [
                        'name' => 'submit',
                        'type' => 'submit'
                    ],
                ],
            ],
        ];
	}
}
