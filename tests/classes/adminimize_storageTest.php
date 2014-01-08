<?php
require_once dirname( dirname( dirname( __FILE__ ) ) ) . '\classes\adminimize_storage.php';

/**
 * Test class for PluginHeaderReader
 */
class Adminimize_StorageTest extends \WP_UnitTestCase
{

	public $object = null;

	public $id = '';

	public $sample = array();

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	public function setUp() {

		global $wpdb;

		$wpdb->db_connect();

		$this->object = new Adminimize_Storage();
		$this->id     = $this->object->id;

		$this->sample = array( 'foo' => 'bar', 'baz' => 1 );

		add_option( Adminimize_Storage::OPTION_KEY, $this->sample );

	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	public function tearDown() {}

	/**
	 * @covers Adminimize_Storage::__construct()
	 */
	public function testConstruct() {
		$this->assertTrue( $this->object->id === $this->id );

	}

	/**
	 * @covers Adminimize_Storage::set_basedirs()
	 */
	public function testSet_basedirs() {

		$this->object->set_basedirs( __FILE__ );
		$expected = plugin_basename( __FILE__ );

		$this->assertEquals( $expected, $this->object->basename );

	}

	/**
	 * @covers Adminimize_Storage::get_option()
	 */
	public function testGet_All_Options() {

		$options = $this->object->get_option();
		$this->assertCount( count( $this->sample ), $options );


	}

	/**
	 * @covers Adminimize_Storage::get_option()
	 */
	public function testGet_Single_Option() {

		$option = $this->object->get_option( 'foo' );
		$this->assertTrue( 'bar' === $option );

		// fail
		$option = $this->object->get_option( 'igel' );
		$this->assertNull( $option );

	}

	/**
	 * @covers Adminimize_Storage::set_option()
	 */
	public function testSet_Option() {

		$old_value    = $this->sample['foo'];
		$new_value    = 'bazbazbaz';

		$result = $this->object->set_option( 'foo', $new_value );
		$stored_value = $this->object->get_option( 'foo' );

		$this->assertTrue( $result );
		$this->assertTrue( $stored_value === $new_value );
		$this->assertFalse( $stored_value === $old_value );

		// fail
		$result = $this->object->set_option( '', $new_value );
		$this->assertFalse( $result );

	}

}