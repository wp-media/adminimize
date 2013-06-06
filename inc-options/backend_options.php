<?php
/**
 * @package Adminimize
 * @subpackage Backend Options
 * @author Frank Bültge
 */
if ( ! function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a part of plugin, not much I can do when called directly.";
	exit;
}
?>

		<div id="poststuff" class="ui-sortable meta-box-sortables">
			<div class="postbox">
				<div class="handlediv" title="<?php _e('Click to toggle'); ?>"><br/></div>
				<h3 class="hndle" id="backend_options"><?php _e('Backend Options', FB_ADMINIMIZE_TEXTDOMAIN ); ?></h3>
				<div class="inside">

				
					<?php wp_nonce_field('mw_adminimize_nonce'); ?>
					<br class="clear" />
					<table summary="config" class="widefat">
						<tbody>
							<?php if ( is_multisite() && is_plugin_active_for_network( MW_ADMIN_FILE ) && function_exists('is_super_admin') ) { ?>
							<!--
							<tr valign="top" class="form-invalid">
								<td><?php _e( 'Use Global Settings', FB_ADMINIMIZE_TEXTDOMAIN ); ?></td>
								<td>
									<?php
									$mw_adminimize_use_global = '0';
									$select_active = '';
									$message = '';
									if ( is_multisite() && is_plugin_active_for_network( MW_ADMIN_FILE ) ) {
										$mw_adminimize_use_global = 1;
										$select_active = ' disabled="disabled"';
										$message = __( 'The plugin is active in multiste.', FB_ADMINIMIZE_TEXTDOMAIN );
									}
									$mw_adminimize_use_global = get_option( 'mw_adminimize_use_global' ); ?>
									<select name="_mw_adminimize_use_global"<?php echo $select_active; ?>>
										<option value="0"<?php if ( '0' === $mw_adminimize_use_global ) { echo ' selected="selected"'; } ?>><?php _e( 'False', FB_ADMINIMIZE_TEXTDOMAIN ); ?></option>
										<option value="1"<?php if ( '1' === $mw_adminimize_use_global ) { echo ' selected="selected"'; } ?>><?php _e('True', FB_ADMINIMIZE_TEXTDOMAIN ); ?></option>
									</select> <?php _e('Use the settings global in your Multisite network.', FB_ADMINIMIZE_TEXTDOMAIN ); echo ' ' . $message; ?>
								</td>
							</tr>
							-->
							<tr valign="top" class="form-invalid">
								<td><?php _e('Exclude Super Admin', FB_ADMINIMIZE_TEXTDOMAIN ); ?></td>
								<td>
									<?php $_mw_adminimize_exclude_super_admin = _mw_adminimize_get_option_value('_mw_adminimize_exclude_super_admin'); ?>
									<select name="_mw_adminimize_exclude_super_admin">
										<option value="0"<?php if ($_mw_adminimize_exclude_super_admin == '0') { echo ' selected="selected"'; } ?>><?php _e('Default', FB_ADMINIMIZE_TEXTDOMAIN ); ?></option>
										<option value="1"<?php if ($_mw_adminimize_exclude_super_admin == '1') { echo ' selected="selected"'; } ?>><?php _e('Activate', FB_ADMINIMIZE_TEXTDOMAIN ); ?></option>
									</select> <?php _e('Exclude the Super Admin on a WP Multisite Install from all limitations of this plugin.', FB_ADMINIMIZE_TEXTDOMAIN ); ?>
								</td>
							</tr>
							<?php } ?>
							<tr valign="top">
								<td><?php _e('User-Info', FB_ADMINIMIZE_TEXTDOMAIN ); ?></td>
								<td>
									<?php $_mw_adminimize_user_info = _mw_adminimize_get_option_value('_mw_adminimize_user_info'); ?>
									<select name="_mw_adminimize_user_info">
										<option value="0"<?php if ($_mw_adminimize_user_info == '0') { echo ' selected="selected"'; } ?>><?php _e('Default', FB_ADMINIMIZE_TEXTDOMAIN ); ?></option>
										<option value="1"<?php if ($_mw_adminimize_user_info == '1') { echo ' selected="selected"'; } ?>><?php _e('Hide', FB_ADMINIMIZE_TEXTDOMAIN ); ?></option>
										<option value="2"<?php if ($_mw_adminimize_user_info == '2') { echo ' selected="selected"'; } ?>><?php _e('Only logout', FB_ADMINIMIZE_TEXTDOMAIN ); ?></option>
										<option value="3"<?php if ($_mw_adminimize_user_info == '3') { echo ' selected="selected"'; } ?>><?php _e('User &amp; Logout', FB_ADMINIMIZE_TEXTDOMAIN ); ?></option>
									</select> <?php _e('The &quot;User-Info-area&quot; is on the top right side of the backend. You can hide or reduced show.', FB_ADMINIMIZE_TEXTDOMAIN ); ?>
								</td>
							</tr>
							<?php if ( ($_mw_adminimize_user_info == '') || ($_mw_adminimize_user_info == '1') || ($_mw_adminimize_user_info == '0') ) $disabled_item = ' disabled="disabled"' ?>
							<tr valign="top" class="form-invalid">
								<td><?php _e('Change User-Info, redirect to', FB_ADMINIMIZE_TEXTDOMAIN ); ?></td>
								<td>
									<?php $_mw_adminimize_ui_redirect = _mw_adminimize_get_option_value('_mw_adminimize_ui_redirect'); ?>
									<select name="_mw_adminimize_ui_redirect" <?php if ( isset($disabled_item) ) echo $disabled_item; ?>>
										<option value="0"<?php if ($_mw_adminimize_ui_redirect == '0') { echo ' selected="selected"'; } ?>><?php _e('Default', FB_ADMINIMIZE_TEXTDOMAIN ); ?></option>
										<option value="1"<?php if ($_mw_adminimize_ui_redirect == '1') { echo ' selected="selected"'; } ?>><?php _e('Frontpage of the Blog', FB_ADMINIMIZE_TEXTDOMAIN ); ?>
									</select> <?php _e('When the &quot;User-Info-area&quot; change it, then it is possible to change the redirect.', FB_ADMINIMIZE_TEXTDOMAIN ); ?>
								</td>
							</tr>
							<tr valign="top">
								<td><?php _e('Footer', FB_ADMINIMIZE_TEXTDOMAIN ); ?></td>
								<td>
									<?php $_mw_adminimize_footer = _mw_adminimize_get_option_value('_mw_adminimize_footer'); ?>
									<select name="_mw_adminimize_footer">
										<option value="0"<?php if ($_mw_adminimize_footer == '0') { echo ' selected="selected"'; } ?>><?php _e('Default', FB_ADMINIMIZE_TEXTDOMAIN ); ?></option>
										<option value="1"<?php if ($_mw_adminimize_footer == '1') { echo ' selected="selected"'; } ?>><?php _e('Hide', FB_ADMINIMIZE_TEXTDOMAIN ); ?></option>
									</select> <?php _e('The Footer-area can hide, include all links and details.', FB_ADMINIMIZE_TEXTDOMAIN ); ?>
								</td>
							</tr>
							<?php 
							// not usable from WP 3.5
							if ( version_compare( $wp_version, '3.5alpha', '<' ) ) { ?>
							<tr valign="top">
								<td><?php _e('Header', FB_ADMINIMIZE_TEXTDOMAIN ); ?></td>
								<td>
									<?php $_mw_adminimize_header = _mw_adminimize_get_option_value('_mw_adminimize_header'); ?>
									<select name="_mw_adminimize_header">
										<option value="0"<?php if ($_mw_adminimize_header == '0') { echo ' selected="selected"'; } ?>><?php _e('Default', FB_ADMINIMIZE_TEXTDOMAIN ); ?></option>
										<option value="1"<?php if ($_mw_adminimize_header == '1') { echo ' selected="selected"'; } ?>><?php _e('Hide', FB_ADMINIMIZE_TEXTDOMAIN ); ?></option>
									</select> <?php _e('The Header-area can hide, include all links and details.', FB_ADMINIMIZE_TEXTDOMAIN ); ?>
								</td>
							</tr>
							<?php } // end if < wp 3-6 ?>
							<tr valign="top">
								<td><?php _e('Timestamp', FB_ADMINIMIZE_TEXTDOMAIN ); ?></td>
								<td>
									<?php $_mw_adminimize_timestamp = _mw_adminimize_get_option_value('_mw_adminimize_timestamp'); ?>
									<select name="_mw_adminimize_timestamp">
										<option value="0"<?php if ($_mw_adminimize_timestamp == '0') { echo ' selected="selected"'; } ?>><?php _e('Default', FB_ADMINIMIZE_TEXTDOMAIN ); ?></option>
										<option value="1"<?php if ($_mw_adminimize_timestamp == '1') { echo ' selected="selected"'; } ?>><?php _e('Activate', FB_ADMINIMIZE_TEXTDOMAIN ); ?></option>
									</select> <?php _e('Opens the post timestamp editing fields without you having to click the "Edit" link every time.', FB_ADMINIMIZE_TEXTDOMAIN ); ?>
								</td>
							</tr>
							<?php 
							// not usable from WP 3.5
							if ( version_compare( $wp_version, '3.5alpha', '<' ) ) { ?>
							<tr valign="top">
								<td><?php _e('Thickbox FullScreen', FB_ADMINIMIZE_TEXTDOMAIN ); ?></td>
								<td>
									<?php $_mw_adminimize_tb_window = _mw_adminimize_get_option_value('_mw_adminimize_tb_window'); ?>
									<select name="_mw_adminimize_tb_window">
										<option value="0"<?php if ($_mw_adminimize_tb_window == '0') { echo ' selected="selected"'; } ?>><?php _e('Default', FB_ADMINIMIZE_TEXTDOMAIN ); ?></option>
										<option value="1"<?php if ($_mw_adminimize_tb_window == '1') { echo ' selected="selected"'; } ?>><?php _e('Activate', FB_ADMINIMIZE_TEXTDOMAIN ); ?></option>
									</select> <?php _e('All Thickbox-function use the full area of the browser. Thickbox is for example in upload media-files.', FB_ADMINIMIZE_TEXTDOMAIN ); ?>
								</td>
							</tr>
							<tr valign="top">
								<td><?php _e('Flashuploader', FB_ADMINIMIZE_TEXTDOMAIN ); ?></td>
								<td>
									<?php $_mw_adminimize_control_flashloader = _mw_adminimize_get_option_value('_mw_adminimize_control_flashloader'); ?>
									<select name="_mw_adminimize_control_flashloader">
										<option value="0"<?php if ($_mw_adminimize_control_flashloader == '0') { echo ' selected="selected"'; } ?>><?php _e('Default', FB_ADMINIMIZE_TEXTDOMAIN ); ?></option>
										<option value="1"<?php if ($_mw_adminimize_control_flashloader == '1') { echo ' selected="selected"'; } ?>><?php _e('Activate', FB_ADMINIMIZE_TEXTDOMAIN ); ?></option>
									</select> <?php _e('Disable the flashuploader and users use only the standard uploader.', FB_ADMINIMIZE_TEXTDOMAIN ); ?>
								</td>
							</tr>
							<?php } // end if < wp 3-6 ?>
							<tr valign="top">
								<td><?php _e('Category Height', FB_ADMINIMIZE_TEXTDOMAIN ); ?></td>
								<td>
									<?php $_mw_adminimize_cat_full = _mw_adminimize_get_option_value('_mw_adminimize_cat_full'); ?>
									<select name="_mw_adminimize_cat_full">
										<option value="0"<?php if ($_mw_adminimize_cat_full == '0') { echo ' selected="selected"'; } ?>><?php _e('Default', FB_ADMINIMIZE_TEXTDOMAIN ); ?></option>
										<option value="1"<?php if ($_mw_adminimize_cat_full == '1') { echo ' selected="selected"'; } ?>><?php _e('Activate', FB_ADMINIMIZE_TEXTDOMAIN ); ?></option>
									</select> <?php _e('View the Meta Box with Categories in the full height, no scrollbar or whitespace.', FB_ADMINIMIZE_TEXTDOMAIN ); ?>
								</td>
							</tr>
							<tr valign="top">
								<td><?php _e('Advice in Footer', FB_ADMINIMIZE_TEXTDOMAIN ); ?></td>
								<td>
									<?php $_mw_adminimize_advice = _mw_adminimize_get_option_value('_mw_adminimize_advice'); ?>
									<select name="_mw_adminimize_advice">
										<option value="0"<?php if ($_mw_adminimize_advice == '0') { echo ' selected="selected"'; } ?>><?php _e('Default', FB_ADMINIMIZE_TEXTDOMAIN ); ?></option>
										<option value="1"<?php if ($_mw_adminimize_advice == '1') { echo ' selected="selected"'; } ?>><?php _e('Activate', FB_ADMINIMIZE_TEXTDOMAIN ); ?></option>
									</select>
									<textarea style="width: 85%;" class="code" rows="1" cols="60" name="_mw_adminimize_advice_txt" id="_mw_adminimize_advice_txt" ><?php echo htmlspecialchars(stripslashes(_mw_adminimize_get_option_value('_mw_adminimize_advice_txt'))); ?></textarea><br /><?php _e('In the Footer you can display an  advice for changing the Default-design, (x)HTML is possible.', FB_ADMINIMIZE_TEXTDOMAIN ); ?>
								</td>
							</tr>
							<?php
							// when remove dashboard
							foreach ($user_roles as $role) {
								$disabled_menu_[$role]    = _mw_adminimize_get_option_value('mw_adminimize_disabled_menu_'. $role .'_items');
								$disabled_submenu_[$role] = _mw_adminimize_get_option_value('mw_adminimize_disabled_submenu_'. $role .'_items');
							}
							
							$disabled_menu_all = array();
							foreach ($user_roles as $role) {
								array_push($disabled_menu_all, $disabled_menu_[$role]);
								array_push($disabled_menu_all, $disabled_submenu_[$role]);
							}

							if ( '' != $disabled_menu_all ) {
								if ( ! _mw_adminimize_recursive_in_array('index.php', $disabled_menu_all) ) {
									$disabled_item2 = ' disabled="disabled"';
								}
								?>
								<tr valign="top" class="form-invalid">
									<td><?php _e('Dashboard deactivate, redirect to', FB_ADMINIMIZE_TEXTDOMAIN ); ?></td>
									<td>
										<?php $_mw_adminimize_db_redirect = _mw_adminimize_get_option_value('_mw_adminimize_db_redirect'); ?>
										<select name="_mw_adminimize_db_redirect"<?php if ( isset($disabled_item2) ) echo $disabled_item2; ?>>
											<option value="0"<?php if ($_mw_adminimize_db_redirect == '0') { echo ' selected="selected"'; } ?>><?php _e('Default', FB_ADMINIMIZE_TEXTDOMAIN ); ?> (profile.php)</option>
											<option value="1"<?php if ($_mw_adminimize_db_redirect == '1') { echo ' selected="selected"'; } ?>><?php _e('Manage Posts', FB_ADMINIMIZE_TEXTDOMAIN ); ?> (edit.php)</option>
											<option value="2"<?php if ($_mw_adminimize_db_redirect == '2') { echo ' selected="selected"'; } ?>><?php _e('Manage Pages', FB_ADMINIMIZE_TEXTDOMAIN ); ?> (edit-pages.php)</option>
											<option value="3"<?php if ($_mw_adminimize_db_redirect == '3') { echo ' selected="selected"'; } ?>><?php _e('Write Post', FB_ADMINIMIZE_TEXTDOMAIN ); ?> (post-new.php)</option>
											<option value="4"<?php if ($_mw_adminimize_db_redirect == '4') { echo ' selected="selected"'; } ?>><?php _e('Write Page', FB_ADMINIMIZE_TEXTDOMAIN ); ?> (page-new.php)</option>
											<option value="5"<?php if ($_mw_adminimize_db_redirect == '5') { echo ' selected="selected"'; } ?>><?php _e('Comments', FB_ADMINIMIZE_TEXTDOMAIN ); ?> (edit-comments.php)</option>
											<option value="6"<?php if ($_mw_adminimize_db_redirect == '6') { echo ' selected="selected"'; } ?>><?php _e('other Page', FB_ADMINIMIZE_TEXTDOMAIN ); ?></option>
										</select>
										<textarea style="width: 85%;" class="code" rows="1" cols="60" name="_mw_adminimize_db_redirect_txt" id="_mw_adminimize_db_redirect_txt" ><?php echo htmlspecialchars(stripslashes(_mw_adminimize_get_option_value('_mw_adminimize_db_redirect_txt'))); ?></textarea>
										<br /><?php _e('You have deactivated the Dashboard, please select a page for redirection or define custom url, include http://?', FB_ADMINIMIZE_TEXTDOMAIN ); ?>
									</td>
								</tr>
								<?php
							}
							?>
						</tbody>
					</table>
					<p id="submitbutton">
						<input class="button button-primary" type="submit" name="_mw_adminimize_save" value="<?php _e('Update Options', FB_ADMINIMIZE_TEXTDOMAIN ); ?> &raquo;" /><input type="hidden" name="page_options" value="'dofollow_timeout'" />
					</p>
					<p><a class="alignright button" href="javascript:void(0);" onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;"><?php _e('scroll to top', FB_ADMINIMIZE_TEXTDOMAIN); ?></a><br class="clear" /></p>

				</div>
			</div>
		</div>
		