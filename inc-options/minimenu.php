<?php
/**
 * @package     Adminimize
 * @subpackage  Menu on settings page
 * @author      Frank Bültge
 */
if ( ! function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a part of plugin, not much I can do when called directly.";
	exit;
}

$screen = get_current_screen();
$message = '';
if ( is_multisite() && is_plugin_active_for_network( plugin_basename( MW_ADMIN_FILE ) ) )
	$message = __( 'Network' );
?>

		<h2><?php esc_attr_e('Adminimize', FB_ADMINIMIZE_TEXTDOMAIN ); echo ' ' . $message; ?></h2>
		<br class="clear" />
		<div id="poststuff" class="ui-sortable meta-box-sortables">
			<div id="minimeu" class="postbox ">
				<div class="handlediv" title="<?php esc_attr_e('Click to toggle'); ?>"><br/></div>
				<h3 class="hndle" id="menu"><?php esc_attr_e('MiniMenu', FB_ADMINIMIZE_TEXTDOMAIN ) ?></h3>
				<div class="inside">
					<table class="widefat" cellspacing="0">
						<tr>
							<td class="row-title"><a href="#about"><?php esc_attr_e('About the plugin', FB_ADMINIMIZE_TEXTDOMAIN ); ?></a></td>
						</tr>
						
						<tr class="alternate">
							<td class="row-title"><a href="#admin_bar_options"><?php esc_attr_e('Admin Bar Options', FB_ADMINIMIZE_TEXTDOMAIN ); ?> <em>&middot; Beta</em></a></td>
						</tr>
						
						<tr class="alternate">
							<td class="row-title"><a href="#backend_options"><?php esc_attr_e('Backend Options', FB_ADMINIMIZE_TEXTDOMAIN ); ?></a></td>
						</tr>
						<tr>
							<td class="row-title"><a href="#global_options"><?php esc_attr_e('Global options', FB_ADMINIMIZE_TEXTDOMAIN ); ?></a></td>
						</tr>
						<tr>
							<td class="row-title"><a href="#dashboard_options"><?php esc_attr_e('Dashboard options', FB_ADMINIMIZE_TEXTDOMAIN ); ?></a></td>
						</tr>
						<tr class="alternate">
							<td class="row-title"><a href="#config_menu"><?php esc_attr_e('Menu Options', FB_ADMINIMIZE_TEXTDOMAIN ); ?></a></td>
						</tr>
						<tr>
							<td class="row-title"><a href="#config_edit_post"><?php esc_attr_e('Write options - Post', FB_ADMINIMIZE_TEXTDOMAIN ); ?></a></td>
						</tr>
						<tr class="alternate">
							<td class="row-title"><a href="#config_edit_page"><?php esc_attr_e('Write options - Page', FB_ADMINIMIZE_TEXTDOMAIN ); ?></a></td>
						</tr>
						<?php 
						if ( function_exists( 'get_post_types' ) ) {
							$args = array( 'public' => TRUE, '_builtin' => FALSE );
							foreach ( get_post_types( $args ) as $post_type) {
								$post_type_object = get_post_type_object($post_type);
								?>
								<tr class="form-invalid">
									<td class="row-title">
										<a href="#config_edit_<?php echo $post_type; ?>">
										<?php esc_attr_e('Write options', FB_ADMINIMIZE_TEXTDOMAIN ); echo ' - ' . $post_type_object->label ?>
										</a>
									</td>
								</tr>
								<?php
							}
						}
						
						// check for active links, acive since WP 3.5
						if ( 0 != get_option( 'link_manager_enabled' ) ) {
						?>
						<tr>
							<td class="row-title"><a href="#links_options"><?php esc_attr_e('Links options', FB_ADMINIMIZE_TEXTDOMAIN ); ?></a></td>
						</tr>
						<?php } ?>
						
						<tr class="alternate">
							<td class="row-title"><a href="#widget_options"><?php esc_attr_e('Widgets', FB_ADMINIMIZE_TEXTDOMAIN ); ?></a></td>
						</tr>
						<tr class="alternate">
							<td class="row-title"><a href="#nav_menu_options"><?php esc_attr_e('WP Nav Menu', FB_ADMINIMIZE_TEXTDOMAIN ); ?></a></td>
						</tr>
						<tr>
							<td class="row-title"><a href="#set_theme"><?php esc_attr_e('Set Theme', FB_ADMINIMIZE_TEXTDOMAIN ); ?></a></td>
						</tr>
						<tr class="alternate">
							<td class="row-title"><a href="#import"><?php esc_attr_e('Export/Import Options', FB_ADMINIMIZE_TEXTDOMAIN ); ?></a></td>
						</tr>
						<tr>
							<td class="row-title"><a href="#uninstall"><?php esc_attr_e('Deinstall Options', FB_ADMINIMIZE_TEXTDOMAIN ); ?></a></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		
		<div id="poststuff" class="ui-sortable meta-box-sortables">
			<div id="about" class="postbox ">
				<div class="handlediv" title="<?php esc_attr_e('Click to toggle'); ?>"><br/></div>
				<h3 class="hndle" id="about-sidebar"><?php esc_attr_e('About the plugin', FB_ADMINIMIZE_TEXTDOMAIN ) ?></h3>
				<div class="inside">
					<p><?php echo _mw_adminimize_get_plugin_data( 'Title' ); echo ' '; esc_attr_e( 'Version', FB_ADMINIMIZE_TEXTDOMAIN ); echo ' '; echo _mw_adminimize_get_plugin_data( 'Version' ) ?></p>
					<p><?php echo _mw_adminimize_get_plugin_data( 'Description' ) ?></p>
					<ul>
						<li><?php _e( 'Further information: Visit the <a href="http://wordpress.org/extend/plugins/adminimize/">plugin homepage</a> for further information or to grab the latest version of this plugin. Also see the <a href="http://wordpress.org/support/plugin/adminimize">support forum</a> for questions.', FB_ADMINIMIZE_TEXTDOMAIN ); ?></li>
						<li><?php esc_attr_e( 'Report a issue on the development repository:', FB_ADMINIMIZE_TEXTDOMAIN ); ?> <a href="https://github.com/bueltge/Adminimize/issues">issues</a></li>
						<li><?php esc_attr_e( 'The plugin have a github repository to easy add a issue or a create a fork, pull request:', FB_ADMINIMIZE_TEXTDOMAIN ); ?> <a href="https://github.com/bueltge/Adminimize">github.com/bueltge/Adminimize</a></li>
						<li>
							<?php _e( 'You want to thank me? Visit my <a href="http://bueltge.de/wunschliste/">wishlist</a> or donate.', FB_ADMINIMIZE_TEXTDOMAIN ); ?>
							<span>
								<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
								<input type="hidden" name="cmd" value="_s-xclick">
								<input type="hidden" name="hosted_button_id" value="4578111">
								<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="<?php esc_attr_e( 'PayPal - The safer, easier way to pay online!', FB_ADMINIMIZE_TEXTDOMAIN ); ?>">
								<img alt="" border="0" src="https://www.paypal.com/de_DE/i/scr/pixel.gif" width="1" height="1">
							</form>
						</li>
					</ul>
					<div class="form-invalid" style="padding:.3em 1em;">
						<p><span style="font-size: 35px; float: left; margin: 10px 3px 0 0;">&#x261D;</span><?php esc_attr_e( 'Please note: The Adminimize settings page ignores the Menu Options below and displays the menu with all entries.<br /><span style="font-weight: 300;">To view your changes to the menu you need to navigate away from the Adminimize settings page.</span>', FB_ADMINIMIZE_TEXTDOMAIN ); ?></p>
						<?php if ( is_multisite() && is_plugin_active_for_network( MW_ADMIN_FILE ) ) { ?>
						<p><?php esc_attr_e( 'You have to activated the Plugin for your Multisite Network. Your settings works now on all blogs in the network. Please set the settings only in one blog, there you have all active menu items and plugins. If you update the settings then write the plugin new settings in dependence of the blog where you put, save the settings.', FB_ADMINIMIZE_TEXTDOMAIN ); ?></p>
						<?php } ?>
					</div>
					<p>&copy; Copyright 2008 - <?php echo date('Y'); ?> <a href="http://bueltge.de">Frank B&uuml;ltge</a></p>
					<p><a class="alignright button" href="javascript:void(0);" onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;"><?php esc_attr_e('scroll to top', FB_ADMINIMIZE_TEXTDOMAIN); ?></a><br class="clear" /></p>
				</div>
			</div>
		</div>
		