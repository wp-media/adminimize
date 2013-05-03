<?php
namespace Inpsyde\Adminimize\Partial;
use Inpsyde\Adminimize;

/**
 * Options to hide menu entries.
 */
class About extends Base
{

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

		$plugin = Adminimize\Adminimize::get_instance();
		?>
		<p id="adminimizer_about_logo">
			<a href="http://www.inpsyde.com" target="_blank">
				<img src="<?php echo plugin_dir_url( __FILE__ ) . '../../../images/inpsyde_logo.png' ?>" style="border: 7px solid #fff">
			</a>
		</p>
		<p>
			<?php
			echo $plugin->get_plugin_header( 'Title' ) . ' ';
			echo __( 'Version', 'adminimize' ) . ' ';
			echo $plugin->get_plugin_header( 'Version' );
			?>
		</p>
		<p>
			<?php
			echo $plugin->get_plugin_header( 'Description' );
			?>
		</p>
		<?php
	}

}
