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
		$this->widgets           = array( 1 );

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
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers MenuPage_Widgets_SAPI::enqueue_scripts()
	 */
	public function testEnqueue_scripts(){
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers MenuPage_Widgets_SAPI::add_screen_options()
	 */
	public function testAdd_screen_options(){
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers MenuPage_Widgets_SAPI::add_widgets()
	 */
	public function testAdd_widgets(){
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers MenuPage_Widgets_SAPI::display_widgets()
	 */
	public function testDisplay_widgets(){
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}

}