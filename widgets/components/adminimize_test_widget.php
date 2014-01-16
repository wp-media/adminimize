<?php
if ( ! class_exists( 'Adminimize_Test_Widget' ) ) {

class Adminimize_Test_Widget extends Adminimize_Base_Widget implements I_Adminimize_Widget
{

	public function get_attributes() {

		return array(
				'id'            => 'test-widget',
				'title'         => __( 'Test Options Widget for Developers', ADMINIMIZE_TEXTDOMAIN ),
				'context'       => 1,
				'callback'      => array( $this, 'test_2' ),
				'priority'      => 'default',
				'callback_args' => array(),
				'option_name'   => 'test_option'
		);


	}

	public function get_hooks() {
		return false;
	}

	public function content() {}

	/**
	 * Test Options Widget
	 * @param boolean $get If the widget callback should return the widget fields or not
	 * @return array	Returns an array with the widget propperties or output the widget content
	 */
	public function test_1() {
// 		$screen = get_current_screen();
// 		var_dump( array_keys( $GLOBALS['wp_meta_boxes'] ), $screen->id );
	}

	public function test_2() {
		/*
		 * starting the widget content
		 */
		$attr = $this->get_attributes();

		$option = $attr['option_name'];

		$test_option = $this->storage->get_option( $option );

		$v1 = new stdClass();
		$v1->text     = __( 'A test option: ', ADMINIMIZE_TEXTDOMAIN );
		$v1->name_arg = $this->templater->get_name_arg( $option );
		$v1->value    = ( ! empty( $test_option ) ) ? $test_option : 'nothing';
		$v1_pat       = '{text} <input type="text" size="10" value="{value}" {name_arg} />';

		$v2 = new stdClass();
		$v2->label = $this->templater->get_label( $option );
		$v2->input = $this->templater->sprintf( $v1_pat, $v1 );
		$v2_pat    = '{label}{input}</label>';

		$out  = $this->templater->sprintf( $v2_pat, $v2 );
		$out .= $this->templater->get_submitbutton();

		print( $out );

// 		var_dump( $this->storage->get_option() );

	}

}

}