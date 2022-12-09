<?php
/**
 * @package    Adminimize
 * @subpackage Admin Bar Front end Options, settings page
 * @author     Frank Bültge
 * @since      2015-07-03
 */
if ( ! function_exists( 'add_action' ) ) {
	die( "Hi there!  I'm just a part of plugin, not much I can do when called directly." );
}

if ( ! isset( $wp_admin_bar ) ) {
	$wp_admin_bar = '';
}

if ( ! isset( $user_roles ) ) {
	$user_roles = _mw_adminimize_get_all_user_roles();
}

if ( ! isset( $user_roles_names ) ) {
	$user_roles_names = _mw_adminimize_get_all_user_roles_names();
}
?>
<div id="poststuff" class="ui-sortable meta-box-sortables">
	<div class="postbox">
		<h3 class="hndle ui-sortable-handle" id="admin_bar_frontend_options" title="<?php esc_attr_e( 'Click to toggle', 'adminimize' ); ?>"><?php
			esc_attr_e( 'Admin Bar Front end Options', 'adminimize' ); ?></h3>

		<div class="inside">
			<br class="clear" />

			<table summary="config_widget" class="widefat">
				<colgroup>
					<?php
					$col = 0;
					foreach ( $user_roles_names as $role_name ) {
						echo '<col class="col' . $col . '">' . "\n";
						$col ++;
					}
					?>
				</colgroup>
				<thead>
				<tr>
					<th><?php esc_attr_e( 'Option', 'adminimize' ); ?></th>
					<?php
					foreach ( $user_roles_names as $role_name ) { ?>
						<th><?php esc_attr_e( 'Deactivate for', 'adminimize' );
							echo '<br/>' . $role_name; ?></th>
					<?php } ?>
				</tr>
				<tr>
					<td><?php esc_attr_e( 'Select all', 'adminimize' ); ?></td>
					<?php
					foreach ( $user_roles as $role_slug ) {
						echo '<td class="num">';
						echo '<input id="select_all" class="admin_bar_frontend_' . $role_slug
							. '" type="checkbox" value="" />';
						echo '</td>' . "\n";
					} ?>
				</tr>
				</thead>

				<tbody>
				<?php
				foreach ( $user_roles as $role ) {
					$disabled_admin_bar_frontend_option_[ $role ] = _mw_adminimize_get_option_value(
						'mw_adminimize_disabled_admin_bar_frontend_' . $role . '_items'
					);
				}

				$x = 0;
				// add items to array for select
				// Use the hook to enhance for custom items, there was not in the list
				$admin_bar_frontend_items = apply_filters(
					'adminimize_admin_bar_frontend_items',
					_mw_adminimize_get_option_value( 'mw_adminimize_admin_bar_frontend_nodes' )
				);

				$message = '';
				if ( ! empty( $admin_bar_frontend_items ) && is_array( $admin_bar_frontend_items ) ) {
					foreach ( $admin_bar_frontend_items as $key => $value ) {
						$value = (is_object($value)) ? $value : (object) $value;
						$is_parent = ! empty( $value->parent );
						$has_link  = ! empty( $value->href );
						// No title on the item.
						if ( ! $value->title ) {
							$value->title = '<b><i>' . esc_attr__( 'No Title!', 'adminimize' ) . '</i></b>';
						}

						$item_string  = '&bull; ';
						$before_title = '<b>';
						$after_title  = '</b> <small>' . esc_attr__( 'Group', 'adminimize' ) . '</small>';
						if ( $is_parent ) {
							$item_string = '&mdash; ';
							$before_title = '';
							$after_title  = '';
						}

						$checked_user_role_ = array();
						foreach ( $user_roles as $role ) {
							$checked_user_role_[ $role ] = ( isset( $disabled_admin_bar_frontend_option_[ $role ] )
								&& in_array(
									$key, $disabled_admin_bar_frontend_option_[ $role ]
								)
							) ? ' checked="checked"' : '';
						}

						echo '<tr>' . "\n";
						echo '<td>'. $before_title . $item_string . strip_tags( $value->title, '<strong><b><em><i>' )
							. $after_title . ' <span>(' . $key . ')</span> </td>' . "\n";
						foreach ( $user_roles as $role ) {
							echo '<td class="num"><input id="check_post' . $role . $x
								. '" class="admin_bar_frontend_' . $role . '" type="checkbox"'
								. $checked_user_role_[ $role ] . ' name="mw_adminimize_disabled_admin_bar_frontend_'
								. $role . '_items[]" value="' . $key . '" /></td>' . "\n";
						}
						echo '</tr>' . "\n";
						$x ++;
					}
				}
				$message = '<span style="font-size: 35px;">&#x261D;</span>'
					. esc_attr__(
						'You must open the front end of the site in this browser in order for the plugin to discover the Admin Bar items that are currently not visible.',
						'adminimize'
					);

				?>
				</tbody>
			</table>

			<p><?php echo $message; ?></p>

			<p id="submitbutton">
				<input type="hidden" name="_mw_adminimize_action" value="_mw_adminimize_insert" />
				<input class="button button-primary" type="submit" name="_mw_adminimize_save" value="<?php esc_attr_e(
					'Update Options', 'adminimize'
				); ?> &raquo;" /><input type="hidden" name="page_options" value="'dofollow_timeout'" />
			</p>

			<p>
                <a class="alignright button adminimize-scroltop" href="#"
					style="margin:3px 0 0 30px;"><?php esc_attr_e(
						'scroll to top', 'adminimize'
					); ?></a>
				<br class="clear" />
			</p>

		</div>
	</div>
</div>
