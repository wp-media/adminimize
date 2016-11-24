<?php
/**
 * @package    Adminimize
 * @subpackage Uninstall options
 * @author     Frank Bültge
 */
if ( ! function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a part of plugin, not much I can do when called directly.";
	exit;
}
?>
<div id="poststuff" class="ui-sortable meta-box-sortables">
	<div class="postbox">
		<div class="handlediv" title="<?php esc_attr_e( 'Click to toggle', 'adminimize' ); ?>"><br /></div>
		<h3 class="hndle" id="uninstall"><?php esc_attr_e( 'Uninstall Options', 'adminimize' ) ?></h3>
		<div class="inside">

			<p><?php _e(
					'Use this option for clean your database from all entries of this plugin. When you deactivate the plugin, the deinstall of the plugin <strong>clean not</strong> all entries in the database.',
					'adminimize'
				); ?></p>
			<form name="deinstall_options" method="post" id="_mw_adminimize_options_deinstall" action="?page=<?php echo esc_attr(
				$_GET[ 'page' ]
			); ?>">
				<?php wp_nonce_field( 'mw_adminimize_nonce' ); ?>
				<p id="submitbutton">
					<input type="submit" name="_mw_adminimize_uninstall" value="<?php esc_attr_e(
						'Delete Options', 'adminimize'
					); ?> &raquo;" class="button-secondary" />
					<input type="checkbox" name="_mw_adminimize_uninstall_yes" value="_mw_adminimize_uninstall" />
					<input type="hidden" name="_mw_adminimize_action" value="_mw_adminimize_uninstall" />
				</p>
			</form>
			<p>
				<a class="alignright button" href="javascript:void(0);" onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;"><?php
					esc_attr_e( 'scroll to top', 'adminimize' ); ?></a><br class="clear" />
			</p>

		</div>
	</div>
</div>

