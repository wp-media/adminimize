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
		<div class="handlediv" title="<?php esc_attr_e( 'Click to toggle', 'adminimize' ); ?>"><br /></div>
		<h3 class="hndle" id="config_menu"><?php esc_attr_e( 'Menu Options', 'adminimize' ); ?></h3>

		<div class="inside">
			<br class="clear" />

			<table summary="config_menu" class="widefat config_menu">
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
					<th><?php esc_attr_e( 'Menu options - Menu, Submenu', 'adminimize' ); ?></th>

					<?php foreach ( $user_roles_names as $role_name ) { ?>
						<th><?php esc_attr_e( 'Deactivate for', 'adminimize' );
							echo '<br/>' . $role_name; ?></th>
					<?php } ?>

				</tr>
				<tr>
					<td><?php esc_attr_e( 'Select all', 'adminimize' ); ?></td>
					<?php
					foreach ( $user_roles as $role_slug ) {
						echo '<td class="num">';
						echo '<span class="form-invalid">';
						echo '<input id="select_all" class="menu_options_' . $role_slug
						     . '" type="checkbox" name="" value="" />';
						echo '</span>';
						echo '<input id="select_all" class="submenu_options_' . $role_slug
						     . '" type="checkbox" name="" value="" />';
						echo '</td>' . "\n";
					} ?>
				</tr>
				</thead>
				<tbody>
				<?php
				$wp_menu    = (array) _mw_adminimize_get_option_value( 'mw_adminimize_default_menu' );
				$wp_submenu = (array) _mw_adminimize_get_option_value( 'mw_adminimize_default_submenu' );

				// Object to array
				if ( is_object( $wp_submenu ) ) {
					$wp_submenu = get_object_vars( $wp_submenu );
				}

				if ( ! isset( $wp_menu ) || empty( $wp_menu ) ) {
					global $menu;

					$wp_menu = (array) $menu;
				}
				if ( ! isset( $wp_submenu ) || empty( $wp_submenu ) ) {
					global $submenu;

					$wp_submenu = $submenu;
				}

				// Enhance Menu with custom slugs.
				$own_menu_slug        = _mw_adminimize_get_option_value(
					'_mw_adminimize_own_menu_slug'
				);
				$own_menu_custom_slug = _mw_adminimize_get_option_value(
					'_mw_adminimize_own_menu_custom_slug'
				);
				$own_menu_slug        = preg_split( "/\r\n/", $own_menu_slug );
				$own_menu_custom_slug = preg_split( "/\r\n/", $own_menu_custom_slug );
				foreach ( (array) $own_menu_slug as $key => $slug ) {
					$wp_menu[] = array(
						0 => trim( $slug ),
						1 => '',
						2 => $own_menu_custom_slug[ $key ],
						3 => '',
						4 => 'custom',
					);
				}

				foreach ( $user_roles as $role ) {
					$disabled_metaboxes_post_[ $role ] = _mw_adminimize_get_option_value(
						'mw_adminimize_disabled_metaboxes_post_' . $role . '_items'
					);
					$disabled_metaboxes_page_[ $role ] = _mw_adminimize_get_option_value(
						'mw_adminimize_disabled_metaboxes_page_' . $role . '_items'
					);
				}

				// print menu, sub-menu
				if ( isset( $wp_menu ) && '' !== $wp_menu ) {

					$i = 0;
					$x = 0;
					foreach ( $wp_menu as $key => $item ) {

						$menu_slug = $item[ 2 ];

						// non checked items
						$disabled_item_adm      = '';
						if ( $menu_slug === 'options-general.php' ) {
							$disabled_item_adm_hint = '<abbr title="' . esc_attr__(
									'After activation of this checkbox you will loose the easy access to the settings area inside the menu.', 'adminimize'
								) . '" style="cursor:pointer;"> ! </acronym>';
						} else {
							
							$disabled_item_adm_hint = '';
						}

						if ( '' !== $menu_slug ) {

							if ( 'wp-menu-separator' === $item[ 4 ] ) {
								$item[ 0 ] = 'Separator';
							}

							foreach ( $user_roles as $role ) {

								// checkbox checked
								$checked_user_role_[ $role ] = '';
								if ( isset( $disabled_menu_[ $role ] )
								     && in_array( $menu_slug, $disabled_menu_[ $role ], FALSE )
								) {
									$checked_user_role_[ $role ] = ' checked="checked"';
								}
							}

							if ( ! $item[ 0 ] ) {
								$item[ 0 ] = '<b><i>' . esc_attr__( 'No Title!', 'adminimize' ) . '</i></b>';
							}

							$typ = esc_attr__( 'Group', 'adminimize' );
							if ( 'custom' === $item[ 4 ] ) {
								$typ = esc_attr__( 'Custom', 'adminimize' );
							}

							echo '<tr class="form-invalid">' . "\n";
							echo "\t";
							echo '<td>';
							echo '<b>&bull; ' . strip_tags( $item[ 0 ] ) . '</b> <small>' . $typ . '</small>';
							echo '<span>('
							     . preg_replace(
								     '#[%2].*#',
								     '...',
								     htmlentities( $menu_slug )
							     ) . ')</span>';
							echo '</td>';

							foreach ( $user_roles as $role ) {
								if ( $role !== 'administrator' ) { // only admin disable items
									$disabled_item_adm      = '';
									$disabled_item_adm_hint = '';
								}
								/**
								 * Switch to key of each Menu item
								 *
								 * @since 2016-01-29
								 *        Use $key instead of htmlentities( $item[ 2 ] ) in the input field below, attribute value
								 */
								echo "\t" . '<td class="num">' . $disabled_item_adm_hint . '<input id="check_menu'
								     . $role . $x . '" class="menu_options_'
								     . preg_replace( '/[^a-z0-9_-]+/', '', $role ) . '" type="checkbox"'
								     . $disabled_item_adm . $checked_user_role_[ $role ]
								     . ' name="mw_adminimize_disabled_menu_' . $role . '_items[]" value="'
								     . $menu_slug . '" />' . $disabled_item_adm_hint . '</td>' . "\n";
							}
							echo '</tr>';

							$x ++;

							if ( ! isset( $wp_submenu[ $menu_slug ] ) ) {
								continue;
							}

							// Loop about Sub Menu items.
							foreach ( $wp_submenu[ $menu_slug ] as $subkey => $subitem ) {
								$submenu_slug = $subitem[ 2 ];

								// Special solutions for the Adminimize link, that it not works on settings site.
								if ( strtolower( $submenu_slug ) === 'adminimize/adminimize.php' ) {
									//$disabled_subitem_adm = ' disabled="disabled"';
									$disabled_subitem_adm_hint = '<abbr title="'
									                             . esc_attr__(
										                             'After activate the checkbox you will loose its easy access in the menu.',
										                             'adminimize'
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
									     // Switch to custom key and url-slug of menu item.
									     && _mw_adminimize_in_arrays(
										     array( $menu_slug . '__' . $subkey, $submenu_slug ),
										     $disabled_submenu_[ $role ]
									     )
									) {
										$checked_user_role_[ $role ] = ' checked="checked"';
									}
								}
								echo '<td> &mdash; ' . strip_tags( $subitem[ 0 ] ) . ' <span>(Slug: '
								     . preg_replace(
									     '#[%2].*#',
									     '...',
									     htmlentities( $submenu_slug )
								     ) . ')[__' . $subkey . ']</span> </td>' . "\n";

								foreach ( $user_roles as $role ) {
									if ( $role !== 'administrator' ) { // only admin disable items
										$disabled_subitem_adm      = '';
										$disabled_subitem_adm_hint = '';
									}
									echo '<td class="num">' . $disabled_subitem_adm_hint . '<input id="check_menu' . $role . $x
									     . '" class="submenu_options_' . $role . '" type="checkbox"'
									     . $disabled_subitem_adm . $checked_user_role_[ $role ]
									     . ' name="mw_adminimize_disabled_submenu_' . $role . '_items[]" value="'
									     . $menu_slug . '__' . $subkey . '" />' . $disabled_subitem_adm_hint . '</td>' . "\n";
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

			<?php
			//Your own dashboard options.
			?>
			<br style="margin-top: 10px;" />
			<table summary="config_edit_post" class="widefat">
				<thead>
				<tr>
					<th><?php esc_attr_e( 'Your own options', 'adminimize' );
						echo '<br />';
						esc_attr_e( 'Slug', 'adminimize' ); ?></th>
					<th><?php echo '<br />';
						esc_attr_e( 'Custom Slug', 'adminimize' ); ?></th>
				</tr>
				</thead>

				<tbody>
				<tr valign="top">
					<td colspan="2"><?php esc_attr_e(
							'It is possible to add your own slugs for menu items.',
							'adminimize'
						); ?></td>
				</tr>
				<tr valign="top">
					<td>
							<textarea name="_mw_adminimize_own_menu_slug" cols="60" rows="3"
								id="_mw_adminimize_own_menu_slug" style="width: 95%;"><?php
								echo _mw_adminimize_get_option_value(
									'_mw_adminimize_own_menu_slug'
								); ?></textarea>
						<br />
						<label for="_mw_adminimize_own_menu_slug">
							<?php esc_attr_e(
								'Possible nomination for the slug. Separate multiple nominations through a carriage return.',
								'adminimize'
							); ?>
						</label>
					</td>
					<td>
							<textarea class="code" name="_mw_adminimize_own_menu_custom_slug" cols="60" rows="3"
								id="_mw_adminimize_own_menu_custom_slug" style="width: 95%;"><?php
								echo _mw_adminimize_get_option_value(
									'_mw_adminimize_own_menu_custom_slug'
								); ?></textarea>
						<br />
						<label for="_mw_adminimize_own_menu_custom_slug">
							<?php esc_attr_e(
								'String of the custom slug.',
								'adminimize'
							); ?>
						</label>
					</td>
				</tr>
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
