<?php declare( strict_types = 1 ); # -*- coding: utf-8 -*-

namespace Adminimize\SettingsPage\Tabs;

use Adminimize\SettingsPage\Interfaces\TabInterface;
use Adminimize\SettingsPage\Interfaces\SettingsPageInterface;
use ChriCo\Fields\ElementFactory;
use ChriCo\Fields\ViewFactory;

/**
 * Stub: Tab for Admin Bar Settings.
 */
class AdminBar implements TabInterface {

	/**
	 * Holds an instance of the settings page
	 *
	 * @var \Adminimize\SettingsPage\Interfaces\SettingsPageInterface
	 */
	private $settings_page;

    /**
     * Constructor.
     *
     * @param \Adminimize\SettingsPage\Interfaces\SettingsPageInterface $settings_page
     */
	public function __construct( SettingsPageInterface $settings_page ) {

		$this->settings_page = $settings_page;
	}

	/**
	 * Get display title for the tab.
	 *
	 * @return string
	 */
	public function get_tab_title(): string {

		return esc_html_x( 'Admin Bar', 'Tab Title', 'adminimize' );
	}

    /**
     * @return array
     */
	public function define_fields(): array
	{
	    $all_roles = get_editable_roles();
	    $roles_checkboxes = [];

	    foreach ($all_roles as $role_key => $role_data) {
            $roles_checkboxes[$role_key] = $role_data['name'];
        }

	    return [
            'attributes' => [
                'name' => 'my-form',
                'type' => 'form'
            ],
            'elements' => [
                [
                    'attributes' => [
                        'name' => 'my-text',
                        'type' => 'text'
                    ],
                    'label'             => 'My label',
                    'label_attributes'  => [ 'for' => 'my-id' ],
                    'errors'            => [ 'error-id' => 'Error message' ],
                ],
                [
                    'attributes' => [
                        'name' => 'role',
                        'type' => 'checkbox'
                    ],
                    'label'     => 'Role',
                    'choices'   => $roles_checkboxes
                ],
            ],
        ];
	}

	/**
	 * Render content of the tab.
	 *
	 * @return void
	 */
	public function render_tab_content()
    {
	    $form = (new ElementFactory())->create($this->define_fields());
	    $settingsHtml = (new ViewFactory())->create( 'form' )->render( $form );

		/** @noinspection PhpIncludeInspection */
		include $this->settings_page->get_template_path() . '/AdminBar.php';
	}
}
