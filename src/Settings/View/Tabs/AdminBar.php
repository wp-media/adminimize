<?php declare(strict_types = 1); // -*- coding: utf-8 -*-

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
    public function title(): string
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
                'method' => 'post',
                'name' => 'my-form',
                'action' => $this->settingsPage->url(),
                'type' => 'form',
            ],
            'elements' => [
                [
                    'attributes' => [
                        'name' => 'test1',
                        'type' => 'text',
                    ],
                    'label' => 'Test 1',
                    'label_attributes' => [ 'for' => 'test1' ],
                ],
                [
                    'attributes' => [
                        'name' => 'test2',
                        'type' => 'text',
                    ],
                    'label' => 'Test 2',
                    'label_attributes' => [ 'for' => 'test1' ],
                ],
                [
                    'attributes' => [
                        'name' => 'submit',
                        'type' => 'submit',
                    ],
                ],
            ],
        ];
    }
}
