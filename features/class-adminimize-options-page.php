<?php
/**
 * Feature Name:	Options Page
 * Version:			1.0
 * Author:			Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

if ( ! class_exists( 'Adminimize_Options_Page' ) ) {

	class Adminimize_Options_Page {

		/**
		 * Tab holder
		 *
		 * @since	0.1
		 * @access	public
		 * @var		array
		 */
		public $tabs = array();
		
		/**
		 * Instance holder
		 *
		 * @since	0.1
		 * @access	private
		 * @static
		 * @var		NULL | Adminimize_Options_Page
		 */
		private static $instance = NULL;

		private static $option_string;

		public static $pagehook;

		/**
		 * Instance of main plugin
		 * 
		 * @var Adminimize
		 */
		private $plugin;
		
		/**
		 * Method for ensuring that only one instance of this object is used
		 *
		 * @since	0.1
		 * @access	public
		 * @static
		 * @return	Adminimize_Options_Page
		 */
		public static function get_instance() {
				
			if ( ! self::$instance )
				self::$instance = new self;
				
			return self::$instance;
		}
		
		/**
		 * Setting up some data, initialize translations and start the hooks
		 *
		 * @since	0.1
		 * @access	public
		 * @uses	is_admin, add_filter
		 * @return	void
		 */
		public function __construct() {

			if ( ! is_admin() )
				return;
			
			add_action( 'init', array( $this, 'init' ) );
		}

		public function init() {

			self::$option_string = 'adminimize';

			if ( $this->plugin->is_active_for_multisite() ) {
				file_put_contents('/tmp/php.log', print_r("\n" . "Adminimize is active for THE NETWORK", true), FILE_APPEND | LOCK_EX);
				add_action( 'network_admin_menu',    array( $this, 'add_network_options_page' ) );
				// add settings link
				add_filter( 'network_admin_plugin_action_links', array( $this, 'network_admin_plugin_action_links' ), 10, 2 );
				// save settings on network
				add_action( 'network_admin_edit_' . self::$option_string, array( $this, 'save_network_settings_page' ) );
				// return message for update settings
				add_action( 'network_admin_notices', array( $this, 'get_network_admin_notices' ) );
			} else {
				file_put_contents('/tmp/php.log', print_r("\n" . "Adminimize is active for a single blog", true), FILE_APPEND | LOCK_EX);
				add_action( 'admin_menu',            array( $this, 'add_options_page' ) );
				// add settings link
				add_filter( 'plugin_action_links',   array( $this, 'plugin_action_links' ), 10, 2 );
				// use settings API
				add_action( 'admin_init',            array( $this, 'register_settings' ) );
			}
		}

		/**
		 * Set instance of main plugin.
		 * 
		 * @param Adminimize $plugin
		 */
		public function set_plugin( Adminimize $plugin ) {
			$this->plugin = $plugin;
		}
		
		public function add_network_options_page( $value='' ) {
			self::$pagehook = add_submenu_page(
				'settings.php',
				$this->plugin->get_plugin_header( 'Name' ) . ' ' . __( 'Settings' ),
				$this->plugin->get_plugin_header( 'Name' ),
				'manage_options',
				$this->plugin->get_plugin_basename(),
				array( $this, 'get_settings_page' )
			);

			add_action( 'load-' . self::$pagehook, array( $this, 'prepare_dragndrop' ) );
		}
		
		public function add_options_page() {
			self::$pagehook = add_options_page(
				$this->plugin->get_plugin_header( 'Name' ) . ' ' . __( 'Settings' ),
				$this->plugin->get_plugin_header( 'Name' ),
				'manage_options',
				$this->plugin->get_plugin_basename(),
				array( $this, 'get_settings_page' )
			);

			add_action( 'load-' . self::$pagehook, array( $this, 'prepare_dragndrop' ) );
		}

		public function prepare_dragndrop() {
			wp_enqueue_script( 'postbox' );
			add_screen_option( 'layout_columns', array( 'max' => 1, 'default' => 1 ) );
		}

		public function network_admin_plugin_action_links( $links, $file ) {
			$plugin_basename = $this->plugin->get_plugin_basename();

			if ( $plugin_basename == $file  )
				$links[] = '<a href="settings.php?page=' . $plugin_basename . '">' . __( 'Settings' ) . '</a>';

			return $links;
		}

		public function save_network_settings_page() {

			if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], self::$pagehook ) )
				wp_die( 'Sorry, you failed the nonce test.' );

			// update options
			update_site_option( 'adminimize', $_POST['adminimize'] );

			// redirect to settings page in network
			wp_redirect(
				add_query_arg(
					array( 'page' => $this->plugin->get_plugin_basename(), 'updated' => 'true' ),
					network_admin_url( 'settings.php' )
				)
			);
			exit();
		}

		public function get_network_admin_notices() {
			if ( isset( $_GET['updated'] ) && stripos( $GLOBALS['current_screen']->id, 'adminimize' ) ) {
				?>
				<div id="message" class="updated">
					<p>
						<strong><?php echo __( 'Settings saved.', 'adminimize' ); ?></strong>
					</p>
				</div>
				<?php
			}
		}

		public function plugin_action_links( $links, $file ) {

			$plugin_basename = $this->plugin->get_plugin_basename();

			if ( $plugin_basename == $file  )
				$links[] = '<a href="options-general.php?page=' . $plugin_basename . '">' . __( 'Settings' ) . '</a>';

			return $links;
		}
		
		public function register_settings() {
			register_setting( Adminimize_Options_Page::$pagehook, 'adminimize' );
		}

		public function get_settings_page() {

			if ( $this->plugin->is_active_for_multisite() )
				$form_action = 'edit.php?action=' . self::$option_string;
			else
				$form_action = 'options.php';

			?>
			<div class="wrap">
				<?php screen_icon('options-general'); ?>
				<h2><?php echo $this->plugin->get_plugin_header( 'Name' ); ?></h2>

				<form method="post" action="<?php echo $form_action; ?>">

					<?php if ( $this->plugin->is_active_for_multisite() ): ?>
						<?php wp_nonce_field( self::$pagehook ); ?>
					<?php else: ?>
						<?php settings_fields( self::$pagehook ); ?>
					<?php endif ?>
					
					<?php do_settings_fields( self::$pagehook, 'default' ); ?>

					<div class="metabox-holder has-right-sidebar">
						
						<div class="inner-sidebar">
							<?php do_meta_boxes( self::$pagehook, 'side', array() ); ?>
						</div> <!-- .inner-sidebar -->
						
						<div id="post-body">
							<div id="post-body-content">
								<?php do_meta_boxes( self::$pagehook, 'normal', array() ); ?>
								<?php do_meta_boxes( self::$pagehook, 'advanced', array() ); ?>
							</div> <!-- #post-body-content -->
						</div> <!-- #post-body -->
						
					</div> <!-- .metabox-holder -->

				</form>

				<!-- Stuff for opening / closing metaboxes -->
				<script type="text/javascript">
				jQuery( document ).ready( function( $ ) {
					// close postboxes that should be closed
					$('.if-js-closed').removeClass( 'if-js-closed' ).addClass('closed');
					// postboxes setup
					postboxes.add_postbox_toggles( '<?php echo self::$pagehook; ?>' );
				} );
				</script>
				
				<form style='display: none' method='get' action=''>
					<?php
					wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
					wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
					?>
				</form>
			</div>
			<?php
		}
	}
}

// Kickoff
if ( function_exists( 'add_filter' ) ) {
	Adminimize_Options_Page::get_instance()->set_plugin( Adminimize::get_instance() );
}
?>