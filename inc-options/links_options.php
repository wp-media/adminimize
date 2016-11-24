<?php
/**
 * @package    Adminimize
 * @subpackage Link Options
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
		<h3 class="hndle" id="links_options"><?php esc_attr_e( 'Links options', 'adminimize' ); ?></h3>

		<div class="inside">
			<br class="clear" />

			<table summary="config_edit_links" class="widefat">
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
						echo '<input id="select_all" class="links_options_' . $role_slug
							. '" type="checkbox" name="" value="" />';
						echo '</td>' . "\n";
					} ?>
				</tr>
				</thead>

				<tbody>
				<?php
				foreach ( $user_roles as $role ) {
					$disabled_link_option_[ $role ] = _mw_adminimize_get_option_value(
						'mw_adminimize_disabled_link_option_' . $role . '_items'
					);
				}

				$link_options = array(
					'#namediv',
					'#addressdiv',
					'#descriptiondiv',
					'#linkcategorydiv',
					'#linktargetdiv',
					'#linkxfndiv',
					'#linkadvanceddiv',
					'#misc-publishing-actions'
				);

				$link_options_names = array(
					esc_attr__( 'Name' ),
					esc_attr__( 'Web Address' ),
					esc_attr__( 'Description' ),
					esc_attr__( 'Categories' ),
					esc_attr__( 'Target' ),
					esc_attr__( 'Link Relationship (XFN)' ),
					esc_attr__( 'Advanced' ),
					esc_attr__( 'Publish Actions', 'adminimize' )
				);

				$_mw_adminimize_own_link_values = _mw_adminimize_get_option_value( '_mw_adminimize_own_link_values' );
				$_mw_adminimize_own_link_values = preg_split( "/\r\n/", $_mw_adminimize_own_link_values );
				foreach ( (array) $_mw_adminimize_own_link_values as $key => $_mw_adminimize_own_link_value ) {
					$_mw_adminimize_own_link_value = trim( $_mw_adminimize_own_link_value );
					$link_options[] = $_mw_adminimize_own_link_value;
				}

				$_mw_adminimize_own_link_options = _mw_adminimize_get_option_value( '_mw_adminimize_own_link_options' );
				$_mw_adminimize_own_link_options = preg_split( "/\r\n/", $_mw_adminimize_own_link_options );
				foreach ( (array) $_mw_adminimize_own_link_options as $key => $_mw_adminimize_own_link_option ) {
					$_mw_adminimize_own_link_option = trim( $_mw_adminimize_own_link_option );
					$link_options_names[] = $_mw_adminimize_own_link_option;
				}

				$x = 0;
				foreach ( $link_options as $index => $link_option ) {
					if ( $link_option != '' ) {
						$checked_user_role_ = array();
						foreach ( $user_roles as $role ) {
							$checked_user_role_[ $role ] = ( isset( $disabled_link_option_[ $role ] )
								&& in_array(
									$link_option, $disabled_link_option_[ $role ]
								) ) ? ' checked="checked"' : '';
						}
						echo '<tr>' . "\n";
						echo '<td>' . $link_options_names[ $index ] . ' <span>(' . $link_option . ')</span> </td>' . "\n";
						foreach ( $user_roles as $role ) {
							echo '<td class="num">';
							echo '<input id="check_post' . $role . $x . '" class="links_options_'
								. preg_replace( '/[^a-z0-9_-]+/', '', $role ) . '" type="checkbox"'
								. $checked_user_role_[ $role ] . ' name="mw_adminimize_disabled_link_option_'
								. $role . '_items[]" value="' . $link_option . '" />';
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
					<th><?php esc_attr_e( 'Your own Link options', 'adminimize' );
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
						<textarea name="_mw_adminimize_own_link_options" cols="60" rows="3" id="_mw_adminimize_own_link_options" style="width: 95%;"><?php echo _mw_adminimize_get_option_value(
								'_mw_adminimize_own_link_options'
							); ?></textarea>
						<br />
						<?php esc_attr_e(
							'Possible nomination for ID or class. Separate multiple nominations through a carriage return.',
							'adminimize'
						); ?>
					</td>
					<td>
						<textarea class="code" name="_mw_adminimize_own_link_values" cols="60" rows="3" id="_mw_adminimize_own_link_values" style="width: 95%;"><?php echo _mw_adminimize_get_option_value(
								'_mw_adminimize_own_link_values'
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
				<a class="alignright button" href="javascript:void(0);" onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;"><?php esc_attr_e(
						'scroll to top', 'adminimize'
					); ?></a><br class="clear" /></p>

		</div>
	</div>
</div>