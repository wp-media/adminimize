<?php
/**
 * @package Adminimize
 * @subpackage Im/Export options
 * @author Frank Bültge
 */
if ( ! function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a part of plugin, not much I can do when called directly.";
	exit;
}
?>
		<div id="poststuff" class="ui-sortable meta-box-sortables">
			<div class="postbox">
				<div class="handlediv" title="<?php _e('Click to toggle'); ?>"><br/></div>
				<h3 class="hndle" id="import"><?php _e('Export/Import Options', FB_ADMINIMIZE_TEXTDOMAIN ) ?></h3>
				<div class="inside">
					<br class="clear" />
					
					<h4><?php _e('Export', FB_ADMINIMIZE_TEXTDOMAIN ) ?></h4>
					<form name="export_options" method="get" action="">
						<p><?php _e('You can save a .seq file with your options.', FB_ADMINIMIZE_TEXTDOMAIN ) ?></p>
						<p id="submitbutton">
							<input type="hidden" name="_mw_adminimize_export" value="true" />
							<input type="submit" name="_mw_adminimize_save" value="<?php _e('Export &raquo;', FB_ADMINIMIZE_TEXTDOMAIN ) ?>" class="button" />
						</p>
					</form>
					
					<h4><?php _e('Import', FB_ADMINIMIZE_TEXTDOMAIN ) ?></h4>
					<form name="import_options" enctype="multipart/form-data" method="post" action="?page=<?php echo esc_attr( $_GET['page'] ); ?>">
						<?php wp_nonce_field('mw_adminimize_nonce'); ?> 
						<p><?php _e('Choose a Adminimize (<em>.seq</em>) file to upload, then click <em>Upload file and import</em>.', FB_ADMINIMIZE_TEXTDOMAIN ) ?></p>
						<p>
							<label for="datei_id"><?php _e('Choose a file from your computer', FB_ADMINIMIZE_TEXTDOMAIN ) ?>: </label>
							<input name="datei" id="datei_id" type="file" />
						</p>
						<p id="submitbutton">
							<input type="hidden" name="_mw_adminimize_action" value="_mw_adminimize_import" />
							<input type="submit" name="_mw_adminimize_save" value="<?php _e('Upload file and import &raquo;', FB_ADMINIMIZE_TEXTDOMAIN ) ?>" class="button" />
						</p>
					</form>
					<p><a class="alignright button" href="javascript:void(0);" onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;"><?php _e('scroll to top', FB_ADMINIMIZE_TEXTDOMAIN); ?></a><br class="clear" /></p>
					
				</div>
			</div>
		</div>
		
