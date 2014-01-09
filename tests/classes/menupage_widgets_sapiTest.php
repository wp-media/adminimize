<?php
require_once dirname( dirname( dirname( __FILE__ ) ) ) . '\classes\menupage_widgets_sapi.php';

class Dummy_MPWS extends MenuPage_Widgets_SAPI {

	public function __construct( $pass = true ){
		if ( true == $pass )
			$this->pass();
	}

	private function pass() {

		$this->page_callback     = array( $this, 'main_frame' );
		$this->validate_callback = array( $this, 'validate_callback' );
		$this->option_name       = 'test';
		$this->option_group      = 'test';
		$this->columns           = array( 'column1', 'column2' );
		$this->widgets           = array(
		  array(
		    'id'            => 'test_1_widget',
		    'title'         => 'Test Options Widget for Developers',
		    'callback'      => array ( $this, 'dummy_text' ) ,
		    'post_type'     => '',
		    'context'       => 'column1',
		    'priority'      => 'default',
		    'callback_args' => array (),
		    'option_name'   => 'test_1_option',
		  ),

		  array(
		    'id'            => 'test_2_widget',
		    'title'         => 'Test Options Widget for Developers',
		    'callback'      => array ( $this, 'dummy_text' ) ,
		    'post_type'     => '',
		    'context'       => 'column2',
		    'priority'      => 'default',
		    'callback_args' => array (),
		    'option_name'   => 'test_2_option',
		  ),

		);

		parent::__construct();

	}

	public function validate_callback( $input ){}
	public function main_frame(){}
	public function default_widget(){}

}

/**
 * Test class for abstarct class MenuPage_Widgets_SAPI
 */
class MenuPage_Widgets_SAPITest extends \WP_UnitTestCase
{

	public $object = null;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	public function setUp() {

		global $wpdb;

		$wpdb->db_connect();

		$this->object = new Dummy_MPWS();

	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	public function tearDown() {}

	/**
	 * @covers MenuPage_Widgets_SAPI::__construct()
	 */
	public function testConstruct() {

		$this->assertTrue( $this->object->valid_instance );

		$bad_object = new Dummy_MPWS( false );
		$this->assertFalse( $bad_object->valid_instance );

	}

	/**
	 * @covers MenuPage_Widgets_SAPI::check_settings()
	 */
	public function testCheck_settings() {

		$this->assertTrue( $this->object->check_settings() );

		$this->object->widgets = array();
		$this->assertFalse( $this->object->check_settings() );
		$this->assertCount( 1, $this->object->errors );
		$this->assertArrayHasKey( 'no_widgets', $this->object->errors );

		$this->object->option_name = '';
		$this->assertFalse( $this->object->check_settings() );
		$this->assertCount( 2, $this->object->errors );
		$this->assertArrayHasKey( 'option_name', $this->object->errors );

		$this->object->page_callback = '';
		$this->assertFalse( $this->object->check_settings() );
		$this->assertCount( 3, $this->object->errors );
		$this->assertArrayHasKey( 'page_callback', $this->object->errors );

	}

	/**
	 * @covers MenuPage_Widgets_SAPI::get_errors()
	 */
	public function testGet_errors() {
		$errors = $this->object->get_errors();
		$this->assertCount( 0, $errors );
	}

	/**
	 * @covers MenuPage_Widgets_SAPI::settings_api_init()
	 */
	public function testSettings_api_init(){

		global $new_whitelist_options;

		$this->object->settings_api_init();
		$this->assertArrayHasKey( $this->object->option_group, $new_whitelist_options );

		$filter = "sanitize_option_{$this->object->option_name}";
		$this->assertArrayHasKey( $filter, $GLOBALS['wp_filter'] );

	}

	/**
	 * @covers MenuPage_Widgets_SAPI::add_menu_page()
	 */
	public function testAdd_menu_page(){

		wp_set_current_user( 1 );

		$pagehook = $this->object->add_menu_page();
		$this->assertNotEmpty( $pagehook );
		$this->assertNotEmpty( $this->object->pagehook );

	}

	/**
	 * @covers MenuPage_Widgets_SAPI::enqueue_scripts()
	 */
	public function testEnqueue_scripts(){

		$this->object->enqueue_scripts();
		$tag = $this->object->script_tag;
		$this->assertArrayHasKey( $tag, $GLOBALS['wp_scripts']->registered );

	}

	/**
	 * @covers MenuPage_Widgets_SAPI::add_screen_options()
	 */
	public function testAdd_screen_options(){

		if ( ! function_exists( 'get_current_screen' ) )
			require_once 'wp-admin/includes/screen.php';

		if ( ! function_exists( 'add_meta_box' ) )
			require_once 'wp-admin/includes/template.php';

		$pagehook = $this->object->add_menu_page();
		set_current_screen( $pagehook );

		$this->object->add_screen_options();

		$screen = $this->object->screen;
		$opts   = $screen->get_options( $pagehook );

		$this->assertArrayHasKey( 'layout_columns', $opts );

		$this->assertEquals( $this->object->layout_columns, $opts['layout_columns'] );

	}

	/**
	 * @covers MenuPage_Widgets_SAPI::add_widgets()
	 */
	public function testAdd_widgets(){

		global $wp_meta_boxes;

		$this->object->add_widgets();

		// test just one column
		$cols = array_shift( $wp_meta_boxes );

		foreach ( $this->object->widgets as $widget ) {
			extract( $widget );
			$this->assertEquals( $widget['id'], $cols[$context][$priority][$id]['id'] );
		}

	}

	/**
	 * @covers MenuPage_Widgets_SAPI::display_widgets()
	 */
	public function testDisplay_widgets(){


// 		ob_start();
// 		$this->object->display_widgets( $this->object->screen );
// 		$out = ob_get_flush();

// 		$this->expectOutputString($expectedString);

	}

}