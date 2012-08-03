<?php 
namespace Adminimize\Part;

require_once 'class-adminimize-part-base-meta-box.php';

/**
 * Options to hide menu entries.
 */
class Links_Options extends \Adminimize\Part\Base_Meta_Box {

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
		\Adminimize\adminimize_generate_checkbox_table( $args );
	}

}

Links_Options::get_instance();
