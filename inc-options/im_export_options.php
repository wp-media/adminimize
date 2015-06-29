<?php
/**
 * @package    Adminimize
 * @subpackage Im/Export options
 * @author     Frank Bültge
 */
if ( ! function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a part of plugin, not much I can do when called directly.";
	exit;
}
?>
<div id="poststuff" class="ui-sortable meta-box-sortables">
	<div class="postbox">
		<div class="handlediv" title="<?php _e( 'Click to toggle' ); ?>"><br /></div>
		<h3 class="hndle" id="import"><?php _e( 'Export/Import Options', FB_ADMINIMIZE_TEXTDOMAIN ) ?></h3>

		<div class="inside">
			<br class="clear" />

			<h4><?php _e( 'Export', FB_ADMINIMIZE_TEXTDOMAIN ) ?></h4>

			<form name="export_options" method="get" action="">
				<p><?php _e(
						'You can save a JSON formatted ".json" file with your settings.', FB_ADMINIMIZE_TEXTDOMAIN
					) ?></p>

				<p>
					<?php wp_nonce_field( 'mw_adminimize_export_nonce', 'mw_adminimize_export_nonce' ); ?>
					<input type="hidden" name="_mw_adminimize_export" value="true" />
					<?php
					$submit_text = esc_html__( 'Export &raquo;', FB_ADMINIMIZE_TEXTDOMAIN );
					submit_button(
						$text = $submit_text, $type = 'primary', $name = '_mw_adminimize_save', $wrap = FALSE,
						$other_attributes = NULL
					);
					?>
				</p>
			</form>

			<h4><?php _e( 'Import', FB_ADMINIMIZE_TEXTDOMAIN ) ?></h4>

			<form name="import_options" enctype="multipart/form-data" method="post" action="?page=<?php echo esc_attr(
				$_GET[ 'page' ]
			); ?>">
				<?php wp_nonce_field( 'mw_adminimize_nonce' ); ?>
				<p><?php _e(
						'Choose a Adminimize (<em>.json</em>) file to upload, then click <em>Upload file and import</em>.',
						FB_ADMINIMIZE_TEXTDOMAIN
					);
					esc_html_e( 'After import please reload the page to display also all global values from WordPress.', FB_ADMINIMIZE_TEXTDOMAIN ); ?>
				</p>

				<p>
					<label for="datei_id">
						<?php esc_html_e(
							'Choose a ".json" file from your computer:', FB_ADMINIMIZE_TEXTDOMAIN
						) ?>
					</label>
					<input name="import_file" id="datei_id" type="file" />
				</p>

				<p>
					<?php wp_nonce_field( 'mw_adminimize_import_nonce', 'mw_adminimize_import_nonce' ); ?>
					<input type="hidden" name="_mw_adminimize_action" value="_mw_adminimize_import" />
					<?php
					$submit_text = esc_html__( 'Upload file and import &raquo;', FB_ADMINIMIZE_TEXTDOMAIN );
					submit_button(
						$text = $submit_text, $type = 'primary', $name = '_mw_adminimize_save', $wrap = FALSE,
						$other_attributes = NULL
					);
					?>
				</p>
			</form>

			<p>
				<a class="alignright button" href="javascript:void(0);" onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;">
					<?php esc_html_e(
						'scroll to top', FB_ADMINIMIZE_TEXTDOMAIN
					); ?>
				</a><br class="clear" />
			</p>

		</div>
	</div>
</div>
		
