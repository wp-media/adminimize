<?php
/**
 * @package    Adminimize
 * @subpackage Nav Menu Options
 * @author     Frank Bültge
 */
if ( ! function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a part of plugin, not much I can do when called directly.";
	exit;
}
?>
<div id="poststuff" class="ui-sortable meta-box-sortables">
	<div class="postbox">
		<h3 class="hndle ui-sortable-handle" title="<?php esc_attr_e( 'Click to toggle', 'adminimize' ); ?>" id="nav_menu_options"><?php esc_attr_e( 'WP Nav Menu', 'adminimize' ); ?></h3>

		<div class="inside">
			<br class="clear" />

			<table summary="config_nav_menu" class="widefat">
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
						echo '<input id="select_all" class="wp_nav_menu_options_' . $role_slug
							. '" type="checkbox" name="" value="" />';
						echo '</td>' . "\n";
					} ?>
				</tr>
				</thead>

				<tbody>
				<?php
				foreach ( $user_roles as $role ) {
					$disabled_nav_menu_option_[ $role ] = _mw_adminimize_get_option_value(
						'mw_adminimize_disabled_nav_menu_option_' . $role . '_items'
					);
				}

				$nav_menu_options = array(
					'#contextual-help-link-wrap',
					'#screen-options-link-wrap',
					'#nav-menu-theme-locations',
					'#add-custom-links',
					'.menu-add-new',
				);

				if ( wp_get_nav_menus() ) {
					array( $nav_menu_options, '#nav-menu-theme-locations' );
				}

				$nav_menu_options_names = array(
					esc_attr__( 'Help', 'adminimize' ),
					esc_attr__( 'Screen Options' ),
					esc_attr__( 'Theme Locations', 'adminimize' ),
					esc_attr__( 'Custom Links', 'adminimize' ),
					'#(' . esc_attr__( 'Add menu', 'adminimize' ) . ')',
				);

				if ( wp_get_nav_menus() ) {
					array( $nav_menu_options_names, esc_attr__( 'Theme Locations' ) );
				}

				// taxonomies
				$taxonomies = get_taxonomies( array( 'show_in_nav_menus' => TRUE ), 'object' );
				if ( $taxonomies ) {
					foreach ( $taxonomies as $tax ) {
						if ( $tax ) {
							$nav_menu_options[]       = '#add-' . $tax->name;
							$nav_menu_options_names[] = $tax->labels->name;
						}
					}
				}

				// post types
				$post_types = get_post_types( array( 'show_in_nav_menus' => TRUE ), 'object' );
				if ( $post_types ) {
					foreach ( $post_types as $post_type ) {
						if ( $post_type ) {
							$nav_menu_options[] = '#add-' . $post_type->name;
							$nav_menu_options_names[] = $post_type->labels->name;
						}
					}
				}

				$_mw_adminimize_own_nav_menu_values = _mw_adminimize_get_option_value(
					'_mw_adminimize_own_nav_menu_values'
				);
				$_mw_adminimize_own_nav_menu_values = preg_split( "/\r\n/", $_mw_adminimize_own_nav_menu_values );
				foreach ( (array) $_mw_adminimize_own_nav_menu_values as $key => $_mw_adminimize_own_nav_menu_value ) {
					$_mw_adminimize_own_nav_menu_value = trim( $_mw_adminimize_own_nav_menu_value );
					$nav_menu_options[] = $_mw_adminimize_own_nav_menu_value;
				}

				$_mw_adminimize_own_nav_menu_options = _mw_adminimize_get_option_value(
					'_mw_adminimize_own_nav_menu_options'
				);
				$_mw_adminimize_own_nav_menu_options = preg_split( "/\r\n/", $_mw_adminimize_own_nav_menu_options );
				foreach ( (array) $_mw_adminimize_own_nav_menu_options as $key => $_mw_adminimize_own_nav_menu_option ) {
					$_mw_adminimize_own_nav_menu_option = trim( $_mw_adminimize_own_nav_menu_option );
					$nav_menu_options_names[] = $_mw_adminimize_own_nav_menu_option;
				}

				$x = 0;
				foreach ( $nav_menu_options as $index => $nav_menu_option ) {
					if ( $nav_menu_option != '' ) {
						$checked_user_role_ = array();
						foreach ( $user_roles as $role ) {
							$checked_user_role_[ $role ] = ( isset( $disabled_nav_menu_option_[ $role ] )
								&& in_array(
									$nav_menu_option, $disabled_nav_menu_option_[ $role ]
								) ) ? ' checked="checked"' : '';
						}
						echo '<tr>' . "\n";
						echo '<td>' . $nav_menu_options_names[ $index ] . ' <span>(' . $nav_menu_option . ')</span> </td>' . "\n";
						foreach ( $user_roles as $role ) {
							echo '<td class="num">';
							echo '<input id="check_post' . $role . $x . '" class="wp_nav_menu_options_'
								. preg_replace( '/[^a-z0-9_-]+/', '', $role ) . '" type="checkbox"'
								. $checked_user_role_[ $role ] . ' name="mw_adminimize_disabled_nav_menu_option_'
								. $role . '_items[]" value="' . $nav_menu_option . '" />';
							echo '</td>' . "\n";
						}
						echo '</tr>' . "\n";
						$x ++;
					}
				}
				?>
				</tbody>
			</table>

			<?php
			//your own global options
			?>
			<br style="margin-top: 10px;" />
			<table summary="config_edit_post" class="widefat">
				<thead>
				<tr>
					<th><?php esc_attr_e( 'Your own Nav Menu options', 'adminimize' );
						echo '<br />';
						esc_attr_e( 'Option name', 'adminimize' ); ?></th>
					<th><?php echo '<br />';
						esc_attr_e( 'Selector, ID or class', 'adminimize' ); ?></th>
				</tr>
				</thead>

				<tbody>
				<tr valign="top">
					<td colspan="2"><?php esc_attr_e(
							'It is possible to add your own IDs or classes from elements and tags. You can find IDs and classes with the FireBug Add-on for Firefox. Assign a value and the associate name per line.',
							'adminimize'
						); ?></td>
				</tr>
				<tr valign="top">
					<td>
						<textarea name="_mw_adminimize_own_nav_menu_options" cols="60" rows="3" id="_mw_adminimize_own_nav_menu_options" style="width: 95%;"><?php echo _mw_adminimize_get_option_value(
								'_mw_adminimize_own_nav_menu_options'
							); ?></textarea>
						<br />
						<?php esc_attr_e(
							'Possible nomination for ID or class. Separate multiple nominations through a carriage return.',
							'adminimize'
						); ?>
					</td>
					<td>
						<textarea class="code" name="_mw_adminimize_own_nav_menu_values" cols="60" rows="3" id="_mw_adminimize_own_nav_menu_values" style="width: 95%;"><?php echo _mw_adminimize_get_option_value(
								'_mw_adminimize_own_nav_menu_values'
							); ?></textarea>
						<br />
						<?php esc_attr_e(
							'Possible IDs or classes. Separate multiple values through a carriage return.', 'adminimize'
						); ?>
					</td>
				</tr>
				</tbody>
			</table>

			<p id="submitbutton">
				<input type="hidden" name="_mw_adminimize_action" value="_mw_adminimize_insert" />
				<input class="button button-primary" type="submit" name="_mw_adminimize_save" value="<?php esc_attr_e(
					'Update Options', 'adminimize'
				); ?> &raquo;" /><input type="hidden" name="page_options" value="'dofollow_timeout'" />
			</p>

			<p>
                <a class="alignright button adminimize-scroltop" href="#" style="margin:3px 0 0 30px;"><?php esc_attr_e(
						'scroll to top', 'adminimize'
					); ?></a><br class="clear" /></p>

		</div>
	</div>
</div>
