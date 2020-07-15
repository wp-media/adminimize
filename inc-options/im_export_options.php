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
global $wp_roles;
?>

<div id="poststuff" class="ui-sortable meta-box-sortables">
	<div class="postbox">
		<h3 class="hndle ui-sortable-handle" title="<?php esc_attr_e( 'Click to toggle', 'adminimize' ); ?>"
			id="import"><?php esc_attr_e( 'Export/Import Options', 'adminimize' ); ?></h3>

		<div class="inside">

			<h3><?php esc_attr_e( 'Export', 'adminimize' ); ?></h3>
			<p><?php esc_attr_e( 'You can save a JSON formatted ".json" file with your settings.', 'adminimize' ); ?></p>
			<label for="_mw_adminimize_choose_export" class="control-label">Export All Roles</label>
			<input type="checkbox" id="adminimize-toggle" name="_mw_adminimize_choose_export" value="1"
				   class="adminimize-checkbox" checked="checked">
			<label for="adminimize-toggle" class="switch"></label>
			<form method="post" id="adminimize-export">
				<p><input type="hidden" name="_mw_adminimize_export" value="true"/></p>
				<p>
					<?php wp_nonce_field( 'mw_adminimize_export_nonce', 'mw_adminimize_export_nonce' ); ?>
					<?php
					$submit_text = esc_html__( 'Export &raquo;', 'adminimize' );
					submit_button( $submit_text, 'primary', '_mw_adminimize_save', false );
					?>
				</p>
			</form>
			<br class="clear">
			<form method="post" id="adminimize-export-role">
				<p>
					<label><?php esc_attr_e( 'Choose one or more roles:', 'adminimize' ); ?><br>
						<select name="select_adminimize_roles[]" multiple id="mw_adminimize_export_select_roles">
							<?php foreach ( $wp_roles->role_names as $role_name => $data ) : ?>
								<option value="<?php echo $role_name; ?>"><?php echo $data; ?></option>
							<?php endforeach; ?>
						</select>
					</label>
				</p>
				<p><input type="hidden" name="_mw_adminimize_export_role" value="true"/></p>
				<p>
					<?php wp_nonce_field( 'mw_adminimize_export_role_nonce', 'mw_adminimize_export_role_nonce' ); ?>
					<?php
					$submit_text = esc_html__( 'Export role(s) &raquo;', 'adminimize' );
					submit_button( $submit_text, 'primary', '_mw_adminimize_save', false ); ?>
				</p>
			</form>
			<br class="clear">

			<h3><?php esc_attr_e( 'Import', 'adminimize' ) ?></h3>
			<form name="import_options" enctype="multipart/form-data" method="post"
				  action="?page=<?php echo esc_attr( $_GET['page'] ); ?>">
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
					<input name="import_file" id="datei_id" type="file"/>
				</p>

				<p>
					<?php wp_nonce_field( 'mw_adminimize_import_nonce', 'mw_adminimize_import_nonce' ); ?>
					<input type="hidden" name="_mw_adminimize_action" value="_mw_adminimize_import"/>
					<?php
					$submit_text = esc_html__( 'Upload file and import &raquo;', 'adminimize' );
					submit_button(
						$text = $submit_text, $type = 'primary', $name = '_mw_adminimize_save', $wrap = false,
						$other_attributes = null
					);
					?>
				</p>
			</form>

			<p>
				<a class="alignright button adminimize-scroltop" href="#" style="margin:3px 0 0 30px;">
					<?php esc_html_e( 'scroll to top', 'adminimize' ); ?>
				</a>
				<br class="clear"/>
			</p>

		</div>
	</div>
</div>

