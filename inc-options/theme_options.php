<?php
/**
 * @package    Adminimize
 * @subpackage Backend Theme options
 * @author     Frank Bültge
 */
if ( ! function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a part of plugin, not much I can do when called directly.";
	exit;
}
?>
<div id="poststuff" class="ui-sortable meta-box-sortables">
	<div class="postbox">
		<h3 class="hndle ui-sortable-handle" title="<?php esc_attr_e( 'Click to toggle', 'adminimize' ); ?>" id="set_theme" title="<?php esc_attr_e( 'Click to toggle', 'adminimize' ); ?>"><?php
			esc_attr_e( 'Set Theme', 'adminimize' ) ?></h3>

		<div class="inside">
			<br class="clear" />

			<?php if ( ! isset( $_POST[ '_mw_adminimize_action' ] ) || ! ( $_POST[ '_mw_adminimize_action' ] === '_mw_adminimize_load_theme' ) ) { ?>
				<form name="set_theme" method="post" id="_mw_adminimize_set_theme" action="?page=<?php echo esc_attr(
					$_GET[ 'page' ]
				); ?>">
					<?php wp_nonce_field( 'mw_adminimize_nonce' ); ?>
					<p><?php esc_attr_e(
							'For better performance on sites with many users, you should load userlist data before making any changes in the theme options for users.',
							'adminimize'
						); ?></p>

					<p id="submitbutton">
						<input type="hidden" name="_mw_adminimize_action" value="_mw_adminimize_load_theme" />
						<input type="submit" name="_mw_adminimize_load" value="<?php esc_attr_e(
							'Load User Data', 'adminimize'
						); ?> &raquo;" class="button button-primary" />
					</p>
				</form>
			<?php }
			if ( isset( $_POST[ '_mw_adminimize_action' ] ) && ( $_POST[ '_mw_adminimize_action' ] === '_mw_adminimize_load_theme' ) ) { ?>
				<form name="set_theme" method="post" id="_mw_adminimize_set_theme" action="?page=<?php echo esc_attr(
					$_GET[ 'page' ]
				); ?>">
					<?php wp_nonce_field( 'mw_adminimize_nonce' ); ?>
					<table class="widefat usertheme">
						<thead>
						<tr class="thead">
							<th class="num">&nbsp;</th>
							<th class="num"><?php esc_attr_e( 'User-ID', 'adminimize' ) ?></th>
							<th><?php esc_attr_e( 'Username', 'adminimize' ) ?></th>
							<th><?php esc_attr_e( 'Display name publicly as', 'adminimize' ) ?></th>
							<th><?php esc_attr_e( 'Admin Color Scheme', 'adminimize' ) ?></th>
							<th><?php esc_attr_e( 'User Level', 'adminimize' ) ?></th>
							<th><?php esc_attr_e( 'Role', 'adminimize' ) ?></th>
						</tr>
						</thead>
						<tbody id="users" class="list:user user-list">
						<?php
						/** @var \WPDB $wpdb */
						$wp_user_search = (array) $wpdb->get_results(
							"SELECT ID, user_login, display_name FROM $wpdb->users ORDER BY ID"
						);

						foreach ( $wp_user_search as $userid ) {
							$user_id       = (int) $userid->ID;
							$user_login    = stripslashes( $userid->user_login );
							$display_name  = stripslashes( $userid->display_name );
							$current_color = get_user_option( 'admin_color', $user_id );
							$user_level    = (int) get_user_option( $table_prefix . 'user_level', $user_id );
							$user_object   = new WP_User( $user_id );
							$roles         = $user_object->roles;
							$role          = array_shift( $roles );
							/** @var \WP_Roles $wp_roles */
							$role_name = '';
							if ( isset( $wp_roles->role_names[ $role ] ) ) {
								$role_name = $wp_roles->role_names[ $role ];
							}

							if ( function_exists( 'translate_user_role' ) ) {
								$role_name = translate_user_role( $role_name );
							} elseif ( function_exists( 'before_last_bar' ) ) {
								$role_name = before_last_bar( $role_name );
							} else {
								$role_name = strrpos( $role_name, '|' );
							}

							$return = '';
							$return .= '<tr>' . "\n";
							$return .= "\t" . '<td class="num"><input type="checkbox" name="mw_adminimize_theme_items[]" value="' . $user_id . '" /></td>' . "\n";
							$return .= "\t" . '<td class="num">' . $user_id . '</td>' . "\n";
							$return .= "\t" . '<td>' . $user_login . '</td>' . "\n";
							$return .= "\t" . '<td>' . $display_name . '</td>' . "\n";
							$return .= "\t" . '<td>' . $current_color . '</td>' . "\n";
							$return .= "\t" . '<td class="num">' . $user_level . '</td>' . "\n";
							$return .= "\t" . '<td>' . $role_name . '</td>' . "\n";
							$return .= '</tr>' . "\n";

							echo $return;
						}
						?>
						<tr valign="top">
							<td class="num">&nbsp;</td>
							<td class="num">&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>
								<label for="_mw_adminimize_set_theme"></label>
								<select id="_mw_adminimize_set_theme" name="_mw_adminimize_set_theme">
									<?php /** @var array $_wp_admin_css_colors */
									foreach ( $_wp_admin_css_colors as $color => $color_info ): ?>
										<option value="<?php echo $color; ?>"><?php echo $color_info->name . ' (' . $color . ')' ?></option>
									<?php endforeach; ?>
								</select>
							</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						</tbody>
					</table>
					<p id="submitbutton">
						<input type="hidden" name="_mw_adminimize_action" value="_mw_adminimize_set_theme" />
						<input type="hidden" name="_mw_adminimize_load" value="_mw_adminimize_load_theme" />
						<input type="submit" name="_mw_adminimize_save" value="<?php esc_attr_e(
							'Set Theme', 'adminimize'
						); ?> &raquo;" class="button button-primary" />
					</p>
				</form>
			<?php } ?>

			<p>
                <a class="alignright button adminimize-scroltop" href="#" style="margin:3px 0 0 30px;"><?php esc_attr_e(
						'scroll to top', 'adminimize'
					); ?></a><br class="clear" /></p>
		</div>
	</div>
</div>
