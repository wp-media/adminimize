<?php 
namespace Inpsyde\Adminimize\Partial;
use Inpsyde\Adminimize;

/**
 * Options to hide menu entries.
 */
class Backend_Options extends Base {

	protected function __construct() {
		
		parent::__construct();
		add_action( 'admin_init', array( $this, 'apply_settings_for_current_user' ) );
	}

	public function apply_settings_for_current_user() {

		if ( ! Adminimize\should_apply_options_for_user() )
			return;

		$settings = $this->get_settings();
		foreach ( $settings as $setting_index => $setting ) {
			$disabled = Adminimize\get_option( $setting_index, NULL, $this->get_option_namespace() );
			if ( $disabled ) {
				if ( isset( $setting['css'] ) ) {
					add_action( $setting['css']['action'], function () use ( $setting ) {
						?><style type="text/css"><?php echo $setting['css']['style']; ?></style><?php
					} );
				}
				if ( isset( $setting['callback'] ) ) {
					call_user_func( $setting['callback'] );
				}
			}
		}
		
	}

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
					1 => __( 'Exclude', 'adminimize' ),
					0 => __( 'Don\'t exclude', 'adminimize' )
				),
			);
		}

		$settings['user_info'] = array(
			'title'       => __( 'User-Info', 'adminimize' ),
			'description' => __( 'The &quot;User-Info-area&quot; is on the top right side of the backend.', 'adminimize' ),
			'options'     => array(
				0 => __( 'Show', 'adminimize' ),
				1 => __( 'Hide', 'adminimize' )
			),
			'css' => array(
				'action' => 'admin_print_styles',
				'style'  => '#wp-admin-bar-top-secondary { display: none; }'
			)
		);

		$settings['user_info_profile'] = array(
			'title'       => __( 'User-Info: Profile-Link', 'adminimize' ),
			'description' => __( 'Hide only the profile link inside the user info box.', 'adminimize' ),
			'options'     => array(
				0 => __( 'Show', 'adminimize' ),
				1 => __( 'Hide', 'adminimize' )
			),
			'css' => array(
				'action' => 'admin_print_styles',
				'style'  => '#wp-admin-bar-edit-profile { display: none; }
							 #wp-admin-bar-user-actions { min-height: 85px; }'
			)
		);

		$settings['user_info_logout'] = array(
			'title'       => __( 'User-Info: Profile-Link', 'adminimize' ),
			'description' => __( 'Hide only the logout link inside the user info box.', 'adminimize' ),
			'options'     => array(
				0 => __( 'Show', 'adminimize' ),
				1 => __( 'Hide', 'adminimize' )
			),
			'css' => array(
				'action' => 'admin_print_styles',
				'style'  => '#wp-admin-bar-logout { display: none; }	
							 #wp-admin-bar-user-actions { min-height: 85px; }'
			)
		);

		$settings['ui_redirect'] = array(
			'title'       => __( 'Change User-Info, redirect to', 'adminimize' ),
			'description' => __('When the &quot;User-Info-area&quot; change it, then it is possible to change the redirect.', 'adminimize' ),
			'options'     => array(
				0 => __( 'Default', 'adminimize' ),
				1 => __( 'Frontpage of the Blog', 'adminimize' )
			),
			'disabled' => in_array( Adminimize\get_option('user_info'), array( '', '1', '0' ) )
		);

		$settings['footer'] = array(
			'title'       => __( 'Footer', 'adminimize' ),
			'description' => __( 'The Footer-area can hide, including all links and details.', 'adminimize' ),
			'options'     => array(
				0 => __( 'Show', 'adminimize' ),
				1 => __( 'Hide', 'adminimize' )
			),
			'callback' => function () {
				$nothing = function(){return '';};
				add_filter( 'admin_footer_text', $nothing, 100 );
				add_filter( 'update_footer', $nothing, 100 );
			}
		);

		$settings['header'] = array(
			'title'       => __( 'Header', 'adminimize' ),
			'description' => __( 'The Header-area can hide, including all links and details.', 'adminimize' ),
			'options'     => array(
				0 => __( 'Show', 'adminimize' ),
				1 => __( 'Hide', 'adminimize' )
			),
			'callback' => function() {
				// FIXME: not working in 3.4 ...
				add_filter( 'show_admin_bar', function(){return false;}, 1000 );
			}
		);

		$settings['timestamp'] = array(
			'title'       => __( 'Timestamp', 'adminimize' ),
			'description' => __( 'Opens the post timestamp editing fields without you having to click the "Edit" link every time.', 'adminimize' ),
			'options'     => array(
				0 => __( 'Default', 'adminimize' ),
				1 => __( 'Activate', 'adminimize' )
			),
			'callback' => function() {

				if ( ! Adminimize\is_edit_post_page() )
					return;

				wp_enqueue_script(
					'_adminimize_timestamp',
					Adminimize\plugins_url( "/js/timestamp" . Adminimize\script_suffix() . ".js" ),
					array( 'jquery' )
				);
			}
		);

		$settings['tb_window'] = array(
			'title'       => __( 'Thickbox FullScreen', 'adminimize' ),
			'description' => __( 'All Thickbox-function use the full area of the browser. Thickbox is for example in upload media-files.', 'adminimize' ),
			'options'     => array(
				0 => __( 'Default', 'adminimize' ),
				1 => __( 'Activate', 'adminimize' )
			),
			'callback' => function () {

				if ( ! Adminimize\is_edit_post_page() )
					return;

				wp_deregister_script( 'media-upload' );
				wp_enqueue_script(
					'media-upload',
					Adminimize\plugins_url( "/js/tb_window" . Adminimize\script_suffix() . ".js" ),
					array( 'thickbox' )
				);
			}
		);

		$settings['control_flashloader'] = array(
			'title'       => __( 'Flashuploader', 'adminimize' ),
			'description' => __( 'Disable the flashuploader and users use only the standard uploader.', 'adminimize' ),
			'options'     => array(
				0 => __( 'Default', 'adminimize' ),
				1 => __( 'Activate', 'adminimize' )
			),
			'callback' => function () {
				add_filter( 'flash_uploader', function(){return false;}, 1 );
			}
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
			<textarea style="width: 85%" class="code" rows="2" cols="60" name="adminimize_backend[advice_txt]" id="adminimize_backend_advice_txt" ><?php echo htmlspecialchars( stripslashes( Adminimize\get_option( 'advice_txt', '', 'adminimize_backend' ) ) ); ?></textarea>
			<?php
		} );

		$settings = $this->get_settings();

		foreach ( $settings as $settings_name => $settings_args ) {
			add_settings_field(
				/* $id,       */ 'adminimize_field_' . $settings_name,
				/* $title,    */ $settings_args['title'],
				/* $callback, */ '\\Inpsyde\\Adminimize\\field_with_select',
				/* $page      */ Adminimize\Options_Page::$pagehook,
				/* $section   */ 'backend-options',
				/* $args      */ array(
					'settings_name'      => $settings_name,
					'settings_namespace' => $this->get_option_namespace(),
					'args'               => $settings_args
				)
			);
		}

		?>
		<table summary="config" class="widefat">
			<tbody>
				<?php do_settings_fields( Adminimize\Options_Page::$pagehook, 'backend-options' ); ?>
			</tbody>
		</table>

		<br style="clear: both">
		<?php submit_button( __( 'Save Changes' ), 'button-primary', 'submit', TRUE ); ?>
		<br style="clear: both">
		<?php	
	}

}
