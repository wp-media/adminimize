<?php
/**
 * Backend options, options that works on all back end pages.
 *
 * @package    Adminimize
 * @subpackage Backend Options
 * @author     Frank Bültge
 */

if ( ! function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a part of plugin, not much I can do when called directly.";
	exit;
}
?>

<div id="poststuff" class="ui-sortable meta-box-sortables">
	<div class="postbox">
		<h3 class="hndle ui-sortable-handle" id="backend_options" title="<?php esc_attr_e( 'Click to toggle', 'adminimize' ); ?>"><?php esc_attr_e( 'Backend Options', 'adminimize' ); ?></h3>
		<div class="inside">


			<?php wp_nonce_field( 'mw_adminimize_nonce' ); ?>
			<br class="clear"/>
			<table summary="config" class="widefat">
				<tbody>
				<?php
				if ( _mw_adminimize_is_active_on_multisite() && function_exists( 'is_super_admin' )
				) {
					?>
					<tr valign="top">
						<td><?php esc_attr_e( 'Exclude Super Admin', 'adminimize' ); ?></td>
						<td>
							<?php
							$_mw_adminimize_exclude_super_admin = _mw_adminimize_get_option_value(
								'_mw_adminimize_exclude_super_admin'
							);
							?>
							<label>
								<select name="_mw_adminimize_exclude_super_admin">
									<option value="0"
										<?php
										if ( 0 === $_mw_adminimize_exclude_super_admin ) {
											echo ' selected="selected"';
										}
										?>
									><?php esc_attr_e( 'Default', 'adminimize' ); ?></option>
									<option value="1"
										<?php
										if ( 1 === $_mw_adminimize_exclude_super_admin ) {
											echo ' selected="selected"';
										}
										?>
									><?php esc_attr_e( 'Activate', 'adminimize' ); ?></option>
								</select>
							</label>
							<?php
							esc_attr_e(
								'Exclude the Super Admin on a WP Multisite Install from all limitations of this plugin.',
								'adminimize'
							);
							?>
						</td>
					</tr>
				<?php } ?>
				<tr valign="top">
					<td><?php esc_attr_e( 'User-Info', 'adminimize' ); ?></td>
					<td>
						<?php
						$_mw_adminimize_user_info = _mw_adminimize_get_option_value(
							'_mw_adminimize_user_info'
						);
						?>
						<label>
							<select name="_mw_adminimize_user_info">
								<option value="0"
									<?php
									if ( 0 === $_mw_adminimize_user_info ) {
										echo ' selected="selected"';
									}
									?>
								><?php esc_attr_e( 'Default', 'adminimize' ); ?></option>
								<option value="1"
									<?php
									if ( 1 === $_mw_adminimize_user_info ) {
										echo ' selected="selected"';
									}
									?>
								><?php esc_attr_e( 'Hide', 'adminimize' ); ?></option>
								<option value="2"
									<?php
									if ( 2 === $_mw_adminimize_user_info ) {
										echo ' selected="selected"';
									}
									?>
								><?php esc_attr_e( 'Only logout', 'adminimize' ); ?></option>
								<option value="3"
									<?php
									if ( 3 === $_mw_adminimize_user_info ) {
										echo ' selected="selected"';
									}
									?>
								><?php esc_attr_e( 'User &amp; Logout', 'adminimize' ); ?></option>
							</select>
						</label>
						<?php
						esc_attr_e(
							'The &quot;User-Info-area&quot; is on the top right side of the backend. You can hide or reduced show.',
							'adminimize'
						);
						?>
					</td>
				</tr>
				<?php
				if ( ( '' === $_mw_adminimize_user_info ) || ( 1 === $_mw_adminimize_user_info ) || ( 0 === $_mw_adminimize_user_info ) ) {
					$disabled_item = ' disabled="disabled"';
				}
				?>
				<tr valign="top">
					<td><label
							for="_mw_adminimize_ui_redirect"><?php esc_attr_e( 'Change User-Info, redirect to', 'adminimize' ); ?>
					</td>
					<td>
						<?php
						$_mw_adminimize_ui_redirect = (int) _mw_adminimize_get_option_value(
							'_mw_adminimize_ui_redirect'
						);
						?>
						<select name="_mw_adminimize_ui_redirect" id="_mw_adminimize_ui_redirect"
							<?php
							if ( isset( $disabled_item ) ) {
								// phpcs:disable
								echo $disabled_item;
							}
							?>
						>
							<option value="0"
								<?php
								if ( 0 === $_mw_adminimize_ui_redirect ) {
									echo ' selected="selected"';
								}
								?>
							>
								<?php esc_attr_e( 'Default', 'adminimize' ); ?>
							</option>
							<option value="1"
								<?php
								if ( 1 === $_mw_adminimize_ui_redirect ) {
									echo ' selected="selected"';
								}
								?>
							>
								<?php esc_attr_e( 'Frontpage of the Blog', 'adminimize' ); ?>
							</option>
						</select>
						<?php
						esc_attr_e(
							'When the &quot;User-Info-area&quot; change it, then it is possible to change the redirect.',
							'adminimize'
						);
						?>
					</td>
				</tr>
				<tr valign="top">
					<td><label for="_mw_adminimize_footer"><?php esc_attr_e( 'Footer', 'adminimize' ); ?></label></td>
					<td>
						<?php $_mw_adminimize_footer = (int) _mw_adminimize_get_option_value( '_mw_adminimize_footer' ); ?>
						<select name="_mw_adminimize_footer" id="_mw_adminimize_footer">
							<option value="0"
								<?php
								if ( 0 === $_mw_adminimize_footer ) {
									echo ' selected="selected"';
								}
								?>
							>
								<?php esc_attr_e( 'Default', 'adminimize' ); ?>
							</option>
							<option value="1"
								<?php
								if ( 1 === $_mw_adminimize_footer ) {
									echo ' selected="selected"';
								}
								?>
							>
								<?php esc_attr_e( 'Hide', 'adminimize' ); ?>
							</option>
						</select>
						<?php
						esc_attr_e(
							'The Footer-area can hide, include all links and details.', 'adminimize'
						);
						?>
					</td>
				</tr>
				<tr valign="top">
					<td><?php esc_attr_e( 'Timestamp', 'adminimize' ); ?></td>
					<td>
						<?php
						$_mw_adminimize_timestamp = (int) _mw_adminimize_get_option_value(
							'_mw_adminimize_timestamp'
						);
						?>
						<label>
							<select name="_mw_adminimize_timestamp">
								<option value="0"
									<?php
									if ( 0 === $_mw_adminimize_timestamp ) {
										echo ' selected="selected"';
									}
									?>
								><?php esc_attr_e( 'Default', 'adminimize' ); ?></option>
								<option value="1"
									<?php
									if ( 1 === $_mw_adminimize_timestamp ) {
										echo ' selected="selected"';
									}
									?>
								><?php esc_attr_e( 'Activate', 'adminimize' ); ?></option>
							</select>
						</label>
						<?php
						esc_attr_e(
							'Opens the post timestamp editing fields without you having to click the "Edit" link every time.',
							'adminimize'
						);
						?>
					</td>
				</tr>
				<tr valign="top">
					<td><?php esc_attr_e( 'Category Height', 'adminimize' ); ?></td>
					<td>
						<?php
						$_mw_adminimize_cat_full = (int) _mw_adminimize_get_option_value(
							'_mw_adminimize_cat_full'
						);
						?>
						<label>
							<select name="_mw_adminimize_cat_full">
								<option value="0"
									<?php
									if ( 0 === $_mw_adminimize_cat_full ) {
										echo ' selected="selected"';
									}
									?>
								><?php esc_attr_e( 'Default', 'adminimize' ); ?></option>
								<option value="1"
									<?php
									if ( 1 === $_mw_adminimize_cat_full ) {
										echo ' selected="selected"';
									}
									?>
								><?php esc_attr_e( 'Activate', 'adminimize' ); ?></option>
							</select>
						</label>
						<?php
						esc_attr_e(
							'View the Meta Box with Categories in the full height, no scrollbar or whitespace.',
							'adminimize'
						);
						?>
					</td>
				</tr>
				<tr valign="top">
					<td><?php esc_attr_e( 'Advice in Footer', 'adminimize' ); ?></td>
					<td>
						<?php $_mw_adminimize_advice = (int) _mw_adminimize_get_option_value( '_mw_adminimize_advice' ); ?>
						<label>
							<select name="_mw_adminimize_advice">
								<option value="0"
									<?php
									if ( 0 === $_mw_adminimize_advice ) {
										echo ' selected="selected"';
									}
									?>
								><?php esc_attr_e( 'Default', 'adminimize' ); ?></option>
								<option value="1"
									<?php
									if ( 1 === $_mw_adminimize_advice ) {
										echo ' selected="selected"';
									}
									?>
								><?php esc_attr_e( 'Activate', 'adminimize' ); ?></option>
							</select>
						</label>
						<br>
						<label for="_mw_adminimize_advice_txt"></label>
						<textarea style="width: 85%;" class="code" rows="1" cols="60" name="_mw_adminimize_advice_txt"
						          id="_mw_adminimize_advice_txt"><?php echo _mw_adminimize_get_option_value( '_mw_adminimize_advice_txt' ); ?></textarea>
						<br/>
						<?php
						esc_attr_e(
							'In the Footer you can display an advice for changing the Default-design, (x)HTML is possible.',
							'adminimize'
						);
						?>
						<code>a - (href, title), br, em, strong</code>
					</td>
				</tr>
				<?php
				if ( ! isset( $user_roles ) ) {
					$user_roles = _mw_adminimize_get_all_user_roles();
				}
				// If the dashboard will remove.
				foreach ( $user_roles as $role ) {
					$disabled_menu_[ $role ]    = _mw_adminimize_get_option_value(
						'mw_adminimize_disabled_menu_' . $role . '_items'
					);
					$disabled_submenu_[ $role ] = _mw_adminimize_get_option_value(
						'mw_adminimize_disabled_submenu_' . $role . '_items'
					);
				}

				$disabled_menu_all = array();
				foreach ( $user_roles as $role ) {
					if ( ! isset( $disabled_menu_ ) ) {
						$disabled_menu_[ $role ] = _mw_adminimize_get_option_value(
							'mw_adminimize_disabled_menu_' . $role . '_items'
						);
					}
					if ( ! isset( $disabled_submenu_ ) ) {
						$disabled_submenu_[ $role ] = _mw_adminimize_get_option_value(
							'mw_adminimize_disabled_submenu_' . $role . '_items'
						);
					}

					$disabled_menu_all[] = $disabled_menu_[ $role ];
					$disabled_menu_all[] = $disabled_submenu_[ $role ];
				}

				if ( '' !== $disabled_menu_all ) {
					if ( ! _mw_adminimize_recursive_in_array( 'index.php', $disabled_menu_all ) ) {
						$disabled_item2 = ' disabled="disabled"';
					}
					?>
					<tr valign="top">
						<td><?php esc_attr_e( 'Dashboard deactivate, redirect to', 'adminimize' ); ?></td>
						<td>
							<?php
							$_mw_adminimize_db_redirect = _mw_adminimize_get_option_value(
								'_mw_adminimize_db_redirect'
							);
							?>
							<label>
								<select name="_mw_adminimize_db_redirect"
									<?php
									if ( isset( $disabled_item2 ) ) {
										echo $disabled_item2;
									}
									?>
								>
									<option value="0"
										<?php
										if ( $_mw_adminimize_db_redirect === 0 ) {
											echo ' selected="selected"';
										}
										?>
									><?php esc_attr_e( 'Default', 'adminimize' ); ?> (profile.php)
									</option>
									<option value="1"
										<?php
										if ( $_mw_adminimize_db_redirect === 1 ) {
											echo ' selected="selected"';
										}
										?>
									><?php esc_attr_e( 'Manage Posts', 'adminimize' ); ?> (edit.php)
									</option>
									<option value="2"
										<?php
										if ( $_mw_adminimize_db_redirect === 2 ) {
											echo ' selected="selected"';
										}
										?>
									><?php esc_attr_e( 'Manage Pages', 'adminimize' ); ?> (edit-pages.php)
									</option>
									<option value="3"
										<?php
										if ( $_mw_adminimize_db_redirect === 3 ) {
											echo ' selected="selected"';
										}
										?>
									><?php esc_attr_e( 'Write Post', 'adminimize' ); ?> (post-new.php)
									</option>
									<option value="4"
										<?php
										if ( $_mw_adminimize_db_redirect === 4 ) {
											echo ' selected="selected"';
										}
										?>
									><?php esc_attr_e( 'Write Page', 'adminimize' ); ?> (page-new.php)
									</option>
									<option value="5"
										<?php
										if ( $_mw_adminimize_db_redirect === 5 ) {
											echo ' selected="selected"';
										}
										?>
									><?php esc_attr_e( 'Comments', 'adminimize' ); ?> (edit-comments.php)
									</option>
									<option value="6"
										<?php
										if ( $_mw_adminimize_db_redirect === 6 ) {
											echo ' selected="selected"';
										}
										?>
									><?php esc_attr_e( 'Other Page', 'adminimize' ); ?></option>
								</select>
							</label>
							<br>
							<label for="_mw_adminimize_db_redirect_txt"></label>
							<textarea style="width: 85%;" class="code" rows="1" cols="60" name="_mw_adminimize_db_redirect_txt" id="_mw_adminimize_db_redirect_txt"><?php echo htmlspecialchars( stripslashes( _mw_adminimize_get_option_value( '_mw_adminimize_db_redirect_txt' ) ) ); ?></textarea>
							<br/>
							<?php
							esc_attr_e(
								'You have deactivated the Dashboard, please select a page for redirection or define custom url, include http://?',
								'adminimize'
							);
							?>
						</td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
			<p id="submitbutton">
				<input class="button button-primary" type="submit" name="_mw_adminimize_save" value="
				<?php
				esc_attr_e(
					'Update Options', 'adminimize'
				);
				?>
				 &raquo;"/><input type="hidden" name="page_options" value="'dofollow_timeout'"/>
			</p>
			<p>
				<a class="alignright button adminimize-scroltop" href="#" style="margin:3px 0 0 30px;">
					<?php
					esc_attr_e( 'scroll to top', 'adminimize' );
					?>
				</a><br class="clear"/>
			</p>
		</div>
	</div>
</div>
