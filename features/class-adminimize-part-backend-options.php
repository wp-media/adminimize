<?php 
namespace Adminimize\Part;

require_once 'class-adminimize-part-base-meta-box.php';

/**
 * Options to hide menu entries.
 */
class Backend_Options extends \Adminimize\Part\Base_Meta_Box {

	/**
	 * Get translated meta box title.
	 * 
	 * @return string
	 */
	public function get_meta_box_title() {
		return __( 'Deactivate Backend Options for Roles', 'adminimize' );
	}

	/**
	 * Get option namespace.
	 *
	 * Will be used to serialize settings.
	 * 
	 * @return string
	 */
	public function get_option_namespace() {
		return 'adminimize_backend';
	}

	/**
	 * Populate $settings var with data.
	 * 
	 * @return void
	 */
	protected function init_settings() {

		$settings = array();

		if ( function_exists( 'is_super_admin' ) ) {
			$settings['exclude_super_admin'] = array(
				'title'       => __( 'Exclude Super Admin', 'adminimize' ),
				'description' => __( 'Exclude the Super Admin on a WP Multisite Install from all limitations of this plugin.', 'adminimize' ),
				'options'     => array(
					0 => __( 'Default', 'adminimize' ),
					1 => __( 'Activate', 'adminimize' )
				),
			);
		}

		$settings['user_info'] = array(
			'title'       => __( 'User-Info', 'adminimize' ),
			'description' => __( 'The &quot;User-Info-area&quot; is on the top right side of the backend. You can hide or reduced show.', 'adminimize' ),
			'options'     => array(
				0 => __( 'Default', 'adminimize' ),
				1 => __( 'Hide', 'adminimize' ),
				2 => __( 'Only Logout', 'adminimize' ),
				3 => __( 'User &amp; Logout', 'adminimize' )
			)
		);

		$settings['ui_redirect'] = array(
			'title'       => __( 'Change User-Info, redirect to', 'adminimize' ),
			'description' => __('When the &quot;User-Info-area&quot; change it, then it is possible to change the redirect.', 'adminimize' ),
			'options'     => array(
				0 => __( 'Default', 'adminimize' ),
				1 => __( 'Frontpage of the Blog', 'adminimize' )
			),
			'disabled' => in_array( adminimize_get_option('user_info'), array( '', '1', '0' ) )
		);

		$settings['footer'] = array(
			'title'       => __( 'Footer', 'adminimize' ),
			'description' => __( 'The Footer-area can hide, include all links and details.', 'adminimize' ),
			'options'     => array(
				0 => __( 'Default', 'adminimize' ),
				1 => __( 'Hide', 'adminimize' )
			)
		);

		$settings['header'] = array(
			'title'       => __( 'Header', 'adminimize' ),
			'description' => __( 'The Header-area can hide, include all links and details.', 'adminimize' ),
			'options'     => array(
				0 => __( 'Default', 'adminimize' ),
				1 => __( 'Hide', 'adminimize' )
			)
		);

		$settings['timestamp'] = array(
			'title'       => __( 'Timestamp', 'adminimize' ),
			'description' => __( 'Opens the post timestamp editing fields without you having to click the "Edit" link every time.', 'adminimize' ),
			'options'     => array(
				0 => __( 'Default', 'adminimize' ),
				1 => __( 'Activate', 'adminimize' )
			)
		);

		$settings['tb_window'] = array(
			'title'       => __( 'Thickbox FullScreen', 'adminimize' ),
			'description' => __( 'All Thickbox-function use the full area of the browser. Thickbox is for example in upload media-files.', 'adminimize' ),
			'options'     => array(
				0 => __( 'Default', 'adminimize' ),
				1 => __( 'Activate', 'adminimize' )
			)
		);

		$settings['control_flashloader'] = array(
			'title'       => __( 'Flashuploader', 'adminimize' ),
			'description' => __( 'Disable the flashuploader and users use only the standard uploader.', 'adminimize' ),
			'options'     => array(
				0 => __( 'Default', 'adminimize' ),
				1 => __( 'Activate', 'adminimize' )
			)
		);

		$settings['cat_full'] = array(
			'title'       => __( 'Category Height', 'adminimize' ),
			'description' => __( 'View the Meta Box with Categories in the full height, no scrollbar or whitespace.', 'adminimize' ),
			'options'     => array(
				0 => __( 'Default', 'adminimize' ),
				1 => __( 'Activate', 'adminimize' )
			)
		);

		$settings['advice'] = array(
			'title'       => __( 'Advice in Footer', 'adminimize' ),
			'description' => __( 'In the Footer you can display an  advice for changing the Default-design, (x)HTML is possible.', 'adminimize' ),
			'options'     => array(
				0 => __( 'Default', 'adminimize' ),
				1 => __( 'Activate', 'adminimize' )
			)
		);

		$this->settings = $settings;
	}

	/**
	 * Print meta box contents.
	 * 
	 * @return void
	 */
	public function meta_box_content() {

		add_action( 'after_adminimize_field_with_select-advice', function () {
			?>
			<textarea style="width: 85%" class="code" rows="2" cols="60" name="adminimize_backend[advice_txt]" id="adminimize_backend_advice_txt" ><?php echo htmlspecialchars( stripslashes( adminimize_get_option( 'advice_txt', '', 'adminimize_backend' ) ) ); ?></textarea>
			<?php
		} );

		$settings = $this->get_settings();

		foreach ( $settings as $settings_name => $settings_args ) {
			add_settings_field(
				/* $id,       */ 'adminimize_field_' . $settings_name,
				/* $title,    */ $settings_args['title'],
				/* $callback, */ '\Adminimize\adminimize_field_with_select',
				/* $page      */ \Adminimize_Options_Page::$pagehook,
				/* $section   */ 'backend-options',
				/* $args      */ array( 'settings_name' => $settings_name, 'args' => $settings_args )
			);
		}

		?>
		<table summary="config" class="widefat">
			<tbody>
				<?php do_settings_fields( \Adminimize_Options_Page::$pagehook, 'backend-options' ); ?>
			</tbody>
		</table>

		<br style="clear: both">
		<?php submit_button( __( 'Save Changes' ), 'button-primary', 'submit', TRUE ); ?>
		<br style="clear: both">
		<?php	
	}

}

Backend_Options::get_instance();
