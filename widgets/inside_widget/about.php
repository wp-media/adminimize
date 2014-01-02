<?php
$dc = new Adminimize_Data_Container();
$plugindata = PluginHeaderReader::get_instance( 'adminimize' );
?>
					<p><?php echo $plugindata->Title; echo ' '; _e( 'Version', ADMINIMIZE_TEXTDOMAIN ); echo ' '; echo $plugindata->Version ?></p>
					<p><?php echo $plugindata->Description ?></p>
					<ul>
						<li><?php _e( 'Further information: Visit the <a href="http://wordpress.org/extend/plugins/adminimize/">plugin homepage</a> for further information or to grab the latest version of this plugin. Also see the <a href="http://wordpress.org/support/plugin/adminimize">support forum</a> for questions.', ADMINIMIZE_TEXTDOMAIN ); ?></li>
						<li>
							<?php _e( 'You want to thank me? Visit my <a href="http://bueltge.de/wunschliste/">wishlist</a> or donate.', ADMINIMIZE_TEXTDOMAIN ); ?>
							<span>
								<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
								<input type="hidden" name="cmd" value="_s-xclick">
								<input type="hidden" name="hosted_button_id" value="4578111">
								<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="<?php _e( 'PayPal - The safer, easier way to pay online!', ADMINIMIZE_TEXTDOMAIN ); ?>">
								<img alt="" border="0" src="https://www.paypal.com/de_DE/i/scr/pixel.gif" width="1" height="1">
							</form>
						</li>
					</ul>
					<div class="form-invalid" style="padding:.3em 1em;">
						<p><span style="font-size: 35px; float: left; margin: 10px 3px 0 0;">&#x261D;</span><?php _e( 'Please note: The Adminimize settings page ignores the Menu Options below and displays the menu with all entries.<br /><span style="font-weight: 300;">To view your changes to the menu you need to navigate away from the Adminimize settings page.</span>', ADMINIMIZE_TEXTDOMAIN ); ?></p>
						<?php if ( is_multisite() && is_plugin_active_for_network( $dc->get( 'MW_ADMIN_FILE' ) ) ) { ?>
						<p><?php _e( 'You have to activated the Plugin for your Multisite Network. Your settings works now on all blogs in the network. Please set the settings only in one blog, there you have all active menu items and plugins. If you update the settings then write the plugin new settings in dependence of the blog where you put, save the settings.', ADMINIMIZE_TEXTDOMAIN ); ?></p>
						<?php } ?>
					</div>
					<p>&copy; Copyright 2008 - <?php echo date('Y'); ?> <a href="http://bueltge.de">Frank B&uuml;ltge</a></p>
					<p><a class="alignright button" href="javascript:void(0);" onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;"><?php _e('scroll to top', ADMINIMIZE_TEXTDOMAIN); ?></a><br class="clear" /></p>
<?php
