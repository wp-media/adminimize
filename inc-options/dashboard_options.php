<?php
/**
 * @package    Adminimize
 * @subpackage Dashboard Options
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
		<h3 class="hndle" id="dashboard_options"><?php esc_attr_e( 'Dashboard options', 'adminimize' ); ?></h3>

		<div class="inside">
			<br class="clear" />
			<?php
			$disabled_dashboard_option_ = array();
			// get widgets
			$widgets = _mw_adminimize_get_option_value( 'mw_adminimize_dashboard_widgets' );
			if ( NULL === $widgets ) {
				echo '<p>';
				esc_attr_e(
					'To complete the installation for Dashboard Widgets you must visit your dashboard once and then come back to Settings > Adminimize to configure who has access to each widget.',
					'adminimize'
				);
				echo '</p>';
			} else {
				?>

				<table summary="config_edit_dashboard" class="widefat">
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
							echo '<input id="select_all" class="dashboard_options_' . $role_slug
								. '" type="checkbox" name="" value="" />';
							echo '</td>' . "\n";
						} ?>
					</tr>
					</thead>

					<tbody>
					<?php
					foreach ( $user_roles as $role ) {
						$disabled_dashboard_option_[ $role ] = _mw_adminimize_get_option_value(
							'mw_adminimize_disabled_dashboard_option_' . $role . '_items'
						);
					}

					$dashboard_options       = array();
					$dashboard_options_names = array();
					foreach ( $widgets as $widget ) {
						// Object to array
						if ( is_object( $widget ) ) {
							$widget = get_object_vars( $widget );
						}
						$dashboard_options[]       = $widget[ 'id' ];
						$dashboard_options_names[] = $widget[ 'title' ];
					}

					$_mw_adminimize_own_dashboard_values = _mw_adminimize_get_option_value(
						'_mw_adminimize_own_dashboard_values'
					);
					$_mw_adminimize_own_dashboard_values = preg_split( "/\r\n/", $_mw_adminimize_own_dashboard_values );
					foreach ( (array) $_mw_adminimize_own_dashboard_values as $key => $_mw_adminimize_own_dashboard_value ) {
						$_mw_adminimize_own_dashboard_value = trim( $_mw_adminimize_own_dashboard_value );
						$dashboard_options[]                = $_mw_adminimize_own_dashboard_value;
					}

					$_mw_adminimize_own_dashboard_options = _mw_adminimize_get_option_value(
						'_mw_adminimize_own_dashboard_options'
					);
					$_mw_adminimize_own_dashboard_options = preg_split(
						"/\r\n/", $_mw_adminimize_own_dashboard_options
					);
					foreach ( (array) $_mw_adminimize_own_dashboard_options as $key => $_mw_adminimize_own_dashboard_option ) {
						$_mw_adminimize_own_dashboard_option = trim( $_mw_adminimize_own_dashboard_option );
						$dashboard_options_names[]           = $_mw_adminimize_own_dashboard_option;
					}

					$x = 0;
					foreach ( $dashboard_options as $index => $dashboard_option ) {

						if ( '' !== $dashboard_option ) {
							$checked_user_role_ = array();
							foreach ( $user_roles as $role ) {

								$checked_user_role_[ $role ] = ( in_array(
									$dashboard_option, (array) $disabled_dashboard_option_[ $role ], FALSE
								) ) ? ' checked="checked"' : '';

							}
							echo '<tr>' . "\n";

							// No title on the Dashboard item.
							if ( ! $dashboard_options_names[ $index ] ) {
								$dashboard_options_names[ $index ] = '<b><i>' . esc_attr__(
										'No Title!', 'adminimize'
									) . '</i></b>';
							}
							echo '<td>' . $dashboard_options_names[ $index ] . ' <span>(' . $dashboard_option . ')</span> </td>' . "\n";
							foreach ( $user_roles as $role ) {
								echo '<td class="num">';
								echo '<input id="check_post' . $role . $x . '" class="dashboard_options_'
									. preg_replace( '/[^a-z0-9_-]+/', '', $role ) . '" type="checkbox"'
									. $checked_user_role_[ $role ] . ' name="mw_adminimize_disabled_dashboard_option_'
									. $role . '_items[]" value="' . $dashboard_option . '" />';
								echo '</td>';
							}
							echo '</tr>' . "\n";
							$x ++;
						}

					}
					?>
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
							<textarea name="_mw_adminimize_own_dashboard_options" cols="60" rows="3"
								id="_mw_adminimize_own_dashboard_options" style="width: 95%;"><?php
								echo _mw_adminimize_get_option_value(
									'_mw_adminimize_own_dashboard_options'
								); ?></textarea>
							<br />
							<label for="_mw_adminimize_own_dashboard_options">
								<?php esc_attr_e(
									'Possible nomination for ID or class. Separate multiple nominations through a carriage return.',
									'adminimize'
								); ?>
							</label>
						</td>
						<td>
							<textarea class="code" name="_mw_adminimize_own_dashboard_values" cols="60" rows="3"
								id="_mw_adminimize_own_dashboard_values" style="width: 95%;"><?php
								echo _mw_adminimize_get_option_value(
									'_mw_adminimize_own_dashboard_values'
								); ?></textarea>
							<br />
							<label for="_mw_adminimize_own_dashboard_values">
								<?php esc_attr_e(
									'Possible IDs or classes. Separate multiple values through a carriage return.',
									'adminimize'
								); ?>
							</label>
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

			<?php } // end if else $widgets ?>

			<p>
				<a class="alignright button" href="javascript:void(0);" onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;"><?php esc_attr_e(
						'scroll to top', 'adminimize'
					); ?></a><br class="clear" /></p>

		</div>
	</div>
</div>
