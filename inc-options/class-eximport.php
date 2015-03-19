<?php
/**
 * Aminimize - Ex- Import Part
 * 
 * @license    GPLv3
 * @subpackage Aminimize Settings
 * @author     Frank Bueltge <frank@bueltge.de>
 */

if ( ! function_exists( 'add_filter' ) ) {
	echo "Hi there!  I'm just a part of plugin, not much I can do when called directly.";
	exit;
}

if ( ! class_exists( 'Adminimize_Eximport' ) ) :
	
add_action( 'plugins_loaded', array( 'Adminimize_Eximport', 'get_object' ) );
class Adminimize_Eximport {
	
	protected static $classobj = NULL;
	
	// string for nonce fields
	static public    $nonce_string;
	
	public function __construct() {
		
		if ( ! is_admin() )
			return NULL;
		
		self::$nonce_string = '_mw_adminimize_nonce';
		
		if ( isset( $_GET['_mw_adminimize_export'] ) && check_admin_referer( self::$nonce_string ) )
			$this->get_export_file();
		
		if ( isset( $_POST['_mw_adminimize_import'] ) && check_admin_referer( self::$nonce_string ) )
			$this->import_file();
		
		add_action( 'mw_adminimize_after_settings_form', array( $this, 'get_im_export_part' ) );
	}

	/**
	 * Handler for the action 'init'. Instantiates this class.
	 *
	 * @access  public
	 * @since   02/15/2013
	 * @return Adminimize_Eximport|null $classobj
	 */
	public static function get_object() {
		
		if ( NULL === self::$classobj ) {
			self::$classobj = new self;
		}
		
		return self::$classobj;
	}
	
	/**
	 * get markup for ex- and import on settings page
	 * 
	 * @access  public
	 * @since   2.0.0
	 * @uses    wp_nonce_field
	 * @return  string
	 */
	public function get_im_export_part() {
		?>
		<div class="postbox">
			<h3><span><?php _e( 'Export', FB_ADMINIMIZE_TEXTDOMAIN ); ?></span></h3>
			<div class="inside">
				<p><?php _e( 'When you click the button below the plugin will create an XML file for you to save to your computer.', FB_ADMINIMIZE_TEXTDOMAIN ); ?></p>
				<p><?php _e( 'This format, a custom XML, will contain your options from quicktags.', FB_ADMINIMIZE_TEXTDOMAIN ); ?></p>
				<p><?php _e( 'Once you’ve saved the download file, you can use the Import function in another WordPress installation to import this site.', FB_ADMINIMIZE_TEXTDOMAIN ); ?></p>
				<form method="get" action="">
					<?php wp_nonce_field( self::$nonce_string ); ?>
					<p class="submit">
						<input type="submit" name="submit" value="<?php _e( 'Download Export File', FB_ADMINIMIZE_TEXTDOMAIN ); ?> &raquo;" />
						<input type="hidden" name="addquicktag_download" value="true" />
					</p>
				</form>
			</div>
		</div>
		
		<div class="postbox">
			<h3><span><?php _e( 'Import', FB_ADMINIMIZE_TEXTDOMAIN ); ?></span></h3>
			<div class="inside">
				<p><?php _e( 'If you have quicktags from other installs, the plugin can import those into this site. To get started, choose a file to import.', FB_ADMINIMIZE_TEXTDOMAIN ); ?></p>
				<form method="post" action="" enctype="multipart/form-data">
					<?php wp_nonce_field( self::$nonce_string ); ?>
					<p class="submit">
						<input type="file" name="xml" />
						<input type="submit" name="submit" value="<?php _e( 'Upload file and import', FB_ADMINIMIZE_TEXTDOMAIN ); ?> &raquo;" />
						<input type="hidden" name="addquicktag_import" value="true" />
					</p>
				</form>
			</div>
		</div>
		<?php
	}
	
	/*
	 * Build export file, xml
	 * 
	 * @access  public
	 * @since   2.0.0
	 * @uses    is_plugin_active_for_network, get_site_option, get_option
	 * @return  string $xml
	 */
	public function get_export_file() {
		
		if ( is_multisite() && is_plugin_active_for_network( parent :: get_plugin_string() ) )
			$options = get_site_option( parent :: get_option_string() );
		else
			$options = get_option( parent :: get_option_string() );
		
		if ( $options['buttons'] ) {
			
			$xml  = '<?xml version="1.0" encoding="UTF-8"?>';
			$xml .= "\n" . '<buttons>' . "\n";
			
			for ( $i = 0; $i < count( $options['buttons'] ); $i++ ) {
				$xml .= "\t" . '<quicktag>' . "\n";
				foreach( $options['buttons'][$i] as $name => $value ) {
					
					$value = stripslashes( $value );
					
					if ( empty( $value ) ) {
						$xml .= "\t\t" . '<' . $name . '/>' . "\n";
					} elseif ( preg_match( '/^[0-9]*$/', $value ) ) {
						$xml .= "\t\t" . '<' . $name . '>' . $value . '</' . $name . '>' . "\n";
					} else {
						$xml .= "\t\t" . '<' . $name . '><![CDATA[' . $value . ']]></' . $name . '>' . "\n";
					}
				}
				$xml .= "\t" . '</quicktag>' . "\n";
			}
			$xml .= '</buttons>';
			
		} else {
			$xml = 'We dont find settings in database';
		}
		
		$filename = urlencode( 'addquicktag.' . date('Y-m-d') . '.xml' );
		$filesize = strlen( $xml );
		
		$this -> export_xml( $filename, $filesize, $filetype = 'text/xml' );
		echo $xml;
		exit;
	}
	
	/**
	 * Create download file
	 * 
	 * @access  public
	 * @since   2.0.0
	 * @param   string $filename
	 * @param   string $filesize
	 * @param   string $filetype
	 * @uses    get_option
	 * @return  void
	 */
	public function export_xml( $filename, $filesize, $filetype ) {
		
		header( 'Content-Description: File Transfer' );
		header( 'Content-Disposition: attachment; filename=' . $filename );
		header( 'Content-Length: ' . $filesize );
		header( 'Content-type: ' . $filetype . '; charset=' . get_option('blog_charset'), TRUE );
		flush();
	}

	/**
	 * Import XML and update settings
	 *
	 * @access  public
	 * @since   2.0.0
	 *
	 * @param bool|string $filename
	 *
	 * @uses    current_user_can, wp_die, is_plugin_active_for_network, update_site_option, update_option
	 */
	public function import_file( $filename = FALSE ) {
		
		if ( ! current_user_can( 'manage_options' ) )
			wp_die( __('Options not update - you don&lsquo;t have the privilidges to do this!', FB_ADMINIMIZE_TEXTDOMAIN ) );
		
		// use tmp file
		if ( ! $filename )
			$filename = $_FILES['xml']['tmp_name'];
		
		$filename = preg_replace( "/\<\!\[CDATA\[(.*?)\]\]\>/ies", "'[CDATA]' . base64_encode('$1') . '[/CDATA]'", $filename );
		$filename = utf8_encode( $filename );
		$matches  = simplexml_load_file( $filename );
		
		// create array from xml
		$button = array();
		foreach ( $matches -> quicktag as $key ) {
			foreach ($key as $value) {
				$buttons[$value -> getName()] = $value;
			}
			$button[] = $buttons;
		}
		$options['buttons'] = $button;
		// validate the values from xml
		$options = parent :: validate_settings($options);
		
		// update settings in database
		if ( is_multisite() && is_plugin_active_for_network( parent :: get_plugin_string() ) )
			update_site_option( parent :: get_option_string(), $options );
		else
			update_option( parent :: get_option_string(), $options );
	}
	
} // end class

endif;
