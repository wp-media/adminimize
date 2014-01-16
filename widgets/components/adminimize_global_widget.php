<?php
/**
 * Global options widget
 *
 * PHP version 5.2
 *
 * @category   PHP
 * @package    WordPress
 * @subpackage Inpsyde\Adminimize
 * @author     Ralf Albert <me@neun12.de>
 * @license    GPLv3 http://www.gnu.org/licenses/gpl-3.0.txt
 * @version    1.0
 * @link       http://wordpress.com
 */

if ( ! class_exists( 'Adminimize_Global_Widget' ) ) {

class Adminimize_Global_Widget extends Adminimize_Base_Widget implements I_Adminimize_Widget
{
	public function get_attributes() {

		return array(
				'id'            => 'global_widget',
				'title'         => __( 'Global Options', $this->pluginheaders->TextDomain ),
				'context'       => 0,
				'priority'      => 'default',
				'callback_args' => array(),
				'option_name'   => 'global_option'
		);

	}

	public function get_hooks() {

		return array(
		 	'actions' => array(
		 			array( 'admin_head', array( $this, 'do_global_options' ), 1, 0 ),
			),
		);

	}

	public function content() {

		$attr = $this->get_attributes();

		$global_options = array(
				array( 'id' => '.show-admin-bar', 'title' => __('Admin Bar', $this->pluginheaders->TextDomain ) ),
				array( 'id' => '#favorite-actions', 'title' => __('Favorite Actions', $this->pluginheaders->TextDomain ) ),
				array( 'id' => '#screen-meta', 'title' => __('Screen-Meta', $this->pluginheaders->TextDomain ) ),
				array( 'id' => '#screen-options, #screen-options-link-wrap', 'title' => __('Screen Options', $this->pluginheaders->TextDomain ) ),
				array( 'id' => '#contextual-help-link-wrap', 'title' => __('Screen Options', $this->pluginheaders->TextDomain ) ),
				array( 'id' => '#your-profile .form-table fieldset', 'title' => __('Contextual Help', $this->pluginheaders->TextDomain ) ),
				array( 'id' => '#admin_color_scheme', 'title' => __('Admin Color Scheme', $this->pluginheaders->TextDomain ) ),
		);

		$custom_globals = $this->storage->get_custom_options( $attr['option_name'] );

		// merge standard options with custom options
		foreach ( $custom_globals['original'] as $title => $id ) {

			if ( empty( $id ) || empty( $title ) )
				continue;

			$global_options[] = array( 'id' => $id, 'title' => $title );

		}

		echo $this->templater->get_table( $attr['option_name'], $global_options, 'global' );
		echo $this->templater->get_custom_setings_table( $attr['option_name'], $custom_globals, 'global' );
		echo $this->templater->get_widget_bottom();

	}

	/**
	 * set global options in backend in all areas
	 */
	public function do_global_options() {

		global $_wp_admin_css_colors;

		// exclude super admin
		if ( $this->common->exclude_super_admin() )
			return NULL;

		$user         = wp_get_current_user();
		$user_roles   = $user->roles;

		$global_style = '';
		$admin_head   =
<<<ADMINHEAD
<!-- global options -->
<style type="text/css">
%s
</style>
ADMINHEAD;

		foreach ( $user_roles as $role ) {

			$style   = '';
			$options = $this->common->get_option( 'global_option_' . $role );

			if ( is_array( $options ) ) {
				$style  = implode( ', ', array_keys( $options ) );
				$style .= " {display: none !important;}\n";

				$global_style .= $style;

				if ( isset( $options['#your-profile .form-table fieldset'] ) && true == $options['#your-profile .form-table fieldset'] )
					$_wp_admin_css_colors = 0;

			}

		}

		printf( $admin_head, $global_style );

	}
}

}