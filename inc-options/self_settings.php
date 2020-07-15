<?php
/**
 * @package    Adminimize
 * @subpackage Admininmiz Options for the settings page
 * @author     Frank Bültge
 * @since      2016-02-26
 */
if ( ! function_exists( 'add_action' ) ) {
	die( "Hi there!  I'm just a part of plugin, not much I can do when called directly." );
}
?>
<div id="poststuff" class="ui-sortable meta-box-sortables">
	<div id="about" class="postbox">
		<h3 class="hndle ui-sortable-handle" title="<?php esc_attr_e( 'Click to toggle', 'adminimize' ); ?>" id="self-settings"><?php esc_attr_e( 'Plugin Settings', 'adminimize' ) ?></h3>

		<div class="inside">

			<table class="form-table">
				<tr>
					<td class="row-title"><label for="mw_adminimize_debug">
							<?php esc_attr_e( 'Active Debug Helper', 'adminimize' ); ?>
						</label>
					</td>
					<td>
						<input type="checkbox" value="1" id="mw_adminimize_debug"
							name="mw_adminimize_debug" <?php checked(
							_mw_adminimize_get_option_value( 'mw_adminimize_debug' ),
							1, TRUE ); ?>>
						<?php esc_attr_e( 'After activation is it possible to see several information inside the console of the browser for the current active user.', 'adminimize' ); ?>
					</td>
				</tr>
				<tr>
					<td class="row-title"><label for="mw_adminimize_multiple_roles">
							<?php esc_attr_e( 'Support Multiple Roles', 'adminimize' ); ?>
						</label>
					</td>
					<td>
						<input type="checkbox" value="1" id="mw_adminimize_multiple_roles"
							name="mw_adminimize_multiple_roles" <?php checked(
							_mw_adminimize_get_option_value( 'mw_adminimize_multiple_roles' ),
							1, TRUE ); ?>>
						<?php esc_attr_e( 'To hide an option for a user with multiple roles, the option must be selected for every role of that user. If the option is not selected for one of the user\'s roles, then the item will appear.', 'adminimize' ); ?>
					</td>
				</tr>
				<tr>
					<td class="row-title"><label for="mw_adminimize_support_bbpress">
							<?php esc_attr_e( 'Support bbPress Roles', 'adminimize' ); ?>
						</label>
					</td>
					<td>
						<input type="checkbox" value="1" id="mw_adminimize_support_bbpress"
							name="mw_adminimize_support_bbpress" <?php checked(
							_mw_adminimize_get_option_value( 'mw_adminimize_support_bbpress' ),
							1,
							TRUE ); ?>>
						<?php esc_attr_e( 'Show bbPress roles for each area to allow bbPress specific user settings.', 'adminimize' ); ?>
					</td>
				</tr>
				<tr>
					<td class="row-title"><label for="mw_adminimize_prevent_page_access">
							<?php esc_attr_e( 'Allow Page Access', 'adminimize' ); ?>
						</label>
					</td>
					<td>
						<input type="checkbox" value="1" id="mw_adminimize_prevent_page_access"
							name="mw_adminimize_prevent_page_access" <?php checked(
							_mw_adminimize_get_option_value( 'mw_adminimize_prevent_page_access' ),
							1,
							TRUE ); ?>>
						<?php esc_attr_e( 'Activate this option to allow access to pages of the back end, even if it\'s hidden to a user role.', 'adminimize' ); ?>
					</td>
				</tr>
			</table>

			<p id="submitbutton">
				<input type="hidden" name="_mw_adminimize_action" value="_mw_adminimize_insert" />
				<input class="button button-primary" type="submit" name="_mw_adminimize_save" value="<?php esc_attr_e(
					'Update Options', 'adminimize'
				); ?> &raquo;" /><input type="hidden" name="page_options" value="'dofollow_timeout'" />
			</p>

			<p>
                <a class="alignright button adminimize-scroltop" href="#"
					onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;"><?php esc_attr_e(
						'scroll to top', 'adminimize'
					); ?></a><br class="clear" />
			</p>
		</div>
	</div>
</div>
