<?php 
namespace Inpsyde\Adminimize\Partial;
use Inpsyde\Adminimize;

/**
 * Options to hide menu entries.
 */
class Global_Options extends Checkbox_Base {

	/**
	 * This method is called for every setting that is active.
	 *
	 * Generates CSS to hide global options.
	 * Special treatment for admin bar.
	 * 
	 * @param  string $index  setting index
	 * @param  array  $values setting values
	 * @param  string $role   WordPress role handle
	 * @return void
	 */
	public function apply_checkbox_setting( $index, $values, $role ) {

		add_action( 'admin_head', function () use ( $index, $values ) {
			?>
			<style type="text/css">
			<?php if ( $index === 'admin_bar' ): ?>
				html.wp-toolbar { padding-top: 0px !important; }
			<?php endif; ?>
			<?php echo $values['description']; ?> { display: none !important; }
			</style>
			<?php
		} );
	}
	/**
	 * Get translated meta box title.
	 * 
	 * @return string
	 */
	public function get_meta_box_title() {
		return __( 'Deactivate Global Options for Roles', 'adminimize' );
	}

	/**
	 * Get option namespace.
	 *
	 * Will be used to serialize settings.
	 * 
	 * @return string
	 */
	public function get_option_namespace() {
		return 'adminimize_global';
	}

	/**
	 * Populate $settings var with data.
	 * 
	 * @return void
	 */
	protected function init_settings() {
		$this->settings = array(
			'admin_bar' => array(
				'title'    => __( 'Admin Bar', 'adminimize' ),
				'description' => '#wpadminbar, .show-admin-bar'
			),
			'fav_actions' => array(
				'title'    => __( 'Favorite Actions', 'adminimize' ),
				'description' => '#favorite-actions'
			),
			'screen_meta' => array(
				'title'    => __( 'Screen-Meta', 'adminimize' ),
				'description' => '#screen-meta'
			),
			'screen_options' => array(
				'title'    => __( 'Screen Options', 'adminimize' ),
				'description' => '#screen-options, #screen-options-link-wrap'
			),
			'context_help' => array(
				'title'    => __( 'Contextual Help', 'adminimize' ),
				'description' => '#contextual-help-link-wrap'
			),
			'admin_color_scheme' => array(
				'title'    => __( 'Admin Color Scheme', 'adminimize' ),
				'description' => '#your-profile .form-table fieldset'
			),
		);
	}

	/**
	 * Print meta box contents.
	 * 
	 * @return void
	 */
	public function meta_box_content() {

		$args = array(
			'option_namespace' => $this->get_option_namespace(),
			'settings'         => $this->get_settings()
		);
		Adminimize\generate_checkbox_table( $args );
	}

}
