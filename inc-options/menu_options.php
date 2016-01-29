<?php
/**
 * @package    Adminimize
 * @subpackage Menu, Submenu Options
 * @author     Frank Bültge
 */
if ( ! function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a part of plugin, not much I can do when called directly.";
	exit;
}
?>

<div id="poststuff" class="ui-sortable meta-box-sortables">
	<div class="postbox">
		<div class="handlediv" title="<?php esc_attr_e( 'Click to toggle' ); ?>"><br /></div>
		<h3 class="hndle" id="config_menu"><?php esc_attr_e( 'Menu Options', 'adminimize' ); ?></h3>

		<div class="inside">
			<br class="clear" />

			<table summary="config_menu" class="widefat">
				<thead>
				<tr>
					<th><?php esc_attr_e( 'Menu options - Menu, Submenu', 'adminimize' ); ?></th>

					<?php foreach ( $user_roles_names as $role_name ) { ?>
						<th><?php esc_attr_e( 'Deactivate for', 'adminimize' );
							echo '<br/>' . $role_name; ?></th>
					<?php } ?>

				</tr>
				<tr>
					<td><?php esc_attr_e( 'Select all', 'adminimize' ); ?></td>
					<?php
					foreach ( $user_roles_names as $role_name ) {
						$role_name = strtolower( $role_name );
						$role_name = preg_replace( '/[^a-z0-9]+/', '', $role_name );
						echo '<td class="num">';
						echo '<span class="form-invalid">';
						echo '<input id="select_all" class="menu_options_' . $role_name
							. '" type="checkbox" name="" value="" />';
						echo '</span>';
						echo '<input id="select_all" class="submenu_options_' . $role_name
							. '" type="checkbox" name="" value="" />';
						echo '</td>' . "\n";
					} ?>
				</tr>
				</thead>
				<tbody>
				<?php
				$wp_menu    = _mw_adminimize_get_option_value( 'mw_adminimize_default_menu' );
				$wp_submenu = _mw_adminimize_get_option_value( 'mw_adminimize_default_submenu' );

				// Object to array
				if ( is_object( $wp_submenu ) ) {
					$wp_submenu = get_object_vars( $wp_submenu );
				}

				if ( ! isset( $wp_menu ) || empty( $wp_menu ) ) {
					global $menu;

					$wp_menu = $menu;
				}
				if ( ! isset( $wp_submenu ) || empty( $wp_submenu ) ) {
					global $submenu;

					$wp_submenu = $submenu;
				}

				foreach ( $user_roles as $role ) {
					$disabled_metaboxes_post_[ $role ] = _mw_adminimize_get_option_value(
						'mw_adminimize_disabled_metaboxes_post_' . $role . '_items'
					);
					$disabled_metaboxes_page_[ $role ] = _mw_adminimize_get_option_value(
						'mw_adminimize_disabled_metaboxes_page_' . $role . '_items'
					);
				}

				// print menu, submenu
				if ( isset( $wp_menu ) && '' !== $wp_menu ) {

					$i     = 0;
					$x     = 0;

					$users = array(
						0 => 'Profile',
						1 => 'edit_users',
						2 => 'profile.php',
						3 => '',
						4 => 'menu-top',
						5 => 'menu-users',
						6 => 'div'
					);

					foreach ( $wp_menu as $key => $item ) {

						_mw_adminimize_debug( 'Adminimize Menu Settings: Menu Item Key', $key );
						_mw_adminimize_debug( 'Adminimize Menu Settings: Menu Item', $item );

						// non checked items
						if ( $item[ 2 ] === 'options-general.php' ) {
							$disabled_item_adm_hint = '<abbr title="' . esc_attr__(
									'After activate the check box it heavy attitudes will change.', 'adminimize'
								) . '" style="cursor:pointer;"> ! </acronym>';
						} else {
							$disabled_item_adm      = '';
							$disabled_item_adm_hint = '';
						}

						if ( '' !== $item[ 2 ] ) {

							if ( 'wp-menu-separator' === $item[ 4 ] ) {
								$item[ 0 ] = 'Separator';
							}

							foreach ( $user_roles as $role ) {

								// checkbox checked
								$checked_user_role_[ $role ] = '';
								if ( isset( $disabled_menu_[ $role ] )
									// @since 2015-11-11
									// Switch to the key and url of menu item.
									&& _mw_adminimize_in_arrays( array( $key, $item[ 2 ] ), $disabled_menu_[ $role ] )
								) {
									$checked_user_role_[ $role ] = ' checked="checked"';
								}
							}

							echo '<tr class="form-invalid">' . "\n";
							echo "\t";
							echo '<th>';
							echo '<b>&bull; ' . $item[ 0 ] . '</b> <small>' . esc_attr__(
									'Group', 'adminimize'
								) . '</small>';
							echo '<span>'
								. $key . '('
								. preg_replace(
									"#[%2].*#",
									'...',
									htmlentities( $item[ 2 ] )
								) . ')</span>';
							echo '</th>';

							foreach ( $user_roles as $role ) {
								if ( $role !== 'administrator' ) { // only admin disable items
									$disabled_item_adm      = '';
									$disabled_item_adm_hint = '';
								}
								/**
								 * Switch to key of each Menu item
								 * @since 2016-01-29
								 *
								 * Use $key instead of htmlentities( $item[ 2 ] ) in the input field below, attribute value
								 */
								echo "\t" . '<td class="num">' . $disabled_item_adm_hint . '<input id="check_menu'
									. $role . $x . '" class="menu_options_'
									. preg_replace( '/[^a-z0-9]+/', '', $role ) . '" type="checkbox"'
									. $disabled_item_adm . $checked_user_role_[ $role ]
									. ' name="mw_adminimize_disabled_menu_' . $role . '_items[]" value="'
									. $key . '" />' . $disabled_item_adm_hint . '</td>' . "\n";
							}
							echo '</tr>';

							// Only for user smaller administrator, change user-Profile-File.
							if ( 'users.php' === $item[ 2 ] ) {
								$x ++;
								echo '<tr class="form-invalid">' . "\n";
								echo "\t" . '<th>' . esc_attr__( 'Profile' ) . ' <span>(profile.php)</span> </th>';
								foreach ( $user_roles as $role ) {
									echo "\t" . '<td class="num"><input disabled="disabled" id="check_menu'
										. $role . $x . '" class="menu_options_'
										. preg_replace( '/[^a-z0-9]+/', '', $role )
										. '" type="checkbox"' . $checked_user_role_[ $role ]
										. ' name="mw_adminimize_disabled_menu_' . $role
										. '_items[]" value="profile.php" /></td>' . "\n";
								}
								echo '</tr>';
							}

							$x ++;

							if ( ! isset( $wp_submenu[ $item[ 2 ] ] ) ) {
								continue;
							}

							// Loop about Sub Menu items.
							foreach ( $wp_submenu[ $item[ 2 ] ] as $subkey => $subitem ) {

								// Special solutions for the Adminimize link, that it not works on settings site.
								if ( $subitem[ 2 ] === 'adminimize/adminimize.php' ) {
									//$disabled_subitem_adm = ' disabled="disabled"';
									$disabled_subitem_adm_hint = '<abbr title="'
										. esc_attr__(
											'After activate the check box it heavy attitudes will change.', 'adminimize'
										)
										. '" style="cursor:pointer;"> ! </acronym>';
								} else {
									$disabled_subitem_adm      = '';
									$disabled_subitem_adm_hint = '';
								}

								echo '<tr>' . "\n";
								foreach ( $user_roles as $role ) {
									// checkbox checked
									$checked_user_role_[ $role ] = '';
									if ( isset( $disabled_submenu_[ $role ] )
										// @since 2015-11-11
										// Switch to custom key and url of menu item.
										&& _mw_adminimize_in_arrays(
											array( $item[ 2 ] . '__' . $subkey, $subitem[ 2 ] ),
											$disabled_submenu_[ $role ]
										)
									) {
										$checked_user_role_[ $role ] = ' checked="checked"';
									}
								}
								echo '<td> &mdash; ' . $subitem[ 0 ] . ' <span>['
									. $subkey . ']('
									. preg_replace(
										"#[%2].*#",
										'...',
										htmlentities( $subitem[ 2 ] )
									) . ')</span> </td>' . "\n";

								foreach ( $user_roles as $role ) {
									if ( $role !== 'administrator' ) { // only admin disable items
										$disabled_subitem_adm      = '';
										$disabled_subitem_adm_hint = '';
									}
									echo '<td class="num">' . $disabled_subitem_adm_hint . '<input id="check_menu' . $role . $x
										. '" class="submenu_options_'
										. preg_replace( '/[^a-z0-9]+/', '', $role ) . '" type="checkbox"'
										. $disabled_subitem_adm . $checked_user_role_[ $role ]
										. ' name="mw_adminimize_disabled_submenu_' . $role . '_items[]" value="'
										. $item[ 2 ] . '__' . $subkey . '" />' . $disabled_subitem_adm_hint . '</td>' . "\n";
								}
								echo '</tr>' . "\n";
								$x ++;
							}
							$i ++;
							$x ++;
						}
					}

				} else {
					$myErrors = new _mw_adminimize_message_class();
					$myErrors = '<tr><td style="color: red;">' . $myErrors->get_error(
							'_mw_adminimize_get_option'
						) . '</td></tr>';
					echo $myErrors;
				} ?>
				</tbody>
			</table>

			<p id="submitbutton">
				<input class="button button-primary" type="submit" name="_mw_adminimize_save" value="<?php esc_attr_e(
					'Update Options', 'adminimize'
				); ?> &raquo;" /><input type="hidden" name="page_options" value="'dofollow_timeout'" />
			</p>

			<p>
				<a class="alignright button" href="javascript:void(0);" onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;"><?php esc_attr_e(
						'scroll to top', 'adminimize'
					); ?></a><br class="clear" /></p>

		</div>
	</div>
</div>
		