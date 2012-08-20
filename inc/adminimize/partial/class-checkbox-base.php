<?php
namespace Inpsyde\Adminimize\Partial;
use \Inpsyde\Adminimize;

/**
 * Abstract Class for "Checkbox Matrix" Partials.
 */
abstract class Checkbox_Base extends Base {
	
	protected function __construct() {
		
		parent::__construct();
		add_action( 'admin_init', array( $this, 'apply_settings_for_current_user' ) );
	}

	public function apply_settings_for_current_user() {

		$roles = Adminimize\get_all_user_roles();
		$settings = $this->get_settings();

		if ( ! $settings || ! $roles )
			return;

		foreach ( $settings as $setting_index => $setting_values ) {

			$values = Adminimize\get_option( $setting_index, array(), $this->get_option_namespace() );
			foreach ( $roles as $role ) {
				if ( Adminimize\user_has_role( $role ) && isset( $values[ $role ] ) && $values[ $role ] ) {
					$this->apply_checkbox_setting( $setting_index, $setting_values, $role );
				}
			}
		}
	}

	/**
	 * Apply setting for role.
	 *
	 * This method is called for every setting that is active.
	 * 
	 * @param  string $index  setting index
	 * @param  array  $values setting values
	 * @param  string $role   WordPress role handle
	 * @return void
	 */
	public abstract function apply_checkbox_setting( $index, $values, $role );

}