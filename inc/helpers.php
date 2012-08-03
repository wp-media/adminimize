<?php

/**
 * Callback for add_settings_field to create a <select>.
 * 
 * @param  array $args Args has two parameters: 'settings_name' and 'args'.
 *                     settings_name:    (string) required. settings name in adminimize settings namespace
 *                     args: options     (array)  required. option list values
 *                           disabled    (bool)   optional.
 *                           description (string) optional.
 */
function adminimize_field_with_select( $args ) {

	$settings_name = $args['settings_name'];
	$args          = $args['args'];
	$disabled_attr = isset( $args['disabled'] ) && $args['disabled'] ? ' disabled="disabled"' : '';

	$current_value = adminimize_get_option($settings_name);

	do_action( 'before_adminimize_field_with_select' );
	do_action( 'before_adminimize_field_with_select-' . $settings_name );
	?>

	<select name="adminimize[<?php echo $settings_name ?>]"<?php echo $disabled_attr; ?>>
		<?php foreach ($args['options'] as $value => $title): ?>
			<option value="<?php echo $value ?>"<?php selected( $value, $current_value ) ?>><?php echo $title ?></option>
		<?php endforeach ?>
	</select>

	<?php if ( $args['description'] ): ?>
		<span class="description"><?php echo $args['description']; ?></span>
	<?php endif ?>

	<?php
	do_action( 'after_adminimize_field_with_select' );
	do_action( 'after_adminimize_field_with_select-' . $settings_name );
}

function adminimize_generate_checkbox_table( $args ) {

	$defaults = array(
		'option_namespace' => '',
		'settings'         => array(),
		'custom_options'   => true
	);
	$args = wp_parse_args( $args, $defaults );

	$user_roles_names = adminimize_get_all_user_roles_names();
	$user_roles       = adminimize_get_all_user_roles();

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
			<?php foreach ( $args['settings'] as $index => $setting ): ?>
				<?php $values = adminimize_get_option( $index, array(), $args['option_namespace'] ); ?>
				<tr>
					<td>
						<?php echo $setting['title']; ?>
						<span class="description">(<?php echo $setting['description']; ?>)</span>
					</td>
					<?php foreach ( $user_roles as $role ): ?>
						<td>
							<input type="checkbox" name="<?php echo $args['option_namespace'] ?>[<?php echo $index ?>][<?php echo $role ?>]" <?php checked( $values[ $role ], 'on' ) ?> >
						</td>
					<?php endforeach ?>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>

	<?php if ( $args['custom_options'] ): ?>
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
						<textarea class="code" name="<?php echo $args['option_namespace'] ?>_custom[options]" cols="60" rows="3" style="width: 95%;" ><?php echo adminimize_get_option( 'options', '', $args['option_namespace'] . '_custom' ); ?></textarea>
						<br />
						<span class="description">
							<?php _e( 'Possible nomination for ID or class. Separate multiple nominations through a carriage return.', 'adminimize' ); ?>
						</span>
					</td>
					<td>
						<textarea class="code" name="<?php echo $args['option_namespace'] ?>_custom[values]" cols="60" rows="3" id="_mw_adminimize_own_values" style="width: 95%;" ><?php echo adminimize_get_option( 'values', '', $args['option_namespace'] . '_custom' ); ?></textarea>
						<br />
						<span class="description">
							<?php _e( 'Possible IDs or classes. Separate multiple values through a carriage return.', 'adminimize' ); ?>
						</span>
					</td>
				</tr>
			</tbody>
		</table>
	<?php endif ?>

	<br style="clear: both">
	<?php submit_button( __( 'Save Changes' ), 'button-primary', 'submit', TRUE ); ?>
	<br style="clear: both">
	<?php	
}