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
		<div class="handlediv" title="<?php esc_attr_e( 'Click to toggle', 'adminimize' ); ?>"><br /></div>
		<h3 class="hndle" id="import"><?php esc_attr_e( 'Export/Import Options', 'adminimize' ) ?></h3>

		<div class="inside">

			<h4><?php esc_attr_e( 'Export', 'adminimize' ) ?></h4>
			<p><?php esc_attr_e(
					'You can save a JSON formatted ".json" file with your settings.', 'adminimize'
				) ?></p>
			<form method="post">
				<p><input type="hidden" name="_mw_adminimize_export" value="true" /></p>
				<p>
					<?php wp_nonce_field( 'mw_adminimize_export_nonce', 'mw_adminimize_export_nonce' ); ?>
					<?php
					$submit_text = esc_html__( 'Export &raquo;', 'adminimize' );
					submit_button( $submit_text, 'primary', '_mw_adminimize_save', false ); ?>
				</p>
			</form>
			<br class="clear" />

<?php /*
			<form name="export_options" method="get" action="">
				<p><?php esc_attr_e(
						'You can save a JSON formatted ".json" file with your settings.', 'adminimize'
					) ?></p>

				<p>
					<?php wp_nonce_field( 'mw_adminimize_export_nonce', 'mw_adminimize_export_nonce' ); ?>
					<input type="hidden" name="_mw_adminimize_export" value="true" />
					<?php
					$submit_text = esc_html__( 'Export &raquo;', 'adminimize' );
					submit_button(
						$text = $submit_text, $type = 'primary', $name = '_mw_adminimize_save', $wrap = FALSE,
						$other_attributes = NULL
					);
					?>
				</p>
			</form>
*/ ?>
			<h4><?php esc_attr_e( 'Import', 'adminimize' ) ?></h4>
			<form name="import_options" enctype="multipart/form-data" method="post" action="?page=<?php echo esc_attr(
				$_GET[ 'page' ]
			); ?>">
				<?php wp_nonce_field( 'mw_adminimize_nonce' ); ?>
				<p><?php _e(
						'Choose a Adminimize (<em>.json</em>) file to upload, then click <em>Upload file and import</em>.',
						'adminimize'
					);
					esc_html_e( 'After import please reload the page to display also all global values from WordPress.', 'adminimize' ); ?>
				</p>

				<p>
					<label for="datei_id">
						<?php esc_html_e(
							'Choose a ".json" file from your computer:', 'adminimize'
						) ?>
					</label>
					<input name="import_file" id="datei_id" type="file" />
				</p>

				<p>
					<?php wp_nonce_field( 'mw_adminimize_import_nonce', 'mw_adminimize_import_nonce' ); ?>
					<input type="hidden" name="_mw_adminimize_action" value="_mw_adminimize_import" />
					<?php
					$submit_text = esc_html__( 'Upload file and import &raquo;', 'adminimize' );
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
						'scroll to top', 'adminimize'
					); ?>
				</a><br class="clear" />
			</p>

		</div>
	</div>
</div>
		
