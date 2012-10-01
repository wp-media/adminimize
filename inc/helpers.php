<?php
namespace Inpsyde\Adminimize;
use Inpsyde\Adminimize;

/**
 * Return .dev suffix if SCRIPT_DEBUG is set.
 * 
 * @return string
 */
function script_suffix() {
	return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.dev' : '';
}

/**
 * Override default plugins_url() with second parameter set correctly.
 * 
 * @param  string $url url relative to plugin dir
 * @return string
 */
function plugins_url( $url ) {
	return \plugins_url( $url, dirname( __FILE__ ) );
}

/**
 * Remove entry from main WordPress menu.
 * 
 * @param  int $menu_index
 * @return void
 */
function remove_mainmenu_entry( $menu_index ) {
	global $menu;

	unset( $menu[ $menu_index ] );
}

/**
 * Return true if this is a page where you can edit a post. Otherwise false.
 * 
 * @return boolean
 */
function is_edit_post_page() {
	global $pagenow;

	return ! is_admin() || ! in_array( $pagenow, array( 'edit.php', 'post.php', 'post-new.php' ) );
}

/**
 * Remove entry from a WordPress submenu.
 * 
 * @param  int $menu_index
 * @param  int $submenu_index
 * @return void
 */
function remove_submenu_entry( $menu_index, $submenu_index ) {
	global $menu, $submenu;

	$menu_entry       = $menu[ $menu_index ];
	$menu_entry_link  = $menu_entry[ 2 ];

	unset( $submenu[ $menu_entry_link ][ $submenu_index ] );
}

/**
 * Callback for add_settings_field to create a <select>.
 * 
 * @param  array $args Args has two parameters: 'settings_name' and 'args'.
 *                     settings_name:      (string) required. setting name
 *                     settings_namespace: (string) required. setting namespace
 *                     args: options     (array)  required. option list values
 *                           disabled    (bool)   optional.
 *                           description (string) optional.
 */
function field_with_select( $args ) {

	$settings_name      = $args['settings_name'];
	$settings_namespace = $args['settings_namespace'];

	$args          = $args['args'];
	$disabled_attr = isset( $args['disabled'] ) && $args['disabled'] ? ' disabled="disabled"' : '';

	$current_value = Adminimize\get_option( $settings_name, NULL, $settings_namespace );

	do_action( 'before_adminimize_field_with_select' );
	do_action( 'before_adminimize_field_with_select-' . $settings_name );
	?>

	<select name="<?php echo $settings_namespace; ?>[<?php echo $settings_name ?>]"<?php echo $disabled_attr; ?>>
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

function generate_checkbox_table( $args ) {

	$defaults = array(
		'option_namespace' => '',
		'settings'         => array(),
		'custom_options'   => true
	);
	$args = wp_parse_args( $args, $defaults );

	$user_roles_names = Adminimize\get_all_user_roles_names();
	$user_roles       = Adminimize\get_all_user_roles();

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
				<?php $values = Adminimize\get_option( $index, array(), $args['option_namespace'] ); ?>
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
						<textarea class="code" name="<?php echo $args['option_namespace'] ?>_custom[options]" cols="60" rows="3" style="width: 95%;" ><?php echo Adminimize\get_option( 'options', '', $args['option_namespace'] . '_custom' ); ?></textarea>
						<br />
						<span class="description">
							<?php _e( 'Possible nomination for ID or class. Separate multiple nominations through a carriage return.', 'adminimize' ); ?>
						</span>
					</td>
					<td>
						<textarea class="code" name="<?php echo $args['option_namespace'] ?>_custom[values]" cols="60" rows="3" id="_mw_adminimize_own_values" style="width: 95%;" ><?php echo Adminimize\get_option( 'values', '', $args['option_namespace'] . '_custom' ); ?></textarea>
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