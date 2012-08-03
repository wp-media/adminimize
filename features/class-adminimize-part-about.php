<?php 
namespace Adminimize\Part;

require_once 'class-adminimize-part-base-meta-box.php';

/**
 * Options to hide menu entries.
 */
class About_Options extends \Adminimize\Part\Base_Meta_Box {

	/**
	 * Meta Box priority within the context where the boxes should show.
	 * 
	 * 'high', 'core', 'default' or 'low'
	 * 
	 * @var string
	 */
	protected $priority = 'high';

	/**
	 * Get translated meta box title.
	 * 
	 * @return string
	 */
	public function get_meta_box_title() {
		return __( 'About the Plugin', 'adminimize' );
	}

	/**
	 * Get option namespace.
	 *
	 * Will be used to serialize settings.
	 * 
	 * @return string
	 */
	public function get_option_namespace() {
		return NULL;
	}

	/**
	 * Populate $settings var with data.
	 * 
	 * @return void
	 */
	protected function init_settings() {
		$this->settings = NULL;
	}

	/**
	 * Print meta box contents.
	 * 
	 * @return void
	 */
	public function meta_box_content() {
		?>
		<p style="float: left; margin: 0px 15px 5px 0px">
			<a href="http://www.inpsyde.com" target="_blank">
				<img src="<?php echo plugin_dir_url( __FILE__ ) . '../images/inpsyde_logo.png' ?>" style="border: 7px solid #fff">
			</a>
		</p>
		<p>
			<?php 
			echo _mw_adminimize_get_plugin_data( 'Title' ) . ' ';
			echo __( 'Version', FB_ADMINIMIZE_TEXTDOMAIN ) . ' ';
			echo _mw_adminimize_get_plugin_data( 'Version' );
			?>
		</p>
		<p>
			<?php 
			echo _mw_adminimize_get_plugin_data( 'Description' );
			?>
		</p>
		<?php
	}

}

About_Options::get_instance();
