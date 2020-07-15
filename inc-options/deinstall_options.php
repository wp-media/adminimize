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
		<h3 class="hndle ui-sortable-handle" title="<?php esc_attr_e( 'Click to toggle', 'adminimize' ); ?>"  id="uninstall"><?php esc_attr_e( 'Uninstall Options', 'adminimize' ) ?></h3>
		<div class="inside">

			<p><?php _e(
					'Use this option to clean your database from all the entries created by this plugin. Deactivating or uninstalling the plugin <strong>will not</strong> clean the database entries.',
					'adminimize'
				); // Some grammer correction ?></p>
			<form name="deinstall_options" method="post" id="_mw_adminimize_options_deinstall" action="?page=<?php echo esc_attr(
				$_GET[ 'page' ]
			); ?>">
				<?php wp_nonce_field( 'mw_adminimize_nonce' ); ?>
				<p id="submitbutton">
					<input id="_mw_adminimize_uninstall_yes" type="checkbox" name="_mw_adminimize_uninstall_yes" value="_mw_adminimize_uninstall" /><label for="_mw_adminimize_uninstall_yes"><?php esc_html_e( 'Yes, I know the risks.','adminimize' ) ?><br class="clear"></label>
					<input style="margin-top:15px" type="submit" name="_mw_adminimize_uninstall" value="<?php esc_attr_e( 'Delete Options', 'adminimize' ); ?> &raquo;" class="button-secondary" />
					<input type="hidden" name="_mw_adminimize_action" value="_mw_adminimize_uninstall" />
				</p>
			</form>
			<p>
                <a class="alignright button adminimize-scroltop" href="#" style="margin:3px 0 0 30px;">
                    <?php esc_attr_e( 'scroll to top', 'adminimize' ); ?>
                </a><br class="clear" />
			</p>

		</div>
	</div>
</div>

