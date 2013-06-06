<?php
/**
 * @package Adminimize
 * @subpackage Post Options
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
				<h3 class="hndle" id="config_edit_post"><?php _e('Write options - Post', FB_ADMINIMIZE_TEXTDOMAIN ); ?></h3>
				<div class="inside">
					<br class="clear" />

					<table summary="config_edit_post" class="widefat">
						<thead>
							<tr>
								<th><?php _e('Write options - Post', FB_ADMINIMIZE_TEXTDOMAIN ); ?></th>
								<?php
									foreach ($user_roles_names as $role_name) { ?>
										<th><?php _e('Deactivate for', FB_ADMINIMIZE_TEXTDOMAIN ); echo '<br/>' . $role_name; ?></th>
								<?php } ?>
							</tr>
						</thead>

						<tbody>
						<?php
							$metaboxes = array(
								'#contextual-help-link-wrap',
								'#screen-options-link-wrap',
								'#title, #titlediv, th.column-title, td.title',
								'#pageslugdiv',
								'#tags, #tagsdiv,#tagsdivsb,#tagsdiv-post_tag, th.column-tags, td.tags',
								'#categories, #categorydiv, #categorydivsb, th.column-categories, td.categories',
								'#category-add-toggle',
								'#date, #datediv, th.column-date, td.date, div.curtime',
								'#passworddiv',
								'.side-info',
								'#notice',
								'#post-body h2',
								'#media-buttons, #wp-content-media-buttons',
								'#wp-word-count',
								'#slugdiv,#edit-slug-box',
								'#misc-publishing-actions',
								'#commentstatusdiv',
								'#editor-toolbar #edButtonHTML, #quicktags, #content-html, .wp-switch-editor.switch-html'
							);
							
							$post_type = 'post';
							foreach ( $GLOBALS['_wp_post_type_features'][$post_type] as $post_type_support => $key ) {
								if ( post_type_supports( $post_type, $post_type_support ) )
									if ( 'excerpt' === $post_type_support )
										$post_type_support = $post_type . 'excerpt';
									if ( 'page-attributes' === $post_type_support )
										$post_type_support = 'pageparentdiv';
									if ( 'custom-fields' == $post_type_support )
										$post_type_support = $post_type . 'custom';
									if ( 'post-formats' === $post_type_support )
										$post_type_support = 'format';
									array_push( 
										$metaboxes, 
										'#' . $post_type_support . ', #' . $post_type_support . 'div, th.column-' . $post_type_support . ', td.' . $post_type_support
									); //th and td for raw in edit screen
							}

							if ( function_exists('current_theme_supports') && current_theme_supports( 'post-thumbnails', 'post' ) )
								array_push($metaboxes, '#postimagediv');

							// quick edit areas, id and class
							$quickedit_areas = array(
								'div.row-actions, div.row-actions .inline',
								'fieldset.inline-edit-col-left',
								'fieldset.inline-edit-col-left label',
								'fieldset.inline-edit-col-left label.inline-edit-author',
								'fieldset.inline-edit-col-left .inline-edit-group',
								'fieldset.inline-edit-col-center',
								'fieldset.inline-edit-col-center .inline-edit-categories-label',
								'fieldset.inline-edit-col-center .category-checklist',
								'fieldset.inline-edit-col-right',
								'fieldset.inline-edit-col-right .inline-edit-tags',
								'fieldset.inline-edit-col-right .inline-edit-group',
								'tr.inline-edit-post p.inline-edit-save'
							);
							$metaboxes = array_merge($metaboxes, $quickedit_areas);

							$metaboxes_names = array(
								__('Help'),
								__('Screen Options'),
								__('Title', FB_ADMINIMIZE_TEXTDOMAIN),
								__('Permalink', FB_ADMINIMIZE_TEXTDOMAIN ),
								__('Tags', FB_ADMINIMIZE_TEXTDOMAIN ),
								__('Categories', FB_ADMINIMIZE_TEXTDOMAIN ),
								__('Add New Category', FB_ADMINIMIZE_TEXTDOMAIN ),
								__('Date'),
								__('Password Protect This Post', FB_ADMINIMIZE_TEXTDOMAIN ),
								__('Related, Shortcuts', FB_ADMINIMIZE_TEXTDOMAIN ),
								__('Messages', FB_ADMINIMIZE_TEXTDOMAIN ),
								__('h2: Advanced Options', FB_ADMINIMIZE_TEXTDOMAIN ),
								__('Media Buttons (all)', FB_ADMINIMIZE_TEXTDOMAIN ),
								__('Word count', FB_ADMINIMIZE_TEXTDOMAIN ),
								__('Post Slug', FB_ADMINIMIZE_TEXTDOMAIN),
								__('Publish Actions', FB_ADMINIMIZE_TEXTDOMAIN ),
								__('Discussion'),
								__('HTML Editor Button')
							);
							
							foreach ( $GLOBALS['_wp_post_type_features'][$post_type] as $post_type_support => $key ) {
								if ( post_type_supports( $post_type, $post_type_support ) )
									array_push( $metaboxes_names, ucfirst($post_type_support) );
							}
							
							if ( function_exists('current_theme_supports') && current_theme_supports( 'post-thumbnails', 'post' ) )
								array_push($metaboxes_names, __('Post Thumbnail', FB_ADMINIMIZE_TEXTDOMAIN) );
							
							// quick edit names
							$quickedit_names = array(
								'<strong>' .__('Quick Edit Link', FB_ADMINIMIZE_TEXTDOMAIN) . '</strong>',
								__('QE', FB_ADMINIMIZE_TEXTDOMAIN) . ' ' . __('Inline Edit Left', FB_ADMINIMIZE_TEXTDOMAIN),
								'&emsp;QE &rArr;' . ' ' . __('All Labels', FB_ADMINIMIZE_TEXTDOMAIN),
								'&emsp;QE &rArr;' . ' ' . __('Author'),
								'&emsp;QE &rArr;' . ' ' . __('Password and Private', FB_ADMINIMIZE_TEXTDOMAIN),
								__('QE', FB_ADMINIMIZE_TEXTDOMAIN) . ' ' . __('Inline Edit Center', FB_ADMINIMIZE_TEXTDOMAIN),
								'&emsp;QE &rArr;' . ' ' . __('Categories Title', FB_ADMINIMIZE_TEXTDOMAIN),
								'&emsp;QE &rArr;' . ' ' . __('Categories List', FB_ADMINIMIZE_TEXTDOMAIN),
								__('QE', FB_ADMINIMIZE_TEXTDOMAIN) . ' ' . __('Inline Edit Right', FB_ADMINIMIZE_TEXTDOMAIN),
								'&emsp;QE &rArr;' . ' ' . __('Tags'),
								'&emsp;QE &rArr;' . ' ' . __('Status, Sticky', FB_ADMINIMIZE_TEXTDOMAIN),
								__('QE', FB_ADMINIMIZE_TEXTDOMAIN) . ' ' . __('Cancel/Save Button', FB_ADMINIMIZE_TEXTDOMAIN)
							);
							$metaboxes_names = array_merge($metaboxes_names, $quickedit_names);
							
							// add own post options
							$_mw_adminimize_own_post_values  = _mw_adminimize_get_option_value('_mw_adminimize_own_post_values');
							$_mw_adminimize_own_post_values = preg_split( "/\r\n/", $_mw_adminimize_own_post_values );
							foreach ( (array) $_mw_adminimize_own_post_values as $key => $_mw_adminimize_own_post_value ) {
								$_mw_adminimize_own_post_value = trim($_mw_adminimize_own_post_value);
								array_push($metaboxes, $_mw_adminimize_own_post_value);
							}
							
							$_mw_adminimize_own_post_options = _mw_adminimize_get_option_value('_mw_adminimize_own_post_options');
							$_mw_adminimize_own_post_options = preg_split( "/\r\n/", $_mw_adminimize_own_post_options );
							foreach ( (array) $_mw_adminimize_own_post_options as $key => $_mw_adminimize_own_post_option ) {
								$_mw_adminimize_own_post_option = trim($_mw_adminimize_own_post_option);
								array_push($metaboxes_names, $_mw_adminimize_own_post_option);
							}
						
							$x = 0;
							$class = '';
							foreach ($metaboxes as $index => $metabox) {
								if ($metabox != '') {
									$class = ( ' class="alternate"' == $class ) ? '' : ' class="alternate"';
									$checked_user_role_ = array();
									foreach ($user_roles as $role) {
										$checked_user_role_[$role]  = ( isset($disabled_metaboxes_post_[$role]) && in_array($metabox, $disabled_metaboxes_post_[$role]) ) ? ' checked="checked"' : '';
									}
									echo '<tr' . $class . '>' . "\n";
									echo '<td>' . $metaboxes_names[$index] . ' <span style="color:#ccc; font-weight: 400;">(' . $metabox . ')</span> </td>' . "\n";
									foreach ($user_roles as $role) {
										echo '<td class="num"><input id="check_post'. $role.$x .'" type="checkbox"' . $checked_user_role_[$role] . ' name="mw_adminimize_disabled_metaboxes_post_'. $role .'_items[]" value="' . $metabox . '" /></td>' . "\n";
									}
									echo '</tr>' . "\n";
									$x++;
								}
							}
						?>
						</tbody>
					</table>
					
					<?php
					//your own post options
					?>
					<br style="margin-top: 10px;" />
					<table summary="config_own_post" class="widefat">
						<thead>
							<tr>
								<th><?php _e('Your own post options', FB_ADMINIMIZE_TEXTDOMAIN ); echo '<br />'; _e('ID or class', FB_ADMINIMIZE_TEXTDOMAIN ); ?></th>
								<th><?php echo '<br />'; _e('Option', FB_ADMINIMIZE_TEXTDOMAIN ); ?></th>
							</tr>
						</thead>

						<tbody>
							<tr valign="top">
								<td colspan="2"><?php _e('It is possible to add your own IDs or classes from elements and tags. You can find IDs and classes with the FireBug Add-on for Firefox. Assign a value and the associate name per line.', FB_ADMINIMIZE_TEXTDOMAIN ); ?></td>
							</tr>
							<tr valign="top">
								<td>
									<textarea name="_mw_adminimize_own_post_options" cols="60" rows="3" id="_mw_adminimize_own_post_options" style="width: 95%;" ><?php echo _mw_adminimize_get_option_value('_mw_adminimize_own_post_options'); ?></textarea>
									<br />
									<?php _e('Possible nomination for ID or class. Separate multiple nominations through a carriage return.', FB_ADMINIMIZE_TEXTDOMAIN ); ?>
								</td>
								<td>
									<textarea class="code" name="_mw_adminimize_own_post_values" cols="60" rows="3" id="_mw_adminimize_own_post_values" style="width: 95%;" ><?php echo _mw_adminimize_get_option_value('_mw_adminimize_own_post_values'); ?></textarea>
									<br />
									<?php _e('Possible IDs or classes. Separate multiple values through a carriage return.', FB_ADMINIMIZE_TEXTDOMAIN ); ?>
								</td>
							</tr>
						</tbody>
					</table>
					
					<p id="submitbutton">
						<input type="hidden" name="_mw_adminimize_action" value="_mw_adminimize_insert" />
						<input class="button button-primary" type="submit" name="_mw_adminimize_save" value="<?php _e('Update Options', FB_ADMINIMIZE_TEXTDOMAIN ); ?> &raquo;" /><input type="hidden" name="page_options" value="'dofollow_timeout'" />
					</p>
					<p><a class="alignright button" href="javascript:void(0);" onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;"><?php _e('scroll to top', FB_ADMINIMIZE_TEXTDOMAIN); ?></a><br class="clear" /></p>

				</div>
			</div>
		</div>
