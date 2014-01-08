<?php
require_once dirname( dirname( dirname( __FILE__ ) ) ) . '\classes\adminimize_storage.php';

class Dummy_WPDB extends wpdb
{
	public function get_dbh() {
		return $this->dbh;
	}
}
/**
 * Test class for PluginHeaderReader
 */
class Adminimize_StorageTest extends \WP_UnitTestCase
{

	public $object = null;

	public $id = 'test';

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	public function setUp() {
		$this->object = new Adminimize_Storage( $this->id );
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

		$ref = new ReflectionClass( $this->object );
		$id = $ref->getStaticPropertyValue( 'id' );

		$this->assertTrue( $id === $this->id );

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
	public function testGet_option() {

// 		$db = new wpdb( 'root', '', 'wp_unittesting', 'localhost' );
// // 		var_dump( $db->get_dbh() );
// 		var_dump( $db->_real_escape( 'hello#world' ) );


		// get all options
		$options = $this->object->get_option();
		var_dump( $options );

	}

}