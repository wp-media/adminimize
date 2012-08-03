<?php 
namespace Adminimize\Part;

require_once 'class-adminimize-part-base-meta-box.php';

/**
 * Options to hide menu entries.
 */
class Write_Post_Options extends \Adminimize\Part\Base_Meta_Box {

	/**
	 * Get translated meta box title.
	 * 
	 * @return string
	 */
	public function get_meta_box_title() {
		return __( 'Deactivate Write Post Options for Roles', 'adminimize' );
	}

	/**
	 * Get option namespace.
	 *
	 * Will be used to serialize settings.
	 * 
	 * @return string
	 */
	public function get_option_namespace() {
		return 'adminimize_write_post';
	}

	/**
	 * Populate $settings var with data.
	 * 
	 * @return void
	 */
	protected function init_settings() {

		$this->settings = array(
			'help' => array(
				'title' => __( 'Help', 'adminimize' ),
				'description' => '#contextual-help-link-wrap'
			),
			'screen_options' => array(
				'title' => __( 'Screen Options', 'adminimize' ),
				'description' => '#screen-options-link-wrap'
			),
			'title' => array(
				'title' => __( 'Title', 'adminimize' ),
				'description' => '#title, #titlediv, th.column-title, td.title'
			),
			'permalink' => array(
				'title' => __( 'Permalink', 'adminimize' ),
				'description' => '#pageslugdiv'
			),
			'tags' => array(
				'title' => __( 'Tags', 'adminimize' ),
				'description' => '#tags, #tagsdiv,#tagsdivsb,#tagsdiv-post_tag, th.column-tags, td.tags'
			),
			'new_category' => array(
				'title' => __( 'Add New Category', 'adminimize' ),
				'description' => '#category-add-toggle'
			),
			'date' => array(
				'title' => __( 'Date', 'adminimize' ),
				'description' => '#date, #datediv, th.column-date, td.date, div.curtime'
			),
			'password_protect' => array(
				'title' => __( 'Password Protect This Post', 'adminimize' ),
				'description' => '#passworddiv'
			),
			'related_shortcuts' => array(
				'title' => __( 'Related, Shortcuts', 'adminimize' ),
				'description' => '.side-info'
			),
			'messages' => array(
				'title' => __( 'Messages', 'adminimize' ),
				'description' => '#notice'
			),
			'advanced_options' => array(
				'title' => __( 'h2: Advanced Options', 'adminimize' ),
				'description' => '#post-body h2'
			),
			'media_buttons' => array(
				'title' => __( 'Media Buttons', 'adminimize' ),
				'description' => 'all) (#media-buttons, #wp-content-media-buttons'
			),
			'word_count' => array(
				'title' => __( 'Word count', 'adminimize' ),
				'description' => '#wp-word-count'
			),
			'post_slug' => array(
				'title' => __( 'Post Slug', 'adminimize' ),
				'description' => '#slugdiv,#edit-slug-box'
			),
			'publish_actions' => array(
				'title' => __( 'Publish Actions', 'adminimize' ),
				'description' => '#misc-publishing-actions'
			),
			'discussion' => array(
				'title' => __( 'Discussion', 'adminimize' ),
				'description' => '#commentstatusdiv'
			),
			'html_editor_button' => array(
				'title' => __( 'HTML Editor Button', 'adminimize' ),
				'description' => '#editor-toolbar #edButtonHTML, #quicktags, #content-html'
			),
			'editor' => array(
				'title' => __( 'Editor', 'adminimize' ),
				'description' => '#editor, #editordiv, th.column-editor, td.editor'
			),
			'author' => array(
				'title' => __( 'Author', 'adminimize' ),
				'description' => '#author, #authordiv, th.column-author, td.author'
			),
			'thumbnail' => array(
				'title' => __( 'Thumbnail', 'adminimize' ),
				'description' => '#thumbnail, #thumbnaildiv, th.column-thumbnail, td.thumbnail'
			),
			'excerpt' => array(
				'title' => __( 'Excerpt', 'adminimize' ),
				'description' => '#postexcerpt, #postexcerptdiv, th.column-postexcerpt, td.postexcerpt'
			),
			'trackbacks' => array(
				'title' => __( 'Trackbacks', 'adminimize' ),
				'description' => '#trackbacks, #trackbacksdiv, th.column-trackbacks, td.trackbacks'
			),
			'custom_fields' => array(
				'title' => __( 'Custom-fields', 'adminimize' ),
				'description' => '#postcustom, #postcustomdiv, th.column-postcustom, td.postcustom'
			),
			'comments' => array(
				'title' => __( 'Comments', 'adminimize' ),
				'description' => '#comments, #commentsdiv, th.column-comments, td.comments'
			),
			'revisions' => array(
				'title' => __( 'Revisions', 'adminimize' ),
				'description' => '#revisions, #revisionsdiv, th.column-revisions, td.revisions'
			),
			'post_formats' => array(
				'title' => __( 'Post-formats', 'adminimize' ),
				'description' => '#format, #formatdiv, th.column-format, td.format'
			),
			'post_thumbnail' => array(
				'title' => __( 'Post Thumbnail', 'adminimize' ),
				'description' => '#postimagediv'
			),
			'quick_edit_link' => array(
				'title' => __( 'Quick Edit Link', 'adminimize' ),
				'description' => 'div.row-actions, div.row-actions .inline'
			),
			'qe_inline_edit_left' => array(
				'title' => __( 'QE Inline Edit Left', 'adminimize' ),
				'description' => 'fieldset.inline-edit-col-left'
			),
			'qe_all_labels' => array(
				'title' => __( ' QE ⇒ All Labels', 'adminimize' ),
				'description' => 'fieldset.inline-edit-col-left label'
			),
			'qe_author' => array(
				'title' => __( ' QE ⇒ Author', 'adminimize' ),
				'description' => 'fieldset.inline-edit-col-left label.inline-edit-author'
			),
			'qe_password_and_private' => array(
				'title' => __( ' QE ⇒ Password and Private', 'adminimize' ),
				'description' => 'fieldset.inline-edit-col-left .inline-edit-group'
			),
			'qe_inline_edit_center' => array(
				'title' => __( 'QE Inline Edit Center', 'adminimize' ),
				'description' => 'fieldset.inline-edit-col-center'
			),
			'qe_categories_title' => array(
				'title' => __( ' QE ⇒ Categories Title', 'adminimize' ),
				'description' => 'fieldset.inline-edit-col-center .inline-edit-categories-label'
			),
			'qe_categories_list' => array(
				'title' => __( ' QE ⇒ Categories List', 'adminimize' ),
				'description' => 'fieldset.inline-edit-col-center .category-checklist'
			),
			'qe_inline_edit_right' => array(
				'title' => __( 'QE Inline Edit Right', 'adminimize' ),
				'description' => 'fieldset.inline-edit-col-right'
			),
			'qe_tags' => array(
				'title' => __( ' QE ⇒ Tags', 'adminimize' ),
				'description' => 'fieldset.inline-edit-col-right .inline-edit-tags'
			),
			'qe_status_sticky' => array(
				'title' => __( ' QE ⇒ Status, Sticky', 'adminimize' ),
				'description' => 'fieldset.inline-edit-col-right .inline-edit-group'
			),
			'qe_cancel_save_button' => array(
				'title' => __( 'QE Cancel/Save Button', 'adminimize' ),
				'description' => 'tr.inline-edit-post p.inline-edit-save'
			)
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

Write_Post_Options::get_instance();
