<?php

/**
 * Table wrapper for settings metabox content.
 */
function adminimize_meta_box_backend_options_page() {
	?>
	<table summary="config" class="widefat">
		<tbody>
			<?php do_settings_fields( Adminimize_Options_Page::$pagehook, 'backend-options' ); ?>
		</tbody>
	</table>

	<br style="clear: both">
	<?php submit_button( __( 'Save Changes' ), 'button-primary', 'submit', TRUE ); ?>
	<br style="clear: both">
	<?php	
}

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