<?php 

/**
 * Table wrapper for settings metabox content.
 */
function adminimize_meta_box_global_options_page() {
	$user_roles_names = adminimize_get_all_user_roles_names();
	$user_roles       = adminimize_get_all_user_roles();

	$settings = array(
		'admin_bar' => array(
			'title'    => __( 'Admin Bar', 'adminimize' ),
			'css_path' => '.show-admin-bar'
		),
		'fav_actions' => array(
			'title'    => __( 'Favorite Actions', 'adminimize' ),
			'css_path' => '#favorite-actions'
		),
		'screen_meta' => array(
			'title'    => __( 'Screen-Meta', 'adminimize' ),
			'css_path' => '#screen-meta'
		),
		'screen_options' => array(
			'title'    => __( 'Screen Options', 'adminimize' ),
			'css_path' => '#screen-options, #screen-options-link-wrap'
		),
		'context_help' => array(
			'title'    => __( 'Contextual Help', 'adminimize' ),
			'css_path' => '#contextual-help-link-wrap'
		),
		'admin_color_scheme' => array(
			'title'    => __( 'Admin Color Scheme', 'adminimize' ),
			'css_path' => '#your-profile .form-table fieldset'
		),

	);

	?>
	<table summary="config" class="widefat">
		<thead>
			<th>
				<?php echo __( 'Option', 'adminimize' ); ?>
			</th>
			<?php foreach ( $user_roles_names as $role_name ): ?>
				<th>
					<?php echo $role_name; ?>
				</th>
			<?php endforeach ?>
		</thead>
		<tbody>
			<?php foreach ( $settings as $index => $setting ): ?>
				<?php $values = adminimize_get_option( $index, array(), 'adminimize_backend' ); ?>
				<tr>
					<td>
						<?php echo $setting['title']; ?>
						<span class="description">(<?php echo $setting['css_path']; ?>)</span>
					</td>
					<?php foreach ( $user_roles as $role ): ?>
						<td>
							<input type="checkbox" name="adminimize_backend[<?php echo $index ?>][<?php echo $role ?>]" <?php checked( $values[ $role ], 'on' ) ?> >
						</td>
					<?php endforeach ?>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>

	<h4><?php _e( 'Your own options', 'adminimize' ); ?></h4>

	<p class="description">
		<?php _e( 'It is possible to add your own IDs or classes from elements and tags. You can find IDs and classes with the FireBug Add-on for Firefox. Assign a value and the associate name per line.', 'adminimize' ); ?>
	</p>

	<table summary="config_edit_post" class="widefat">
		<thead>
			<tr>
				<th><?php _e( 'ID or class', 'adminimize' ); ?></th>
				<th><?php _e( 'Option', 'adminimize' ); ?></th>
			</tr>
		</thead>

		<tbody>
			<tr valign="top">
				<td>
					<textarea class="code" name="adminimize_custom[options]" cols="60" rows="3" style="width: 95%;" ><?php echo adminimize_get_option( 'options', '', 'adminimize_custom' ); ?></textarea>
					<br />
					<span class="description">
						<?php _e( 'Possible nomination for ID or class. Separate multiple nominations through a carriage return.', 'adminimize' ); ?>
					</span>
				</td>
				<td>
					<textarea class="code" name="adminimize_custom[values]" cols="60" rows="3" id="_mw_adminimize_own_values" style="width: 95%;" ><?php echo adminimize_get_option( 'values', '', 'adminimize_custom' ); ?></textarea>
					<br />
					<span class="description">
						<?php _e( 'Possible IDs or classes. Separate multiple values through a carriage return.', 'adminimize' ); ?>
					</span>
				</td>
			</tr>
		</tbody>
	</table>

	<br style="clear: both">
	<?php submit_button( __( 'Save Changes' ), 'button-primary', 'submit', TRUE ); ?>
	<br style="clear: both">
	<?php	
}

function adminimize_add_meta_box_global_options() {

	add_meta_box(
		/* $id,           */ 'adminimize_add_meta_box_global_options',
		/* $title,        */ __( 'Deactivate Backend Options for Roles', 'adminimize' ),
		/* $callback,     */ 'adminimize_meta_box_global_options_page',
		/* $post_type,    */ Adminimize_Options_Page::$pagehook,
		/* $context,      */ 'normal'
		/* $priority,     */
		/* $callback_args */
	);
	
}

add_action( 'admin_menu', 'adminimize_add_meta_box_global_options', 20 );
add_action( 'network_admin_menu', 'adminimize_add_meta_box_global_options', 20 );
