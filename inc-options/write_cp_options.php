<?php
/**
 * @package    Adminimize
 * @subpackage Custom Post type options
 * @author     Frank Bültge
 */

if ( ! function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a part of plugin, not much I can do when called directly.";
	exit;
}
// reset
$post_type = '';
$args      = array( 'public' => TRUE, '_builtin' => FALSE );
foreach ( get_post_types( $args ) as $post_type ) {
	$post_type_object = get_post_type_object( $post_type );
	?>

	<div id="poststuff" class="ui-sortable meta-box-sortables">
		<div class="postbox">
			<div class="handlediv" title="<?php esc_attr_e( 'Click to toggle', 'adminimize' ); ?>"><br /></div>
			<h3 class="hndle" id="config_edit_<?php echo $post_type; ?>">
				<?php esc_attr_e( 'Write options', 'adminimize' );
				echo ' - ' . $post_type_object->label; ?>
			</h3>

			<div class="inside">
				<br class="clear" />

				<table summary="config_edit_post" class="widefat">
					<colgroup>
						<?php
						$col = 0;
						foreach ( (array) $user_roles_names as $role_name ) {
							echo '<col class="col' . (int) $col . '">' . "\n";
							$col ++;
						}
						?>
					</colgroup>
					<thead>
					<tr>
						<th><?php esc_attr_e( 'Write options', 'adminimize' );
							echo ' - ' . $post_type_object->label ?></th>
						<?php
						foreach ( (array) $user_roles_names as $role_name ) {
							echo '<th>' . esc_attr_e( 'Deactivate for', 'adminimize' )
						. '<br/>' . esc_attr( $role_name ) . '</th>';
						} ?>
					</tr>
					<tr>
						<td><?php esc_attr_e( 'Select all', 'adminimize' ); ?></td>
						<?php
						foreach ( (array) $user_roles as $role_slug ) {
							echo '<td class="num"><input id="select_all" class="write_cp_options_'
							     . esc_attr( $post_type ) . '_' . esc_attr( $role_slug )
								. '" type="checkbox" name="" value="" /></td>' . "\n";
						} ?>
					</tr>
					</thead>

					<tbody>
					<?php
					$metaboxes = array(
						'#contextual-help-link-wrap',
						'#screen-options-link-wrap',
						'.page-title-action',
						'#pageslugdiv',
						'#tagsdiv,#tagsdivsb,#tagsdiv-post_tag',
						'#formatdiv',
						'#categorydiv,#categorydivsb',
						'#category-add-toggle',
						'#passworddiv',
						'.side-info',
						'#notice',
						'#post-body h2',
						'#media-buttons, #wp-content-media-buttons',
						'#wp-word-count',
						'#slugdiv,#edit-slug-box',
						'#misc-publishing-actions',
						'#commentstatusdiv',
						'#editor-toolbar #edButtonHTML, #quicktags, #content-html',
					);

					if ( ! empty( $GLOBALS[ '_wp_post_type_features' ][ $post_type ] ) ) {
						foreach ( $GLOBALS[ '_wp_post_type_features' ][ $post_type ] as $post_type_support => $key ) {

							if ( post_type_supports( $post_type, $post_type_support ) && 'excerpt' === $post_type_support ) {
								$post_type_support = 'postexcerpt';
							}

							if ( 'page-attributes' === $post_type_support ) {
								$post_type_support = 'pageparentdiv';
							}

							if ( 'custom-fields' === $post_type_support ) {
								$post_type_support = 'postcustom';
							}

							$metaboxes[] = '#' . $post_type_support
								. ', #' . $post_type_support
								. 'div, th.column-' . $post_type_support
								. ', td.' . $post_type_support; // td for raw in edit screen
						}
					}

					if ( function_exists( 'current_theme_supports' )
						&& current_theme_supports(
							'post-thumbnails', $post_type
						)
					) {
						$metaboxes[] = '#postimagediv';
					}
					if ( function_exists( 'sticky_add_meta_box' ) ) {
						$metaboxes[] = '#poststickystatusdiv';
					}

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
						'tr.inline-edit-save p.inline-edit-save',
					);
					$metaboxes       = array_merge( $metaboxes, $quickedit_areas );

					$metaboxes_names = array(
						esc_attr__( 'Help', 'adminimize' ),
						esc_attr__( 'Screen Options', 'adminimize' ),
						esc_attr__( 'Add New', 'adminimize' ),
						esc_attr__( 'Permalink', 'adminimize' ),
						esc_attr__( 'Tags', 'adminimize' ),
						esc_attr__( 'Format', 'adminimize' ),
						esc_attr__( 'Categories', 'adminimize' ),
						esc_attr__( 'Add New Category', 'adminimize' ),
						esc_attr__( 'Password Protect This Post', 'adminimize' ),
						esc_attr__( 'Related, Shortcuts', 'adminimize' ),
						esc_attr__( 'Messages', 'adminimize' ),
						esc_attr__( 'h2: Advanced Options', 'adminimize' ),
						esc_attr__( 'Media Buttons (all)', 'adminimize' ),
						esc_attr__( 'Word count', 'adminimize' ),
						esc_attr__( 'Post Slug', 'adminimize' ),
						esc_attr__( 'Publish Actions', 'adminimize' ),
						esc_attr__( 'Discussion', 'adminimize' ),
						esc_attr__( 'HTML Editor Button', 'adminimize' ),
					);

					if ( ! empty( $GLOBALS[ '_wp_post_type_features' ][ $post_type ] ) ) {
						foreach ( $GLOBALS[ '_wp_post_type_features' ][ $post_type ] as $post_type_support => $key ) {
							if ( post_type_supports( $post_type, $post_type_support ) ) {
								$metaboxes_names[] = ucfirst( $post_type_support );
							}
						}
					}

					if ( function_exists( 'current_theme_supports' )
						&& current_theme_supports(
							'post-thumbnails', 'post'
						)
					) {
						$metaboxes_names[] = esc_attr__( 'Post Thumbnail', 'adminimize' );
					}

					if ( function_exists( 'sticky_add_meta_box' ) ) {
						$metaboxes_names[] = 'Post Sticky Status';
					}

					// quick edit names
					$quickedit_names = array(
						'<strong>' . esc_attr__( 'Quick Edit Link', 'adminimize' ) . '</strong>',
						esc_attr__( 'QE', 'adminimize' ) . ' ' . esc_attr__( 'Inline Edit Left', 'adminimize' ),
						'&emsp;' . esc_attr__( 'QE', 'adminimize' ) . ' &rArr;' . ' ' . esc_attr__( 'All Labels', 'adminimize' ),
						'&emsp;' . esc_attr__( 'QE', 'adminimize' ) . ' &rArr;' . ' ' . esc_attr__( 'Author' ),
						'&emsp;' . esc_attr__( 'QE', 'adminimize' ) . ' &rArr;' . ' ' . esc_attr__( 'Password and Private', 'adminimize' ),
						esc_attr__( 'QE', 'adminimize' ) . ' ' . esc_attr__( 'Inline Edit Center', 'adminimize' ),
						'&emsp;' . esc_attr__( 'QE', 'adminimize' ) . ' &rArr;' . ' ' . esc_attr__( 'Categories Title', 'adminimize' ),
						'&emsp;' . esc_attr__( 'QE', 'adminimize' ) . ' &rArr;' . ' ' . esc_attr__( 'Categories List', 'adminimize' ),
						esc_attr__( 'QE', 'adminimize' ) . ' ' . esc_attr__( 'Inline Edit Right', 'adminimize' ),
						'&emsp;' . esc_attr__( 'QE', 'adminimize' ) . ' &rArr;' . ' ' . esc_attr__( 'Tags' ),
						'&emsp;' . esc_attr__( 'QE', 'adminimize' ) . ' &rArr;' . ' ' . esc_attr__( 'Status, Sticky', 'adminimize' ),
						esc_attr__( 'QE', 'adminimize' ) . ' ' . esc_attr__( 'Cancel/Save Button', 'adminimize' ),
					);
					$metaboxes_names = array_merge( $metaboxes_names, $quickedit_names );

					// add own post options
					$_mw_adminimize_own_values_[ $post_type ] = _mw_adminimize_get_option_value(
						'_mw_adminimize_own_values_' . $post_type
					);
					$_mw_adminimize_own_values_[ $post_type ] = preg_split(
						"/\r\n/", $_mw_adminimize_own_values_[ $post_type ]
					);
					foreach ( (array) $_mw_adminimize_own_values_[ $post_type ] as $key => $_mw_adminimize_own_value_[ $post_type ] ) {
						$_mw_adminimize_own_value_[ $post_type ] = trim( $_mw_adminimize_own_value_[ $post_type ] );
						$metaboxes[] = $_mw_adminimize_own_value_[ $post_type ];
					}

					$_mw_adminimize_own_options_[ $post_type ] = _mw_adminimize_get_option_value(
						'_mw_adminimize_own_options_' . $post_type
					);
					$_mw_adminimize_own_options_[ $post_type ] = preg_split(
						"/\r\n/", $_mw_adminimize_own_options_[ $post_type ]
					);
					foreach ( (array) $_mw_adminimize_own_options_[ $post_type ] as $key => $_mw_adminimize_own_option_[ $post_type ] ) {
						$_mw_adminimize_own_option_[ $post_type ] = trim( $_mw_adminimize_own_option_[ $post_type ] );
						$metaboxes_names[] = $_mw_adminimize_own_option_[ $post_type ];
					}

					$x = 0;
					foreach ( $metaboxes as $index => $metabox ) {
						if ( '' !== $metabox ) {
							$checked_user_role_ = array();
							foreach ( (array) $user_roles as $role ) {
								$disabled_metaboxes_[ $post_type . '_' . $role ] = _mw_adminimize_get_option_value(
									'mw_adminimize_disabled_metaboxes_' . $post_type . '_' . $role . '_items'
								);
								$checked_user_role_[ $post_type . '_' . $role ]  = (
									isset( $disabled_metaboxes_[ $post_type . '_' . $role ] )
									&& in_array(
										$metabox, $disabled_metaboxes_[ $post_type . '_' . $role ], TRUE
									)
								) ? ' checked="checked"' : '';
							}
							echo '<tr>' . "\n";
							echo '<td>' . $metaboxes_names[ $index ] .
								' <span>(' . $metabox . ')</span> </td>' . "\n";
							foreach ( $user_roles as $role_slug ) {
								echo '<td class="num">';
								echo '<input id="check_' .
									$post_type . $role_slug . $x . '" class="write_cp_options_'
									. $post_type .
									'_' . $role_slug . '" type="checkbox"' .
									$checked_user_role_[ $post_type . '_' . $role_slug ] .
									' name="mw_adminimize_disabled_metaboxes_' . $post_type .
									'_' . $role_slug . '_items[]" value="' . $metabox . '" />';
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
				// Your own post options
				?>
				<br style="margin-top: 10px;" />
				<table summary="config_own_post" class="widefat">
					<thead>
					<tr>
						<th>
							<?php echo sprintf(
								esc_attr__( 'Your own %s options', 'adminimize' ),
								$post_type_object->label
							);
							echo '<br />';
							esc_attr_e( 'Option name', 'adminimize' ); ?>
						</th>
						<th><?php echo '<br />';
							esc_attr_e( 'Selector, ID or class', 'adminimize' ); ?></th>
					</tr>
					</thead>

					<tbody>
					<tr valign="top">
						<td colspan="2">
							<?php esc_attr_e(
								'It is possible to add your own IDs or classes from elements and tags. You can find IDs and classes with the FireBug Add-on for Firefox. Assign a value and the associate name per line.',
								'adminimize'
							); ?>
						</td>
					</tr>
					<tr valign="top">
						<td>
									<textarea name="_mw_adminimize_own_options_<?php echo $post_type; ?>"
										cols="60" rows="3"
										id="_mw_adminimize_own_options_<?php echo $post_type; ?>"
										style="width: 95%;"><?php echo _mw_adminimize_get_option_value(
											'_mw_adminimize_own_options_' . $post_type
										); ?></textarea>
							<br />
							<label for="_mw_adminimize_own_options_<?php echo $post_type; ?>">
							<?php esc_attr_e(
								'Possible nomination for ID or class. Separate multiple nominations through a carriage return.',
								'adminimize'
							); ?>
							</label>
						</td>
						<td>
							<textarea class="code" name="_mw_adminimize_own_values_<?php echo $post_type; ?>"
								cols="60" rows="3"
								id="_mw_adminimize_own_values_<?php echo $post_type; ?>"
								style="width: 95%;"><?php echo _mw_adminimize_get_option_value(
								'_mw_adminimize_own_values_' . $post_type
							); ?></textarea>
							<br />
							<label for="_mw_adminimize_own_values_<?php echo $post_type; ?>">
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
					<input class="button button-primary" type="submit"
						name="_mw_adminimize_save"
						value="<?php esc_attr_e( 'Update Options', 'adminimize' ); ?> &raquo;" />
					<input type="hidden" name="page_options" value="'dofollow_timeout'" />
				</p>

                <a class="alignright button adminimize-scroltop" href="#"
						onclick="window.scrollTo(0,0);" style="margin:3px 0 0 30px;">
						<?php esc_attr_e( 'scroll to top', 'adminimize' ); ?></a>
					<br class="clear" />
				</p>

			</div>
		</div>
	</div>

<?php } // end foreach ?>
