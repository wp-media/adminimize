<?php
function adminimize_add_meta_box_about() {
	add_meta_box(
		/* $id,           */ 'adminimize_add_meta_box_about',
		/* $title,        */ __( 'About the Plugin', 'adminimize' ),
		/* $callback,     */ 'adminimize_meta_box_about_page',
		/* $post_type,    */ Adminimize_Options_Page::$pagehook,
		/* $context,      */ 'side'
		/* $priority,     */
		/* $callback_args */
	);
}
add_action( 'admin_menu', 'adminimize_add_meta_box_about', 20 );
add_action( 'network_admin_menu', 'adminimize_add_meta_box_about', 20 );

function adminimize_meta_box_about_page() {
	?>
	<p style="text-align: center">
		<a href="http://www.inpsyde.com" target="_blank">
			<img src="<?php echo plugin_dir_url( __FILE__ ) . '../images/inpsyde_logo.png' ?>" style="border: 7px solid #fff">
		</a>
	</p>
	<p>
		<?php 
		echo _mw_adminimize_get_plugin_data( 'Title' ) . ' ';
		echo __( 'Version', FB_ADMINIMIZE_TEXTDOMAIN ) . ' ';
		echo _mw_adminimize_get_plugin_data( 'Version' );
		?>
	</p>
	<p>
		<?php 
		echo _mw_adminimize_get_plugin_data( 'Description' );
		?>
	</p>
	<?php
}