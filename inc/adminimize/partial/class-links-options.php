<?php 
namespace Inpsyde\Adminimize\Partial;
use Inpsyde\Adminimize;

/**
 * Options to hide menu entries.
 */
class Links_Options extends Checkbox_Base {

	/**
	 * This method is called for every setting that is active.
	 *
	 * Generates CSS to hide link form elements.
	 * 
	 * @param  string $index  setting index
	 * @param  array  $values setting values
	 * @param  string $role   WordPress role handle
	 * @return void
	 */
	public function apply_checkbox_setting( $index, $values, $role ) {
		add_action( 'admin_print_styles-link.php', function () use ( $values ) {
			?>
			<style type="text/css"><?php echo $values['description']; ?> { display: none; }</style>
			<?php
		} );
	}

	/**
	 * Get translated meta box title.
	 * 
	 * @return string
	 */
	public function get_meta_box_title() {
		return __( 'Deactivate Links Options for Roles', 'adminimize' );
	}

	/**
	 * Get option namespace.
	 *
	 * Will be used to serialize settings.
	 * 
	 * @return string
	 */
	public function get_option_namespace() {
		return 'adminimize_links';
	}

	/**
	 * Populate $settings var with data.
	 * 
	 * @return void
	 */
	protected function init_settings() {

		$this->settings = array(
			'name' => array(
				'title'    => __( 'Name', 'adminimize' ),
				'description' => '#namediv'
			),
			'web_address' => array(
				'title'    => __( 'Web Address', 'adminimize' ),
				'description' => '#addressdiv'
			),
			'description' => array(
				'title'    => __( 'Description', 'adminimize' ),
				'description' => '#descriptiondiv'
			),
			'categories' => array(
				'title'    => __( 'Categories', 'adminimize' ),
				'description' => '#linkcategorydiv'
			),
			'target' => array(
				'title'    => __( 'Target', 'adminimize' ),
				'description' => '#linktargetdiv'
			),
			'link_relationship' => array(
				'title'    => __( 'Link Relationship (XFN)', 'adminimize' ),
				'description' => '#linkxfndiv'
			),
			'advanced' => array(
				'title'    => __( 'Advanced', 'adminimize' ),
				'description' => '#linkadvanceddiv'
			),
			'publish_actions' => array(
				'title'    => __( 'Publish Actions', 'adminimize' ),
				'description' => '#misc-publishing-actions'
			),
		);

	}

	/**
	 * Print meta box contents.
	 * 
	 * @return void
	 */
	public function meta_box_content() {

		$args = array(
			'option_namespace' => $this->get_option_namespace(),
			'settings'         => $this->get_settings()
		);
		Adminimize\generate_checkbox_table( $args );
	}

}
