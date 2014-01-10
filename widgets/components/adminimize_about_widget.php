<?php
/**
 * About widget
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

if ( ! class_exists( 'Adminimize_About_Widget' ) ) {

class Adminimize_About_Widget extends Adminimize_Base_Widget implements I_Adminimize_Widget
{

	public function get_attributes() {

		return array(
				'id'            => 'about_widget',
				'title'         => __( 'About the plugin', $this->pluginheaders->TextDomain ),
// 				'callback'      => array( $this, 'content' ),
// 				'post_type'     => '',
				'context'       => 1,				// the column NUMBER, not the column name!!
				'priority'      => 'low', 	// 'high', 'core', 'default' or 'low'
				'callback_args' => array()
		);

	}

	/**
	 * About the plugin
	 * Returns the widget content
	 */
	public function content() {

?>
	<p><?php echo $this->pluginheaders->Title; echo ' '; _e( 'Version', $this->pluginheaders->TextDomain ); echo ' '; echo $this->pluginheaders->Version ?></p>
	<p><?php echo $this->pluginheaders->Description ?></p>
	<ul>
		<li><?php _e( 'Further information: Visit the <a href="http://wordpress.org/extend/plugins/adminimize/">plugin homepage</a> for further information or to grab the latest version of this plugin. Also see the <a href="http://wordpress.org/support/plugin/adminimize">support forum</a> for questions.', $this->pluginheaders->TextDomain ); ?></li>
		<li>
			<?php _e( 'You want to thank me? Visit my <a href="http://bueltge.de/wunschliste/">wishlist</a> or donate.', $this->pluginheaders->TextDomain ); ?>
			<span>
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="4578111">
				<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="<?php _e( 'PayPal - The safer, easier way to pay online!', $this->pluginheaders->TextDomain ); ?>">
				<img alt="" border="0" src="https://www.paypal.com/de_DE/i/scr/pixel.gif" width="1" height="1">
			</form>
		</li>
	</ul>
	<div class="form-invalid" style="padding:.3em 1em;">
		<p><span style="font-size: 35px; float: left; margin: 10px 3px 0 0;">&#x261D;</span><?php _e( 'Please note: The Adminimize settings page ignores the Menu Options below and displays the menu with all entries.<br /><span style="font-weight: 300;">To view your changes to the menu you need to navigate away from the Adminimize settings page.</span>', $this->pluginheaders->TextDomain ); ?></p>
		<?php if ( is_multisite() && is_plugin_active_for_network( $this->storage->MW_ADMIN_FILE ) ) { ?>
		<p><?php _e( 'You have to activated the Plugin for your Multisite Network. Your settings works now on all blogs in the network. Please set the settings only in one blog, there you have all active menu items and plugins. If you update the settings then write the plugin new settings in dependence of the blog where you put, save the settings.', $this->pluginheaders->TextDomain ); ?></p>
		<?php } ?>
	</div>
	<p>&copy; Copyright 2008 - <?php echo date('Y'); ?> <a href="http://bueltge.de">Frank B&uuml;ltge</a></p>
	<p><a class="alignright button" href="javascript:void(0);" onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;"><?php _e('scroll to top', $this->pluginheaders->TextDomain); ?></a><br class="clear" /></p>
<?php

	}

}

}